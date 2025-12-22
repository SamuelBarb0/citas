<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Mostrar todas las notificaciones del usuario
     */
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Marcar notificación como leída
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        // Redirigir según el tipo de notificación
        $data = $notification->data;

        if ($data['type'] === 'new_match') {
            return redirect()->route('matches');
        } elseif ($data['type'] === 'new_message') {
            return redirect()->route('messages.show', $data['match_id']);
        }

        return redirect()->route('notifications.index');
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function markAllAsRead()
    {
        auth()->user()
            ->unreadNotifications
            ->markAsRead();

        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }

    /**
     * Obtener contador de notificaciones no leídas (para AJAX)
     */
    public function unreadCount()
    {
        $count = auth()->user()->unreadNotifications->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Eliminar notificación
     */
    public function destroy($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->delete();

        return back()->with('success', 'Notificación eliminada.');
    }
}
