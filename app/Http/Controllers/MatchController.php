<?php

namespace App\Http\Controllers;

use App\Models\UserMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatchController extends Controller
{
    /**
     * Mostrar todos los matches del usuario autenticado
     */
    public function index()
    {
        $currentUserId = auth()->id();

        // Obtener todos los matches donde el usuario actual está involucrado
        $matches = UserMatch::where(function ($query) use ($currentUserId) {
            $query->where('user_id_1', $currentUserId)
                  ->orWhere('user_id_2', $currentUserId);
        })
        ->with(['userOne.profile', 'userTwo.profile'])
        ->orderBy('matched_at', 'desc')
        ->get();

        // Transformar los matches para tener siempre el "otro usuario"
        $matchesData = $matches->map(function ($match) use ($currentUserId) {
            $otherUser = $match->user_id_1 == $currentUserId
                ? $match->userTwo
                : $match->userOne;

            return [
                'match_id' => $match->id,
                'user' => $otherUser,
                'profile' => $otherUser->profile,
                'matched_at' => $match->matched_at,
                'last_message' => $match->messages()->latest()->first(),
            ];
        });

        return view('matches.index', compact('matchesData'));
    }

    /**
     * Eliminar un match (unmatch)
     */
    public function destroy($matchId)
    {
        $currentUserId = auth()->id();

        $match = UserMatch::where('id', $matchId)
            ->where(function ($query) use ($currentUserId) {
                $query->where('user_id_1', $currentUserId)
                      ->orWhere('user_id_2', $currentUserId);
            })
            ->first();

        if (!$match) {
            return back()->with('error', 'Match no encontrado.');
        }

        // Eliminar también los mensajes asociados
        $match->messages()->delete();

        // Eliminar el match
        $match->delete();

        return back()->with('success', 'Match eliminado correctamente.');
    }

    /**
     * Verificar si dos usuarios tienen match
     */
    public function checkMatch($userId1, $userId2)
    {
        $userIdMin = min($userId1, $userId2);
        $userIdMax = max($userId1, $userId2);

        $match = UserMatch::where(function ($query) use ($userIdMin, $userIdMax) {
            $query->where('user_id_1', $userIdMin)
                  ->where('user_id_2', $userIdMax);
        })->orWhere(function ($query) use ($userIdMin, $userIdMax) {
            $query->where('user_id_1', $userIdMax)
                  ->where('user_id_2', $userIdMin);
        })->first();

        return $match ? true : false;
    }
}
