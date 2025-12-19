<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Reportar un usuario
     */
    public function store(Request $request, $userId)
    {
        $request->validate([
            'reason' => 'required|in:inapropiado,spam,acoso,suplantacion,menor_edad,otro',
            'description' => 'nullable|string|max:1000',
        ]);

        $currentUserId = auth()->id();

        // Verificar que no esté intentando reportarse a sí mismo
        if ($currentUserId == $userId) {
            return back()->with('error', 'No puedes reportarte a ti mismo.');
        }

        // Crear el reporte
        Report::create([
            'reporter_id' => $currentUserId,
            'reported_user_id' => $userId,
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'pendiente',
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Reporte enviado. Nuestro equipo lo revisará pronto.'
            ]);
        }

        return back()->with('success', 'Gracias por tu reporte. Nuestro equipo lo revisará pronto.');
    }
}
