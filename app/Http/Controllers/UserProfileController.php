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
            'genero' => 'required|in:hombre,mujer,no-binario,genero-fluido,otro,prefiero-no-decir',
            'orientacion_sexual' => 'nullable|in:heterosexual,gay,lesbiana,bisexual,pansexual,asexual,queer,otra,prefiero-no-decir',
            'busco' => 'required|in:hombre,mujer,no-binario,cualquiera',
            'ciudad' => 'required|string|max:255',
            'biografia' => 'nullable|string|max:500',
            'intereses' => 'nullable|array',
            'intereses.*' => 'string|max:50',
            'foto_principal' => 'nullable|image|max:2048',
        ]);

        // Procesar nueva foto si existe
        if ($request->hasFile('foto_principal')) {
            // Eliminar foto anterior si no es de pravatar
            if ($profile->foto_principal && !str_contains($profile->foto_principal, 'pravatar')) {
                Storage::disk('public')->delete($profile->foto_principal);
            }

            $path = $request->file('foto_principal')->store('profiles', 'public');
            $validated['foto_principal'] = $path;
        }

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
