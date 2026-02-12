<?php

namespace App\Http\Controllers;

use App\Models\UserMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Verificar si hay nuevos matches para el usuario actual
     * Se usa para polling desde el dashboard
     */
    public function checkNewMatches(Request $request)
    {
        $currentUserId = auth()->id();
        $lastCheckTime = $request->input('last_check', now()->subMinutes(1)->toDateTimeString());

        // Buscar matches creados después del último check
        $newMatches = UserMatch::where(function ($query) use ($currentUserId) {
            $query->where('user_id_1', $currentUserId)
                  ->orWhere('user_id_2', $currentUserId);
        })
        ->where('matched_at', '>', $lastCheckTime)
        ->with(['userOne.profile', 'userTwo.profile'])
        ->orderBy('matched_at', 'desc')
        ->get();

        if ($newMatches->isEmpty()) {
            return response()->json([
                'has_new_matches' => false,
                'matches' => []
            ]);
        }

        // Transformar los matches para obtener la info del otro usuario
        $matchesData = $newMatches->map(function ($match) use ($currentUserId) {
            $otherUser = $match->user_id_1 == $currentUserId
                ? $match->userTwo
                : $match->userOne;

            return [
                'match_id' => $match->id,
                'user_id' => $otherUser->id,
                'name' => $otherUser->profile->nombre ?? $otherUser->name,
                'photo' => $otherUser->profile->foto_principal
                    ? (str_starts_with($otherUser->profile->foto_principal, 'http')
                        ? $otherUser->profile->foto_principal
                        : Storage::url($otherUser->profile->foto_principal))
                    : 'https://ui-avatars.com/api/?name=' . urlencode($otherUser->name) . '&size=400&background=A67C52&color=fff',
                'matched_at' => $match->matched_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'has_new_matches' => true,
            'matches' => $matchesData
        ]);
    }

    /**
     * Obtener el número de matches nuevos (últimas 24 horas)
     * Para mostrar en el badge del navbar
     */
    public function newMatchesCount()
    {
        $currentUserId = auth()->id();

        // Contar matches de las últimas 24 horas
        $count = UserMatch::where(function ($query) use ($currentUserId) {
            $query->where('user_id_1', $currentUserId)
                  ->orWhere('user_id_2', $currentUserId);
        })
        ->where('matched_at', '>', now()->subDay())
        ->count();

        return response()->json(['count' => $count]);
    }
}
