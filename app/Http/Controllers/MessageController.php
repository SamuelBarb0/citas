<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\UserMatch;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Mostrar lista de conversaciones (matches con mensajes)
     */
    public function index()
    {
        $currentUserId = auth()->id();

        // Obtener todos los matches del usuario con su último mensaje
        $matches = UserMatch::where(function ($query) use ($currentUserId) {
            $query->where('user_id_1', $currentUserId)
                  ->orWhere('user_id_2', $currentUserId);
        })
        ->with(['userOne.profile', 'userTwo.profile'])
        ->get();

        // Transformar para obtener el otro usuario y el último mensaje
        $conversations = $matches->map(function ($match) use ($currentUserId) {
            $otherUser = $match->user_id_1 == $currentUserId
                ? $match->userTwo
                : $match->userOne;

            $lastMessage = $match->messages()->latest()->first();
            $unreadCount = $match->messages()
                ->where('receiver_id', $currentUserId)
                ->where('leido', false)
                ->count();

            return [
                'match_id' => $match->id,
                'user' => $otherUser,
                'profile' => $otherUser->profile,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount,
                'matched_at' => $match->matched_at,
            ];
        })->sortByDesc(function ($conversation) {
            return $conversation['last_message'] ? $conversation['last_message']->created_at : $conversation['matched_at'];
        });

        return view('messages.index', compact('conversations'));
    }

    /**
     * Mostrar conversación específica con un match
     */
    public function show($matchId)
    {
        $currentUserId = auth()->id();

        // Verificar que el match existe y pertenece al usuario actual
        $match = UserMatch::where('id', $matchId)
            ->where(function ($query) use ($currentUserId) {
                $query->where('user_id_1', $currentUserId)
                      ->orWhere('user_id_2', $currentUserId);
            })
            ->with(['userOne.profile', 'userTwo.profile'])
            ->firstOrFail();

        // Obtener el otro usuario
        $otherUser = $match->user_id_1 == $currentUserId
            ? $match->userTwo
            : $match->userOne;

        // Obtener todos los mensajes de esta conversación
        $messages = $match->messages()
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Marcar mensajes como leídos
        $match->messages()
            ->where('receiver_id', $currentUserId)
            ->where('leido', false)
            ->update(['leido' => true]);

        return view('messages.show', compact('match', 'otherUser', 'messages'));
    }

    /**
     * Enviar un mensaje
     */
    public function store(Request $request, $matchId)
    {
        $request->validate([
            'mensaje' => 'required|string|max:1000',
        ]);

        $currentUser = auth()->user();
        $currentUserId = $currentUser->id;

        // Verificar que el match existe y pertenece al usuario actual
        $match = UserMatch::where('id', $matchId)
            ->where(function ($query) use ($currentUserId) {
                $query->where('user_id_1', $currentUserId)
                      ->orWhere('user_id_2', $currentUserId);
            })
            ->with(['userOne', 'userTwo'])
            ->firstOrFail();

        // Determinar quién es el receptor
        $receiverUser = $match->user_id_1 == $currentUserId
            ? $match->userTwo
            : $match->userOne;

        // VERIFICAR RESTRICCIONES DE MENSAJERÍA
        $senderSubscription = $currentUser->activeSubscription;

        // Si el usuario NO tiene suscripción, es plan Gratis por defecto
        if (!$senderSubscription) {
            // Usuario Gratis: Solo puede responder mensajes (regla 1:1)
            // Verificar quién envió el último mensaje
            $lastMessage = Message::where('match_id', $match->id)
                ->latest()
                ->first();

            // Si no hay mensajes, el usuario gratis no puede iniciar la conversación
            if (!$lastMessage) {
                return back()->with('error', 'Los usuarios gratuitos solo pueden responder mensajes. Actualiza a un plan de pago para iniciar conversaciones.');
            }

            // Si el último mensaje lo envió el usuario actual, no puede enviar otro hasta recibir respuesta
            if ($lastMessage->sender_id == $currentUserId) {
                return back()->with('error', "Has respondido el último mensaje. Espera a que {$receiverUser->profile->nombre} te responda o actualiza a un plan de pago.");
            }

            // Si el último mensaje lo envió el otro usuario, puede responder
        } else {
            // Verificar si puede enviar mensaje según su plan
            if (!$senderSubscription->canSendMessageTo($receiverUser, $match->id)) {
                $remaining = $senderSubscription->getRemainingWeeklyMessages();

                if ($remaining === 0 && $senderSubscription->plan->slug === 'basico') {
                    return back()->with('error', 'Has alcanzado tu límite de 3 mensajes semanales a usuarios gratuitos. Actualiza a Premium para mensajes ilimitados.');
                }

                // Si es usuario gratis con suscripción
                if ($senderSubscription->plan->slug === 'free') {
                    $messagesReceived = Message::where('sender_id', $receiverUser->id)
                        ->where('receiver_id', $currentUserId)
                        ->count();

                    $messagesSent = Message::where('sender_id', $currentUserId)
                        ->where('receiver_id', $receiverUser->id)
                        ->count();

                    $remaining = $messagesReceived - $messagesSent;

                    if ($remaining === 0 && $messagesReceived > 0) {
                        return back()->with('error', "Has respondido todos los mensajes recibidos. Espera a que {$receiverUser->profile->nombre} te envíe más mensajes o actualiza a un plan de pago.");
                    }
                }

                return back()->with('error', 'No puedes enviar más mensajes. Actualiza tu plan para continuar.');
            }

            // Incrementar contador de mensajes si es necesario
            $senderSubscription->incrementWeeklyMessages($receiverUser);
        }

        // Crear el mensaje
        $message = Message::create([
            'match_id' => $match->id,
            'sender_id' => $currentUserId,
            'receiver_id' => $receiverUser->id,
            'mensaje' => $request->mensaje,
            'leido' => false,
        ]);

        // Enviar notificación al receptor
        $receiverUser->notify(new \App\Notifications\NewMessageNotification($message, $currentUser));

        return redirect()->route('messages.show', $matchId)
            ->with('success', 'Mensaje enviado correctamente.');
    }

    /**
     * Eliminar un mensaje
     */
    public function destroy($messageId)
    {
        $currentUserId = auth()->id();

        $message = Message::where('id', $messageId)
            ->where('sender_id', $currentUserId)
            ->firstOrFail();

        $matchId = $message->match_id;
        $message->delete();

        return redirect()->route('messages.show', $matchId)
            ->with('success', 'Mensaje eliminado.');
    }

    /**
     * Marcar mensaje como leído
     */
    public function markAsRead($messageId)
    {
        $currentUserId = auth()->id();

        $message = Message::where('id', $messageId)
            ->where('receiver_id', $currentUserId)
            ->firstOrFail();

        $message->update(['leido' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Obtener número de mensajes no leídos
     */
    public function unreadCount()
    {
        $currentUserId = auth()->id();

        $count = Message::where('receiver_id', $currentUserId)
            ->where('leido', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Obtener mensajes nuevos desde un ID específico (para polling en tiempo real)
     */
    public function getNewMessages(Request $request, $matchId)
    {
        $currentUserId = auth()->id();
        $lastMessageId = $request->query('last_message_id', 0);

        // Verificar que el match pertenece al usuario
        $match = UserMatch::where('id', $matchId)
            ->where(function ($query) use ($currentUserId) {
                $query->where('user_id_1', $currentUserId)
                      ->orWhere('user_id_2', $currentUserId);
            })
            ->firstOrFail();

        // Obtener mensajes nuevos
        $newMessages = $match->messages()
            ->where('id', '>', $lastMessageId)
            ->with(['sender.profile'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Marcar como leídos los mensajes recibidos
        $match->messages()
            ->where('id', '>', $lastMessageId)
            ->where('receiver_id', $currentUserId)
            ->where('leido', false)
            ->update(['leido' => true]);

        // Formatear respuesta
        $formattedMessages = $newMessages->map(function ($message) use ($currentUserId) {
            $isMine = $message->sender_id === $currentUserId;
            return [
                'id' => $message->id,
                'mensaje' => $message->mensaje,
                'created_at' => $message->created_at->format('H:i'),
                'is_mine' => $isMine,
                'sender_name' => $message->sender->profile->nombre ?? $message->sender->name,
                'sender_photo' => $message->sender->profile->foto_principal ?? null,
            ];
        });

        return response()->json([
            'messages' => $formattedMessages,
            'count' => $formattedMessages->count(),
        ]);
    }
}
