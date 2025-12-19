<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Profile;
use App\Models\User;
use App\Models\UserMatch;
use App\Models\Like;
use App\Models\AdminLog;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Dashboard principal del admin
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_profiles' => Profile::where('activo', true)->count(),
            'total_matches' => UserMatch::count(),
            'total_likes' => Like::count(),
            'pending_reports' => Report::where('status', 'pendiente')->count(),
            'verified_profiles' => Profile::where('verified', true)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Ver todos los reportes
     */
    public function reports(Request $request)
    {
        $query = Report::with(['reporter.profile', 'reportedUser.profile']);

        // Filtrar por estado si se proporciona
        if ($request->has('status') && in_array($request->status, ['pendiente', 'revisado', 'accion_tomada', 'descartado'])) {
            $query->where('status', $request->status);
        }

        $reports = $query->orderBy('status')
            ->latest()
            ->paginate(20);

        return view('admin.reports', compact('reports'));
    }

    /**
     * Actualizar estado de un reporte
     */
    public function updateReport(Request $request, $reportId)
    {
        $request->validate([
            'status' => 'required|in:pendiente,revisado,accion_tomada,descartado',
        ]);

        $report = Report::findOrFail($reportId);
        $report->update(['status' => $request->status]);

        return back()->with('success', 'Estado del reporte actualizado.');
    }

    /**
     * Ver perfiles pendientes de verificación
     */
    public function verificationQueue(Request $request)
    {
        $query = Profile::where('activo', true)
            ->where('verified', false)
            ->with('user');

        // Búsqueda por nombre, email o ciudad
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('ciudad', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $profiles = $query->latest()->paginate(20)->withQueryString();

        return view('admin.verification-queue', compact('profiles'));
    }

    /**
     * Verificar un perfil
     */
    public function verifyProfile($profileId)
    {
        $profile = Profile::findOrFail($profileId);
        $profile->update([
            'verified' => true,
            'verified_at' => now(),
        ]);

        $this->logActivity(
            'verify_profile',
            "Verificó el perfil de {$profile->nombre}",
            Profile::class,
            $profile->id,
            ['profile_name' => $profile->nombre]
        );

        return back()->with('success', 'Perfil verificado exitosamente.');
    }

    /**
     * Quitar verificación de un perfil
     */
    public function unverifyProfile($profileId)
    {
        $profile = Profile::findOrFail($profileId);
        $profile->update([
            'verified' => false,
            'verified_at' => null,
        ]);

        return back()->with('success', 'Verificación removida.');
    }

    /**
     * Ver todos los usuarios
     */
    public function users(Request $request)
    {
        $query = User::with('profile');

        // Búsqueda por nombre o email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('profile', function ($pq) use ($search) {
                      $pq->where('nombre', 'like', "%{$search}%")
                         ->orWhere('ciudad', 'like', "%{$search}%");
                  });
            });
        }

        // Filtrar según parámetro
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'active':
                    $query->whereHas('profile', function ($q) {
                        $q->where('activo', true);
                    });
                    break;
                case 'suspended':
                    $query->whereHas('profile', function ($q) {
                        $q->where('activo', false);
                    });
                    break;
                case 'verified':
                    $query->whereHas('profile', function ($q) {
                        $q->where('verified', true);
                    });
                    break;
            }
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users', compact('users'));
    }

    /**
     * Suspender un usuario
     */
    public function suspendUser($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->profile) {
            $user->profile->update(['activo' => false]);

            $this->logActivity(
                'suspend_user',
                "Suspendió al usuario {$user->name}",
                User::class,
                $user->id,
                ['user_name' => $user->name, 'user_email' => $user->email]
            );
        }

        return back()->with('success', 'Usuario suspendido.');
    }

    /**
     * Reactivar un usuario
     */
    public function activateUser($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->profile) {
            $user->profile->update(['activo' => true]);
        }

        return back()->with('success', 'Usuario reactivado.');
    }

    /**
     * Ver logs de actividad
     */
    public function logs(Request $request)
    {
        $query = AdminLog::with('admin')->latest();

        // Filtrar por acción
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        // Filtrar por admin
        if ($request->has('admin_id') && $request->admin_id) {
            $query->where('admin_id', $request->admin_id);
        }

        $logs = $query->paginate(50);
        $admins = User::where('is_admin', true)->get();

        return view('admin.logs', compact('logs', 'admins'));
    }

    /**
     * Estadísticas avanzadas
     */
    public function statistics()
    {
        // Usuarios registrados por mes (últimos 6 meses)
        $usersByMonth = User::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Matches por mes
        $matchesByMonth = UserMatch::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Top usuarios con más matches
        $topMatches = User::withCount('matches')
            ->with('profile')
            ->orderBy('matches_count', 'desc')
            ->limit(10)
            ->get();

        // Usuarios más activos (más likes dados)
        $mostActive = User::withCount('likes')
            ->with('profile')
            ->orderBy('likes_count', 'desc')
            ->limit(10)
            ->get();

        // Estadísticas generales
        $stats = [
            'total_users' => User::count(),
            'users_today' => User::whereDate('created_at', today())->count(),
            'users_week' => User::where('created_at', '>=', now()->subWeek())->count(),
            'users_month' => User::where('created_at', '>=', now()->subMonth())->count(),
            'total_profiles' => Profile::count(),
            'active_profiles' => Profile::where('activo', true)->count(),
            'verified_profiles' => Profile::where('verified', true)->count(),
            'total_matches' => UserMatch::count(),
            'matches_today' => UserMatch::whereDate('created_at', today())->count(),
            'matches_week' => UserMatch::where('created_at', '>=', now()->subWeek())->count(),
            'total_messages' => Message::count(),
            'messages_today' => Message::whereDate('created_at', today())->count(),
            'total_likes' => Like::count(),
            'super_likes' => Like::where('is_super_like', true)->count(),
            'pending_reports' => Report::where('status', 'pendiente')->count(),
        ];

        return view('admin.statistics', compact(
            'usersByMonth',
            'matchesByMonth',
            'topMatches',
            'mostActive',
            'stats'
        ));
    }

    /**
     * Helper para registrar actividad del admin
     */
    private function logActivity($action, $description, $targetType = null, $targetId = null, $metadata = [])
    {
        AdminLog::create([
            'admin_id' => Auth::id(),
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'description' => $description,
            'metadata' => $metadata,
        ]);
    }
}
