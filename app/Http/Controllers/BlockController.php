<?php

namespace App\Http\Controllers;

use App\Models\BlockedUser;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    /**
     * Bloquear un usuario
     */
    public function store(Request $request, $userId)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $currentUserId = auth()->id();

        // Verificar que no esté intentando bloquearse a sí mismo
        if ($currentUserId == $userId) {
            return back()->with('error', 'No puedes bloquearte a ti mismo.');
        }

        // Verificar si ya está bloqueado
        $existingBlock = BlockedUser::where('user_id', $currentUserId)
            ->where('blocked_user_id', $userId)
            ->first();

        if ($existingBlock) {
            return back()->with('info', 'Este usuario ya está bloqueado.');
        }

        // Crear el bloqueo
        BlockedUser::create([
            'user_id' => $currentUserId,
            'blocked_user_id' => $userId,
            'reason' => $request->reason,
        ]);

        // Eliminar match si existe
        \App\Models\UserMatch::where(function($query) use ($currentUserId, $userId) {
            $query->where('user_id_1', $currentUserId)
                  ->where('user_id_2', $userId);
        })->orWhere(function($query) use ($currentUserId, $userId) {
            $query->where('user_id_1', $userId)
                  ->where('user_id_2', $currentUserId);
        })->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Usuario bloqueado exitosamente.'
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Usuario bloqueado. No volverás a ver su perfil.');
    }

    /**
     * Desbloquear un usuario
     */
    public function destroy($userId)
    {
        $currentUserId = auth()->id();

        BlockedUser::where('user_id', $currentUserId)
            ->where('blocked_user_id', $userId)
            ->delete();

        return back()->with('success', 'Usuario desbloqueado.');
    }

    /**
     * Ver lista de usuarios bloqueados
     */
    public function index()
    {
        $blockedUsers = BlockedUser::where('user_id', auth()->id())
            ->with('blockedUser.profile')
            ->latest()
            ->get();

        return view('blocked.index', compact('blockedUsers'));
    }
}
