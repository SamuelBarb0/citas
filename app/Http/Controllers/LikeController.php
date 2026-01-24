<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\UserMatch;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Dar like a un usuario
     */
    public function store(Request $request, $userId)
    {
        $request->validate([
            'liked_user_id' => 'required|exists:users,id',
        ]);

        $currentUserId = auth()->id();
        $likedUserId = $request->liked_user_id;

        // Verificar que no se esté intentando dar like a sí mismo
        if ($currentUserId == $likedUserId) {
            return back()->with('error', 'No puedes darte like a ti mismo.');
        }

        // Verificar si ya existe el like
        $existingLike = Like::where('user_id', $currentUserId)
            ->where('liked_user_id', $likedUserId)
            ->first();

        if ($existingLike) {
            return back()->with('info', 'Ya le has dado like a este usuario.');
        }

        // Crear el like
        Like::create([
            'user_id' => $currentUserId,
            'liked_user_id' => $likedUserId,
        ]);

        // Verificar si el otro usuario también nos ha dado like (MATCH!)
        $mutualLike = Like::where('user_id', $likedUserId)
            ->where('liked_user_id', $currentUserId)
            ->first();

        if ($mutualLike) {
            // ¡Es un match! Crear el match automáticamente
            $this->createMatch($currentUserId, $likedUserId);

            // Obtener los datos del perfil del usuario con quien hicimos match
            $matchedUser = \App\Models\User::with('profile')->find($likedUserId);
            $currentUser = \App\Models\User::with('profile')->find($currentUserId);

            // Enviar notificaciones a ambos usuarios
            $matchedUser->notify(new \App\Notifications\NewMatchNotification($currentUser));
            $currentUser->notify(new \App\Notifications\NewMatchNotification($matchedUser));

            $matchData = [
                'name' => $matchedUser->profile->nombre ?? $matchedUser->name,
                'photo' => $matchedUser->profile->foto_principal ?? 'https://ui-avatars.com/api/?name=' . urlencode($matchedUser->name) . '&size=400&background=A67C52&color=fff'
            ];

            // Si es una petición AJAX, devolver JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'match' => true,
                    'message' => '¡Es un match! 💕',
                    'matched_user' => $matchData
                ]);
            }

            // Guardar el match en la sesión para mostrarlo cuando vuelva al dashboard
            session()->flash('new_match', [
                'name' => $matchedUser->profile->nombre ?? $matchedUser->name,
                'photo' => $matchedUser->profile->foto_principal ?? 'https://ui-avatars.com/api/?name=' . urlencode($matchedUser->name) . '&size=400&background=A67C52&color=fff'
            ]);

            return redirect()->route('matches')->with('success', '¡Es un match! 💕 Ahora puedes enviar mensajes.');
        }

        // Si es una petición AJAX, devolver JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'match' => false,
                'message' => '¡Like enviado! ❤️'
            ]);
        }

        return back()->with('success', '¡Like enviado! ❤️');
    }

    /**
     * Quitar like a un usuario
     */
    public function destroy($likedUserId)
    {
        $currentUserId = auth()->id();

        $like = Like::where('user_id', $currentUserId)
            ->where('liked_user_id', $likedUserId)
            ->first();

        if (!$like) {
            return back()->with('error', 'No has dado like a este usuario.');
        }

        $like->delete();

        return back()->with('success', 'Like eliminado.');
    }

    /**
     * Crear un match entre dos usuarios
     */
    private function createMatch($userId1, $userId2)
    {
        // Asegurar que user_id_1 sea siempre el menor para evitar duplicados
        $userIdMin = min($userId1, $userId2);
        $userIdMax = max($userId1, $userId2);

        // Verificar si ya existe el match
        $existingMatch = UserMatch::where(function ($query) use ($userIdMin, $userIdMax) {
            $query->where('user_id_1', $userIdMin)
                  ->where('user_id_2', $userIdMax);
        })->orWhere(function ($query) use ($userIdMin, $userIdMax) {
            $query->where('user_id_1', $userIdMax)
                  ->where('user_id_2', $userIdMin);
        })->first();

        if (!$existingMatch) {
            UserMatch::create([
                'user_id_1' => $userIdMin,
                'user_id_2' => $userIdMax,
                'matched_at' => now(),
            ]);
        }
    }

    /**
     * Ver lista de usuarios a los que he dado like
     */
    public function myLikes()
    {
        $currentUserId = auth()->id();

        $likes = Like::where('user_id', $currentUserId)
            ->with(['likedUser.profile'])
            ->latest()
            ->paginate(12);

        return view('likes.index', compact('likes'));
    }

    /**
     * Ver lista de usuarios que me han dado like
     */
    public function whoLikesMe()
    {
        $user = auth()->user();
        $canSeeWhoLikedMe = $user->canSeeWhoLikedMe();

        // Contar cuántos likes tiene (siempre mostrar el número)
        $likesCount = Like::where('liked_user_id', $user->id)->count();

        // Si no tiene permiso, mostrar vista de upgrade
        if (!$canSeeWhoLikedMe) {
            // Obtener planes que tienen la función de ver quién te ha dado like
            $planesConLikes = \App\Models\Plan::where('ver_quien_te_gusta', true)
                ->where('activo', true)
                ->orderBy('orden')
                ->get();

            return view('likes.who-likes-me-upgrade', compact('likesCount', 'planesConLikes'));
        }

        $likes = Like::where('liked_user_id', $user->id)
            ->with(['user.profile'])
            ->latest()
            ->paginate(12);

        return view('likes.who-likes-me', compact('likes', 'likesCount'));
    }

}
