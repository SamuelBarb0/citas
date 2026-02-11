<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\UserMatch;
use App\Models\Message;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
    /**
     * Mostrar el formulario para crear un perfil
     */
    public function create()
    {
        // Verificar si el usuario ya tiene un perfil
        if (Auth::user()->profile) {
            return redirect()->route('user.profile.edit');
        }

        // Obtener datos de perfil guardados en la sesión (si existen)
        $profileData = session('profile_data', []);

        return view('profiles.create', compact('profileData'));
    }

    /**
     * Guardar un nuevo perfil
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'edad' => 'required|integer|min:18|max:100',
            'genero' => 'required|string|max:50',
            'orientacion_sexual' => 'nullable|string|max:50',
            'busco' => 'required|string|max:50',
            'ciudad' => 'required|string|max:255',
            'biografia' => 'nullable|string|max:500',
            'intereses' => 'nullable|array',
            'intereses.*' => 'string|max:50',
            'foto_principal' => 'nullable|image|max:2048',
        ]);

        // Procesar foto si existe (convertir a WebP)
        if ($request->hasFile('foto_principal') && $request->file('foto_principal')->isValid()) {
            $path = $this->imageService->convertToWebP($request->file('foto_principal'), 'profiles');
            $validated['foto_principal'] = $path;
        } elseif (!isset($validated['foto_principal']) || empty($validated['foto_principal'])) {
            // Usar avatar por defecto solo si no hay foto
            $validated['foto_principal'] = 'https://i.pravatar.cc/400?u=' . Auth::id();
        }

        $validated['user_id'] = Auth::id();
        $validated['activo'] = true;

        Profile::create($validated);

        // Redirigir directamente al dashboard para empezar a usar la app
        return redirect()->route('dashboard')
            ->with('success', '¡Perfil creado! Ya puedes empezar a conocer gente en Mallorca.');
    }

    /**
     * Mostrar el formulario de edición del perfil
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            return redirect()->route('user.profile.create');
        }

        $maxFotos = $user->getMaxFotosAdicionales();

        return view('profiles.edit', compact('profile', 'maxFotos'));
    }

    /**
     * Actualizar el perfil
     */
    public function update(Request $request)
    {
        $profile = Auth::user()->profile;

        if (!$profile) {
            return redirect()->route('user.profile.create');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'edad' => 'required|integer|min:18|max:100',
            'genero' => 'required|string|max:50',
            'orientacion_sexual' => 'nullable|string|max:50',
            'busco' => 'required|string|max:50',
            'ciudad' => 'required|string|max:255',
            'biografia' => 'nullable|string|max:500',
            'intereses' => 'nullable|array',
            'intereses.*' => 'string|max:50',
            'foto_principal' => 'nullable|image|max:2048',
            'fotos_adicionales.*' => 'nullable|image|max:2048',
            'fotos_adicionales_existentes' => 'nullable|array',
            'fotos_eliminar' => 'nullable|string',
        ]);

        // === FOTO PRINCIPAL ===
        if ($request->hasFile('foto_principal')) {
            // Eliminar foto principal anterior si no es de pravatar
            if ($profile->foto_principal && !str_contains($profile->foto_principal, 'pravatar') && !str_starts_with($profile->foto_principal, 'http')) {
                $this->imageService->delete($profile->foto_principal);
            }
            // Convertir a WebP
            $validated['foto_principal'] = $this->imageService->convertToWebP($request->file('foto_principal'), 'profiles');
        } else {
            // Mantener la foto principal actual
            unset($validated['foto_principal']);
        }

        // === FOTOS ADICIONALES ===
        // Obtener fotos adicionales existentes que se mantienen
        $fotosAdicionalesExistentes = $request->input('fotos_adicionales_existentes', []);
        if (!is_array($fotosAdicionalesExistentes)) {
            $fotosAdicionalesExistentes = [];
        }

        // Procesar fotos a eliminar
        if ($request->has('fotos_eliminar') && $request->fotos_eliminar) {
            $fotosAEliminar = json_decode($request->fotos_eliminar, true) ?? [];
            foreach ($fotosAEliminar as $fotoEliminar) {
                $this->imageService->delete($fotoEliminar);
                // Quitar de las existentes
                $fotosAdicionalesExistentes = array_values(array_filter($fotosAdicionalesExistentes, fn($f) => $f !== $fotoEliminar));
            }
        }

        // Procesar nuevas fotos adicionales (convertir a WebP)
        $nuevasFotosAdicionales = [];
        if ($request->hasFile('fotos_adicionales')) {
            foreach ($request->file('fotos_adicionales') as $foto) {
                $path = $this->imageService->convertToWebP($foto, 'profiles');
                $nuevasFotosAdicionales[] = $path;
            }
        }

        // Combinar fotos adicionales existentes con nuevas (máximo según plan)
        $maxFotos = Auth::user()->getMaxFotosAdicionales();
        $todasFotosAdicionales = array_merge($fotosAdicionalesExistentes, $nuevasFotosAdicionales);
        $todasFotosAdicionales = array_slice($todasFotosAdicionales, 0, $maxFotos);

        $validated['fotos_adicionales'] = $todasFotosAdicionales;

        // Limpiar campos no necesarios antes de actualizar
        unset($validated['fotos_adicionales_existentes']);
        unset($validated['fotos_eliminar']);

        $profile->update($validated);

        return redirect()->route('user.profile.show')->with('success', 'Perfil actualizado exitosamente!');
    }

    /**
     * Mostrar el perfil del usuario autenticado
     */
    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            return redirect()->route('user.profile.create')->with('info', 'Primero debes crear tu perfil');
        }

        // Obtener matches recientes con perfiles
        $matches = UserMatch::where('user_id_1', $user->id)
            ->orWhere('user_id_2', $user->id)
            ->orderBy('matched_at', 'desc')
            ->take(6)
            ->get()
            ->map(function ($match) use ($user) {
                $otherUserId = $match->user_id_1 === $user->id ? $match->user_id_2 : $match->user_id_1;
                $otherProfile = Profile::where('user_id', $otherUserId)->first();
                $match->otherProfile = $otherProfile;
                return $match;
            });

        // Obtener conversaciones recientes (ultimo mensaje por match)
        $recentMessages = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('match_id')
            ->take(4)
            ->map(function ($messages) use ($user) {
                $lastMessage = $messages->first();
                $otherUserId = $lastMessage->sender_id === $user->id ? $lastMessage->receiver_id : $lastMessage->sender_id;
                $otherProfile = Profile::where('user_id', $otherUserId)->first();
                $lastMessage->otherProfile = $otherProfile;
                $lastMessage->unreadCount = $messages->where('receiver_id', $user->id)->where('leido', false)->count();
                return $lastMessage;
            });

        // Obtener suscripcion activa
        $subscription = $user->activeSubscription;
        $plan = $subscription ? $subscription->plan : null;

        // Contar matches totales
        $matchCount = UserMatch::where('user_id_1', $user->id)
            ->orWhere('user_id_2', $user->id)
            ->count();

        return view('profiles.show', compact('profile', 'matches', 'recentMessages', 'subscription', 'plan', 'matchCount'));
    }

    /**
     * Ver el perfil público de otro usuario
     * Acepta tanto user_id como profile_id para mayor flexibilidad
     */
    public function viewPublic($id)
    {
        // Primero intentar buscar por user_id (más común en la app)
        $profile = Profile::with('user')->where('user_id', $id)->first();

        // Si no se encuentra, intentar buscar por profile_id (fallback)
        if (!$profile) {
            $profile = Profile::with('user')->find($id);
        }

        // Si aún no se encuentra, mostrar 404
        if (!$profile) {
            abort(404, 'Perfil no encontrado');
        }

        $user = $profile->user;

        return view('profiles.public', compact('profile', 'user'));
    }
}
