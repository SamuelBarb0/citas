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
            'foto_principal' => 'nullable|image|max:2048',
            'fotos_adicionales.*' => 'nullable|image|max:2048',
            'fotos_adicionales_existentes' => 'nullable|array',
            'fotos_eliminar' => 'nullable|string',
        ]);

        // === FOTO PRINCIPAL ===
        if ($request->hasFile('foto_principal')) {
            // Eliminar foto principal anterior si no es de pravatar
            if ($profile->foto_principal && !str_contains($profile->foto_principal, 'pravatar') && !str_starts_with($profile->foto_principal, 'http')) {
                Storage::disk('public')->delete($profile->foto_principal);
            }
            $validated['foto_principal'] = $request->file('foto_principal')->store('profiles', 'public');
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
                if (!str_contains($fotoEliminar, 'pravatar') && !str_starts_with($fotoEliminar, 'http')) {
                    Storage::disk('public')->delete($fotoEliminar);
                }
                // Quitar de las existentes
                $fotosAdicionalesExistentes = array_values(array_filter($fotosAdicionalesExistentes, fn($f) => $f !== $fotoEliminar));
            }
        }

        // Procesar nuevas fotos adicionales
        $nuevasFotosAdicionales = [];
        if ($request->hasFile('fotos_adicionales')) {
            foreach ($request->file('fotos_adicionales') as $foto) {
                $path = $foto->store('profiles', 'public');
                $nuevasFotosAdicionales[] = $path;
            }
        }

        // Combinar fotos adicionales existentes con nuevas (maximo 6)
        $todasFotosAdicionales = array_merge($fotosAdicionalesExistentes, $nuevasFotosAdicionales);
        $todasFotosAdicionales = array_slice($todasFotosAdicionales, 0, 6);

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
