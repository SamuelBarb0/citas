<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
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
            'genero' => 'required|in:hombre,mujer,no-binario,genero-fluido,otro,prefiero-no-decir',
            'orientacion_sexual' => 'nullable|in:heterosexual,gay,lesbiana,bisexual,pansexual,asexual,queer,otra,prefiero-no-decir',
            'busco' => 'required|in:hombre,mujer,no-binario,cualquiera',
            'ciudad' => 'required|string|max:255',
            'biografia' => 'nullable|string|max:500',
            'intereses' => 'nullable|array',
            'intereses.*' => 'string|max:50',
            'foto_principal' => 'nullable|image|max:2048',
        ]);

        // Procesar foto si existe
        if ($request->hasFile('foto_principal')) {
            $path = $request->file('foto_principal')->store('profiles', 'public');
            $validated['foto_principal'] = $path;
        } else {
            // Usar avatar por defecto
            $validated['foto_principal'] = 'https://i.pravatar.cc/400?u=' . Auth::id();
        }

        $validated['user_id'] = Auth::id();
        $validated['activo'] = true;

        Profile::create($validated);

        // Redirigir a verificación de identidad obligatoria
        return redirect()->route('verification.create')
            ->with('success', '¡Perfil creado! Ahora debes verificar tu identidad para poder usar la app.');
    }

    /**
     * Mostrar el formulario de edición del perfil
     */
    public function edit()
    {
        $profile = Auth::user()->profile;

        if (!$profile) {
            return redirect()->route('user.profile.create');
        }

        return view('profiles.edit', compact('profile'));
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
            'nuevas_fotos.*' => 'nullable|image|max:2048',
            'fotos_orden' => 'nullable|array',
            'fotos_eliminar' => 'nullable|string',
            'foto_principal_index' => 'nullable|integer|min:0',
        ]);

        // Obtener fotos actuales
        $fotosActuales = [];
        if ($profile->foto_principal) {
            $fotosActuales[] = $profile->foto_principal;
        }
        if ($profile->fotos_adicionales && is_array($profile->fotos_adicionales)) {
            $fotosActuales = array_merge($fotosActuales, $profile->fotos_adicionales);
        }
        $fotosActuales = array_values(array_unique($fotosActuales));

        // Procesar fotos a eliminar
        $fotosAEliminar = [];
        if ($request->has('fotos_eliminar') && $request->fotos_eliminar) {
            $fotosAEliminar = json_decode($request->fotos_eliminar, true) ?? [];
            foreach ($fotosAEliminar as $fotoEliminar) {
                if (!str_contains($fotoEliminar, 'pravatar') && !str_starts_with($fotoEliminar, 'http')) {
                    Storage::disk('public')->delete($fotoEliminar);
                }
                $fotosActuales = array_values(array_filter($fotosActuales, fn($f) => $f !== $fotoEliminar));
            }
        }

        // Procesar nuevas fotos
        $nuevasFotos = [];
        if ($request->hasFile('nuevas_fotos')) {
            foreach ($request->file('nuevas_fotos') as $foto) {
                $path = $foto->store('profiles', 'public');
                $nuevasFotos[] = $path;
            }
        }

        // Combinar fotos existentes con nuevas
        $todasLasFotos = array_merge($fotosActuales, $nuevasFotos);
        $todasLasFotos = array_slice($todasLasFotos, 0, 7); // Maximo 7 fotos

        // Determinar foto principal segun el indice seleccionado
        $fotoPrincipalIndex = (int) ($request->foto_principal_index ?? 0);
        if ($fotoPrincipalIndex >= count($todasLasFotos)) {
            $fotoPrincipalIndex = 0;
        }

        // Reorganizar: foto principal primero
        if ($fotoPrincipalIndex > 0 && isset($todasLasFotos[$fotoPrincipalIndex])) {
            $fotoPrincipal = $todasLasFotos[$fotoPrincipalIndex];
            unset($todasLasFotos[$fotoPrincipalIndex]);
            array_unshift($todasLasFotos, $fotoPrincipal);
            $todasLasFotos = array_values($todasLasFotos);
        }

        // Asignar foto principal y adicionales
        $validated['foto_principal'] = $todasLasFotos[0] ?? null;
        $validated['fotos_adicionales'] = count($todasLasFotos) > 1 ? array_slice($todasLasFotos, 1) : [];

        // Limpiar campos no necesarios antes de actualizar
        unset($validated['nuevas_fotos']);
        unset($validated['fotos_orden']);
        unset($validated['fotos_eliminar']);
        unset($validated['foto_principal_index']);

        $profile->update($validated);

        return redirect()->route('user.profile.show')->with('success', 'Perfil actualizado exitosamente!');
    }

    /**
     * Mostrar el perfil del usuario autenticado
     */
    public function show()
    {
        $profile = Auth::user()->profile;

        if (!$profile) {
            return redirect()->route('user.profile.create')->with('info', 'Primero debes crear tu perfil');
        }

        return view('profiles.show', compact('profile'));
    }

    /**
     * Ver el perfil público de otro usuario
     */
    public function viewPublic($id)
    {
        $profile = Profile::with('user')->findOrFail($id);
        $user = $profile->user;

        return view('profiles.public', compact('profile', 'user'));
    }
}
