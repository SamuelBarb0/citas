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
use App\Models\SeoSetting;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
     * Ver solicitudes de verificación pendientes
     */
    public function verificationQueue(Request $request)
    {
        $query = \App\Models\VerificationRequest::with(['user', 'profile'])
            ->where('estado', 'pendiente');

        // Búsqueda por nombre o email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('profile', function ($pq) use ($search) {
                    $pq->where('nombre', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        $verificationRequests = $query->latest()->paginate(20)->withQueryString();

        return view('admin.verification-queue', compact('verificationRequests'));
    }

    /**
     * Aprobar solicitud de verificación
     */
    public function verifyProfile(Request $request, $requestId)
    {
        $verificationRequest = \App\Models\VerificationRequest::findOrFail($requestId);
        $profile = $verificationRequest->profile;

        // Marcar perfil como verificado
        $profile->update([
            'verified' => true,
            'verified_at' => now(),
        ]);

        // Actualizar solicitud
        $verificationRequest->update([
            'estado' => 'aprobada',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'admin_notes' => $request->input('admin_notes'),
        ]);

        // Notificar al usuario
        $verificationRequest->user->notify(new \App\Notifications\VerificationApprovedNotification($profile));

        $this->logActivity(
            'verify_profile',
            "Aprobó la verificación del perfil de {$profile->nombre}",
            Profile::class,
            $profile->id,
            ['profile_name' => $profile->nombre]
        );

        return back()->with('success', 'Solicitud aprobada. El perfil ha sido verificado.');
    }

    /**
     * Rechazar solicitud de verificación
     */
    public function rejectVerification(Request $request, $requestId)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ], [
            'admin_notes.required' => 'Debes proporcionar un motivo para el rechazo.',
        ]);

        $verificationRequest = \App\Models\VerificationRequest::findOrFail($requestId);
        $profile = $verificationRequest->profile;

        // Actualizar solicitud
        $verificationRequest->update([
            'estado' => 'rechazada',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'admin_notes' => $request->input('admin_notes'),
        ]);

        // Notificar al usuario
        $verificationRequest->user->notify(new \App\Notifications\VerificationRejectedNotification($profile, $request->input('admin_notes')));

        $this->logActivity(
            'reject_verification',
            "Rechazó la verificación del perfil de {$profile->nombre}",
            Profile::class,
            $profile->id,
            ['profile_name' => $profile->nombre, 'reason' => $request->input('admin_notes')]
        );

        return back()->with('success', 'Solicitud rechazada. El usuario ha sido notificado.');
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

        $this->logActivity(
            'unverify_profile',
            "Removió la verificación del perfil de {$profile->nombre}",
            Profile::class,
            $profile->id,
            ['profile_name' => $profile->nombre]
        );

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

    // ============================================
    // GESTIÓN DE SEO
    // ============================================

    /**
     * Listar todas las configuraciones SEO
     */
    public function seoIndex()
    {
        $seoSettings = SeoSetting::orderBy('page_key')->paginate(20);

        // Páginas predefinidas disponibles
        $availablePages = [
            'home' => 'Página de Inicio',
            'dashboard' => 'Dashboard',
            'matches' => 'Matches',
            'messages' => 'Mensajes',
            'profile' => 'Mi Perfil',
            'plans' => 'Planes y Precios',
            'login' => 'Iniciar Sesión',
            'register' => 'Registro',
        ];

        return view('admin.seo.index', compact('seoSettings', 'availablePages'));
    }

    /**
     * Mostrar formulario para crear nueva configuración SEO
     */
    public function seoCreate()
    {
        return view('admin.seo.create');
    }

    /**
     * Guardar nueva configuración SEO
     */
    public function seoStore(Request $request)
    {
        $validated = $request->validate([
            'page_key' => 'required|string|unique:seo_settings,page_key|max:100',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'keywords' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|image|max:2048',
            'og_type' => 'nullable|string|max:50',
            'twitter_card' => 'nullable|string|max:50',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string|max:500',
            'twitter_image' => 'nullable|image|max:2048',
            'index' => 'boolean',
            'follow' => 'boolean',
        ]);

        // Subir imagen Open Graph si existe
        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $request->file('og_image')->store('seo/og-images', 'public');
        }

        // Subir imagen Twitter si existe
        if ($request->hasFile('twitter_image')) {
            $validated['twitter_image'] = $request->file('twitter_image')->store('seo/twitter-images', 'public');
        }

        SeoSetting::create($validated);

        $this->logActivity('create_seo', "Creó configuración SEO para página: {$validated['page_key']}");

        return redirect()->route('admin.seo.index')->with('success', 'Configuración SEO creada correctamente.');
    }

    /**
     * Mostrar formulario para editar configuración SEO
     */
    public function seoEdit($id)
    {
        $seoSetting = SeoSetting::findOrFail($id);
        return view('admin.seo.edit', compact('seoSetting'));
    }

    /**
     * Actualizar configuración SEO
     */
    public function seoUpdate(Request $request, $id)
    {
        $seoSetting = SeoSetting::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'keywords' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|image|max:2048',
            'og_type' => 'nullable|string|max:50',
            'twitter_card' => 'nullable|string|max:50',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string|max:500',
            'twitter_image' => 'nullable|image|max:2048',
            'index' => 'boolean',
            'follow' => 'boolean',
        ]);

        // Subir nueva imagen Open Graph si existe
        if ($request->hasFile('og_image')) {
            if ($seoSetting->og_image) {
                Storage::disk('public')->delete($seoSetting->og_image);
            }
            $validated['og_image'] = $request->file('og_image')->store('seo/og-images', 'public');
        }

        // Subir nueva imagen Twitter si existe
        if ($request->hasFile('twitter_image')) {
            if ($seoSetting->twitter_image) {
                Storage::disk('public')->delete($seoSetting->twitter_image);
            }
            $validated['twitter_image'] = $request->file('twitter_image')->store('seo/twitter-images', 'public');
        }

        $seoSetting->update($validated);

        $this->logActivity('update_seo', "Actualizó configuración SEO para página: {$seoSetting->page_key}");

        return redirect()->route('admin.seo.index')->with('success', 'Configuración SEO actualizada correctamente.');
    }

    /**
     * Eliminar configuración SEO
     */
    public function seoDestroy($id)
    {
        $seoSetting = SeoSetting::findOrFail($id);

        // Eliminar imágenes si existen
        if ($seoSetting->og_image) {
            Storage::disk('public')->delete($seoSetting->og_image);
        }
        if ($seoSetting->twitter_image) {
            Storage::disk('public')->delete($seoSetting->twitter_image);
        }

        $pageKey = $seoSetting->page_key;
        $seoSetting->delete();

        $this->logActivity('delete_seo', "Eliminó configuración SEO para página: {$pageKey}");

        return redirect()->route('admin.seo.index')->with('success', 'Configuración SEO eliminada correctamente.');
    }

    // ============================================
    // GESTIÓN DE PLANES
    // ============================================

    /**
     * Listar todos los planes
     */
    public function plansIndex()
    {
        $plans = Plan::orderBy('orden')->orderBy('precio_mensual')->get();
        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Mostrar formulario para crear nuevo plan
     */
    public function plansCreate()
    {
        return view('admin.plans.create');
    }

    /**
     * Guardar nuevo plan
     */
    public function plansStore(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'slug' => 'required|string|unique:plans,slug|max:100',
            'descripcion' => 'nullable|string|max:500',
            'precio_mensual' => 'required|numeric|min:0',
            'precio_anual' => 'nullable|numeric|min:0',
            'likes_diarios' => 'nullable|integer|min:0',
            'super_likes_mes' => 'nullable|integer|min:0',
            'mensajes_semanales_gratis' => 'nullable|integer|min:0',
            'fotos_adicionales' => 'nullable|integer|min:0',
            'boost_mensual' => 'nullable|integer|min:0',
            'ver_quien_te_gusta' => 'boolean',
            'matches_ilimitados' => 'boolean',
            'puede_iniciar_conversacion' => 'boolean',
            'mensajes_ilimitados' => 'boolean',
            'rewind' => 'boolean',
            'sin_anuncios' => 'boolean',
            'modo_incognito' => 'boolean',
            'verificacion_prioritaria' => 'boolean',
            'activo' => 'boolean',
            'orden' => 'nullable|integer|min:0',
        ]);

        $plan = Plan::create($validated);

        // Sincronizar automáticamente con PayPal si tiene precios
        if ($validated['precio_mensual'] > 0 || ($validated['precio_anual'] ?? 0) > 0) {
            try {
                $paypalService = new \App\Services\PayPalService();

                // Crear producto en PayPal
                $productName = "Citas Mallorca - {$plan->nombre}";
                $productDescription = strip_tags($plan->descripcion ?? $plan->nombre);
                $product = $paypalService->createProduct($productName, $productDescription);
                $productId = $product['id'];

                // Crear plan mensual si tiene precio
                if ($plan->precio_mensual > 0) {
                    $billingPlan = $paypalService->createBillingPlan(
                        $productId,
                        "{$plan->nombre} - Mensual",
                        "Suscripción mensual a {$plan->nombre}",
                        $plan->precio_mensual,
                        'MONTH'
                    );
                    $plan->paypal_plan_id_mensual = $billingPlan['id'];
                }

                // Crear plan anual si tiene precio
                if ($plan->precio_anual > 0) {
                    $billingPlan = $paypalService->createBillingPlan(
                        $productId,
                        "{$plan->nombre} - Anual",
                        "Suscripción anual a {$plan->nombre}",
                        $plan->precio_anual,
                        'YEAR'
                    );
                    $plan->paypal_plan_id_anual = $billingPlan['id'];
                }

                $plan->save();

            } catch (\Exception $e) {
                \Log::error('Error al crear plan en PayPal', [
                    'plan_id' => $plan->id,
                    'error' => $e->getMessage()
                ]);
                // No fallar si PayPal tiene problemas, solo registrar
            }
        }

        $this->logActivity('create_plan', "Creó el plan: {$validated['nombre']}");

        return redirect()->route('admin.plans.index')->with('success', 'Plan creado correctamente y sincronizado con PayPal.');
    }

    /**
     * Mostrar formulario para editar plan
     */
    public function plansEdit($id)
    {
        $plan = Plan::findOrFail($id);
        return view('admin.plans.edit', compact('plan'));
    }

    /**
     * Actualizar plan
     */
    public function plansUpdate(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:plans,slug,' . $id,
            'descripcion' => 'nullable|string|max:500',
            'precio_mensual' => 'required|numeric|min:0',
            'precio_anual' => 'nullable|numeric|min:0',
            'likes_diarios' => 'nullable|integer|min:0',
            'super_likes_mes' => 'nullable|integer|min:0',
            'mensajes_semanales_gratis' => 'nullable|integer|min:0',
            'fotos_adicionales' => 'nullable|integer|min:0',
            'boost_mensual' => 'nullable|integer|min:0',
            'ver_quien_te_gusta' => 'boolean',
            'matches_ilimitados' => 'boolean',
            'puede_iniciar_conversacion' => 'boolean',
            'mensajes_ilimitados' => 'boolean',
            'rewind' => 'boolean',
            'sin_anuncios' => 'boolean',
            'modo_incognito' => 'boolean',
            'verificacion_prioritaria' => 'boolean',
            'activo' => 'boolean',
            'orden' => 'nullable|integer|min:0',
        ]);

        // Detectar cambios de precio ANTES de actualizar
        $precioMensualCambio = $plan->precio_mensual != $validated['precio_mensual'];
        $precioAnualCambio = $plan->precio_anual != ($validated['precio_anual'] ?? 0);
        $tienePlanPayPal = $plan->paypal_plan_id_mensual || $plan->paypal_plan_id_anual;

        $plan->update($validated);

        // Si cambió el precio y ya tiene plan en PayPal, advertir que se debe crear un nuevo plan
        if ($tienePlanPayPal && ($precioMensualCambio || $precioAnualCambio)) {
            \Log::warning('Precio de plan modificado - PayPal no permite cambiar precios de planes activos', [
                'plan_id' => $plan->id,
                'precio_mensual_anterior' => $plan->getOriginal('precio_mensual'),
                'precio_mensual_nuevo' => $validated['precio_mensual'],
                'precio_anual_anterior' => $plan->getOriginal('precio_anual'),
                'precio_anual_nuevo' => $validated['precio_anual'] ?? 0
            ]);

            return redirect()->route('admin.plans.index')
                ->with('warning', 'Plan actualizado. IMPORTANTE: PayPal no permite cambiar precios de planes existentes. Los usuarios nuevos verán el nuevo precio, pero las suscripciones activas mantendrán el precio anterior. Si necesitas cambiar precios para todos, considera crear un nuevo plan.');
        }

        // Sincronizar con PayPal si no tiene IDs aún y tiene precios
        if (($validated['precio_mensual'] > 0 || ($validated['precio_anual'] ?? 0) > 0)) {
            $needsSync = false;

            // Verificar si necesita crear plan mensual en PayPal
            if ($validated['precio_mensual'] > 0 && !$plan->paypal_plan_id_mensual) {
                $needsSync = true;
            }

            // Verificar si necesita crear plan anual en PayPal
            if (($validated['precio_anual'] ?? 0) > 0 && !$plan->paypal_plan_id_anual) {
                $needsSync = true;
            }

            if ($needsSync) {
                try {
                    $paypalService = new \App\Services\PayPalService();

                    // Crear producto en PayPal si no existe
                    $productName = "Citas Mallorca - {$plan->nombre}";
                    $productDescription = strip_tags($plan->descripcion ?? $plan->nombre);
                    $product = $paypalService->createProduct($productName, $productDescription);
                    $productId = $product['id'];

                    // Crear plan mensual si no existe
                    if ($plan->precio_mensual > 0 && !$plan->paypal_plan_id_mensual) {
                        $billingPlan = $paypalService->createBillingPlan(
                            $productId,
                            "{$plan->nombre} - Mensual",
                            "Suscripción mensual a {$plan->nombre}",
                            $plan->precio_mensual,
                            'MONTH'
                        );
                        $plan->paypal_plan_id_mensual = $billingPlan['id'];
                    }

                    // Crear plan anual si no existe
                    if ($plan->precio_anual > 0 && !$plan->paypal_plan_id_anual) {
                        $billingPlan = $paypalService->createBillingPlan(
                            $productId,
                            "{$plan->nombre} - Anual",
                            "Suscripción anual a {$plan->nombre}",
                            $plan->precio_anual,
                            'YEAR'
                        );
                        $plan->paypal_plan_id_anual = $billingPlan['id'];
                    }

                    $plan->save();

                } catch (\Exception $e) {
                    \Log::error('Error al sincronizar plan con PayPal', [
                        'plan_id' => $plan->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        $this->logActivity('update_plan', "Actualizó el plan: {$plan->nombre}");

        return redirect()->route('admin.plans.index')->with('success', 'Plan actualizado correctamente.');
    }

    /**
     * Eliminar plan
     */
    public function plansDestroy($id)
    {
        $plan = Plan::findOrFail($id);

        // Verificar si hay usuarios suscritos a este plan
        $activeSubscriptions = $plan->subscriptions()->where('estado', 'activo')->count();

        if ($activeSubscriptions > 0) {
            return redirect()->route('admin.plans.index')
                ->with('error', "No se puede eliminar el plan porque hay {$activeSubscriptions} suscripciones activas.");
        }

        $planNombre = $plan->nombre;

        // Desactivar planes en PayPal si existen
        if ($plan->paypal_plan_id_mensual || $plan->paypal_plan_id_anual) {
            try {
                $paypalService = new \App\Services\PayPalService();

                // PayPal no permite eliminar planes, solo desactivarlos
                // Esto se hace automáticamente cuando no hay más suscripciones activas
                \Log::info('Plan eliminado de la base de datos, los planes de PayPal quedarán inactivos', [
                    'plan_id' => $plan->id,
                    'paypal_plan_id_mensual' => $plan->paypal_plan_id_mensual,
                    'paypal_plan_id_anual' => $plan->paypal_plan_id_anual
                ]);
            } catch (\Exception $e) {
                \Log::error('Error al procesar eliminación de plan en PayPal', [
                    'plan_id' => $plan->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $plan->delete();

        $this->logActivity('delete_plan', "Eliminó el plan: {$planNombre}");

        return redirect()->route('admin.plans.index')->with('success', 'Plan eliminado correctamente. Los planes de PayPal permanecen para suscripciones existentes.');
    }
}
