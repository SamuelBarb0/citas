<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerificationRequest;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

class VerificationRequestController extends Controller
{
    /**
     * Mostrar formulario de solicitud de verificación
     */
    public function create()
    {
        $user = auth()->user();
        $profile = $user->profile;

        if (!$profile) {
            return redirect()->route('user.profile.create')
                ->with('error', 'Debes completar tu perfil primero antes de solicitar verificación.');
        }

        // Verificar si ya está verificado
        if ($profile->verified) {
            return redirect()->route('user.profile.show')
                ->with('info', '¡Tu perfil ya está verificado!');
        }

        // Verificar si ya tiene una solicitud pendiente
        $pendingRequest = VerificationRequest::where('user_id', $user->id)
            ->where('estado', 'pendiente')
            ->first();

        if ($pendingRequest) {
            // Mostrar vista de solicitud pendiente
            return view('verification.pending', compact('profile', 'pendingRequest'));
        }

        return view('verification.create', compact('profile'));
    }

    /**
     * Guardar solicitud de verificación
     */
    public function store(Request $request)
    {
        $request->validate([
            'verification_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ], [
            'verification_photo.required' => 'Debes subir una foto de verificación.',
            'verification_photo.image' => 'El archivo debe ser una imagen.',
            'verification_photo.mimes' => 'La imagen debe ser JPG, JPEG o PNG.',
            'verification_photo.max' => 'La imagen no debe superar los 5MB.',
        ]);

        $user = auth()->user();
        $profile = $user->profile;

        if (!$profile) {
            return back()->with('error', 'Debes completar tu perfil primero.');
        }

        // Verificar si ya tiene una solicitud pendiente
        $existingRequest = VerificationRequest::where('user_id', $user->id)
            ->where('estado', 'pendiente')
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'Ya tienes una solicitud pendiente.');
        }

        // Guardar la foto
        $photoPath = $request->file('verification_photo')->store('verification_photos', 'public');

        // Crear solicitud
        VerificationRequest::create([
            'user_id' => $user->id,
            'profile_id' => $profile->id,
            'verification_photo' => $photoPath,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('user.profile.show')
            ->with('success', '¡Solicitud de verificación enviada! Nuestro equipo la revisará pronto.');
    }

    /**
     * Ver estado de la solicitud
     */
    public function status()
    {
        $user = auth()->user();

        $request = VerificationRequest::where('user_id', $user->id)
            ->with(['reviewer'])
            ->latest()
            ->first();

        return view('verification.status', compact('request'));
    }
}
