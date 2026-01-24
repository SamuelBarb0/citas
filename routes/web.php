<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\LegalController;
use Illuminate\Support\Facades\Route;
use App\Models\Profile;

// Página de inicio pública
Route::get('/', function () {
    $perfiles = Profile::with('user')
        ->where('activo', true)
        ->inRandomOrder()
        ->limit(20)
        ->get();

    return view('welcome', compact('perfiles'));
});

// Ruta pública de Planes (no requiere autenticación)
Route::get('/planes', [\App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscriptions.index');

// Webhook de PayPal (público - no requiere autenticación)
Route::post('/webhooks/paypal', [\App\Http\Controllers\PayPalWebhookController::class, 'handle'])->name('webhooks.paypal');

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth'])->group(function () {
    // Rutas de perfil y verificación (NO requieren estar verificado)
    Route::get('/mi-perfil/crear', [UserProfileController::class, 'create'])->name('user.profile.create');
    Route::post('/mi-perfil', [UserProfileController::class, 'store'])->name('user.profile.store');

    // Verificación de Perfil (NO requiere estar verificado)
    Route::get('/verification/request', [\App\Http\Controllers\VerificationRequestController::class, 'create'])->name('verification.create');
    Route::post('/verification/request', [\App\Http\Controllers\VerificationRequestController::class, 'store'])->name('verification.store');
    Route::get('/verification/status', [\App\Http\Controllers\VerificationRequestController::class, 'status'])->name('verification.status');
});

// Rutas que REQUIEREN tener perfil (no requieren verificación)
Route::middleware(['auth', 'has.profile'])->group(function () {
    // Dashboard - Descubrir perfiles
    Route::get('/dashboard', function () {
        $currentUserId = auth()->id();

        $query = Profile::with('user')
            ->where('activo', true)
            ->where('user_id', '!=', $currentUserId)
            ->whereDoesntHave('likedBy', function ($query) use ($currentUserId) {
                $query->where('user_id', $currentUserId);
            })
            // Filtrar usuarios bloqueados (los que yo bloqueé)
            ->whereNotIn('user_id', function($query) use ($currentUserId) {
                $query->select('blocked_user_id')
                    ->from('blocked_users')
                    ->where('user_id', $currentUserId);
            })
            // Filtrar usuarios que me bloquearon
            ->whereNotIn('user_id', function($query) use ($currentUserId) {
                $query->select('user_id')
                    ->from('blocked_users')
                    ->where('blocked_user_id', $currentUserId);
            });

        // Obtener el perfil del usuario actual para filtros inteligentes
        $myProfile = auth()->user()->profile;
        $searchExpanded = false; // Indica si ampliamos la búsqueda

        // Verificar si el usuario está aplicando filtros manuales
        $hasManualFilters = request()->filled('busco') ||
                           request()->filled('ciudad') ||
                           request()->filled('orientacion_sexual') ||
                           request()->filled('intereses') ||
                           (request()->has('edad_min') && request('edad_min') != 18) ||
                           (request()->has('edad_max') && request('edad_max') != 99);

        // Aplicar filtros manuales si existen
        if (request()->has('edad_min') && request('edad_min') != 18) {
            $query->where('edad', '>=', request('edad_min'));
        }

        if (request()->has('edad_max') && request('edad_max') != 99) {
            $query->where('edad', '<=', request('edad_max'));
        }

        if (request()->filled('ciudad')) {
            $query->where('ciudad', 'LIKE', '%' . request('ciudad') . '%');
        }

        // Filtro manual de género
        if (request()->filled('busco')) {
            $query->where('genero', request('busco'));
        }

        if (request()->filled('orientacion_sexual')) {
            $query->where('orientacion_sexual', request('orientacion_sexual'));
        }

        if (request()->filled('intereses')) {
            $interesesBuscados = array_map('trim', explode(',', request('intereses')));
            $query->where(function($q) use ($interesesBuscados) {
                foreach ($interesesBuscados as $interes) {
                    $q->orWhereJsonContains('intereses', $interes);
                }
            });
        }

        // Si NO hay filtros manuales, aplicar filtro de compatibilidad automático
        if (!$hasManualFilters && $myProfile) {
            // Clonar la query para probar con filtros de compatibilidad
            $compatibleQuery = clone $query;

            // Filtrar por lo que busco
            if ($myProfile->busco && $myProfile->busco !== 'cualquiera' && $myProfile->busco !== '') {
                $compatibleQuery->where('genero', $myProfile->busco);
            }

            // Filtrar personas que me busquen a mí
            if ($myProfile->genero) {
                $compatibleQuery->where(function($q) use ($myProfile) {
                    $q->where('busco', $myProfile->genero)
                      ->orWhere('busco', 'cualquiera')
                      ->orWhereNull('busco')
                      ->orWhere('busco', '');
                });
            }

            // Verificar si hay resultados compatibles
            $compatibleCount = $compatibleQuery->count();

            if ($compatibleCount > 0) {
                // Usar la query con filtros de compatibilidad
                $perfiles = $compatibleQuery->inRandomOrder()->limit(12)->get();
            } else {
                // No hay compatibles, mostrar todos (fallback)
                $searchExpanded = true;
                $perfiles = $query->inRandomOrder()->limit(12)->get();
            }
        } else {
            // Hay filtros manuales, usarlos directamente
            $perfiles = $query->inRandomOrder()->limit(12)->get();
        }

        // Verificar si hay un nuevo match pendiente de mostrar
        $newMatch = session('new_match');
        if ($newMatch) {
            session()->forget('new_match');
        }

        return view('dashboard', compact('perfiles', 'newMatch', 'searchExpanded'));
    })->name('dashboard');

    // Gestión de Perfil de Usuario (Dating Profile)
    Route::get('/mi-perfil', [UserProfileController::class, 'show'])->name('user.profile.show');
    Route::get('/mi-perfil/editar', [UserProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('/mi-perfil', [UserProfileController::class, 'update'])->name('user.profile.update');

    // Ver perfil público de otros usuarios
    Route::get('/perfil/{id}', [UserProfileController::class, 'viewPublic'])->name('profile.public');

    // Configuración de cuenta (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Likes
    Route::post('/like/{userId}', [LikeController::class, 'store'])->name('like.store');
    Route::delete('/like/{likedUserId}', [LikeController::class, 'destroy'])->name('like.destroy');
    Route::get('/mis-likes', [LikeController::class, 'myLikes'])->name('likes.my');
    Route::get('/quien-me-gusta', [LikeController::class, 'whoLikesMe'])->name('likes.who');

    // Matches
    Route::get('/matches', [MatchController::class, 'index'])->name('matches');
    Route::delete('/matches/{matchId}', [MatchController::class, 'destroy'])->name('matches.destroy');
    Route::get('/matches/check-new', [MatchController::class, 'checkNewMatches'])->name('matches.check-new');

    // Mensajes
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::get('/messages/{matchId}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{matchId}', [MessageController::class, 'store'])->name('messages.store');
    Route::delete('/messages/{messageId}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::post('/messages/{messageId}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
    Route::get('/messages/unread/count', [MessageController::class, 'unreadCount'])->name('messages.unread');
    Route::get('/messages/{matchId}/new', [MessageController::class, 'getNewMessages'])->name('messages.new');

    // Bloquear usuarios
    Route::post('/block/{userId}', [BlockController::class, 'store'])->name('block.store');
    Route::delete('/block/{userId}', [BlockController::class, 'destroy'])->name('block.destroy');
    Route::get('/blocked', [BlockController::class, 'index'])->name('blocked.index');

    // Reportar usuarios
    Route::post('/report/{userId}', [ReportController::class, 'store'])->name('report.store');

    // Notificaciones
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all');
    Route::get('/notifications/unread/count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.count');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Suscripciones (requieren autenticación)
    Route::get('/mi-suscripcion', [\App\Http\Controllers\SubscriptionController::class, 'dashboard'])->name('subscriptions.dashboard');
    Route::get('/checkout/{planSlug}', [\App\Http\Controllers\SubscriptionController::class, 'checkout'])->name('subscriptions.checkout');
    Route::post('/subscribe/stripe', [\App\Http\Controllers\SubscriptionController::class, 'processStripe'])->name('subscriptions.stripe');
    Route::post('/subscribe/paypal', [\App\Http\Controllers\SubscriptionController::class, 'processPayPal'])->name('subscriptions.paypal');
    Route::post('/subscribe/paypal/create', [\App\Http\Controllers\SubscriptionController::class, 'createPayPalSubscription'])->name('subscriptions.paypal.create');
    Route::post('/subscribe/paypal/activate', [\App\Http\Controllers\SubscriptionController::class, 'activatePayPalSubscription'])->name('subscriptions.paypal.activate');
    Route::get('/subscribe/paypal/success', [\App\Http\Controllers\SubscriptionController::class, 'paypalSuccess'])->name('subscriptions.paypal.success');
    Route::post('/subscription/cancel', [\App\Http\Controllers\SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::post('/subscription/reactivate', [\App\Http\Controllers\SubscriptionController::class, 'reactivate'])->name('subscriptions.reactivate');

    // Preview de emails (solo admin, para desarrollo)
    Route::prefix('email-preview')->middleware('admin')->group(function () {
        Route::get('/subscription-activated', function () {
            $user = auth()->user();
            $subscription = \App\Models\UserSubscription::where('user_id', $user->id)->first();

            if (!$subscription) {
                // Crear datos de prueba
                $plan = \App\Models\Plan::where('activo', true)->where('precio_mensual', '>', 0)->first();
                $subscription = new \App\Models\UserSubscription([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'tipo' => 'mensual',
                    'estado' => 'activa',
                    'monto_pagado' => $plan->precio_mensual,
                    'fecha_inicio' => now(),
                    'fecha_expiracion' => now()->addMonth(),
                ]);
                $subscription->setRelation('plan', $plan);
            }

            return view('emails.subscription-activated', [
                'user' => $user,
                'subscription' => $subscription,
                'plan' => $subscription->plan,
            ]);
        });

        Route::get('/payment-failed', function () {
            $user = auth()->user();
            $subscription = \App\Models\UserSubscription::where('user_id', $user->id)->first();

            if (!$subscription) {
                $plan = \App\Models\Plan::where('activo', true)->where('precio_mensual', '>', 0)->first();
                $subscription = new \App\Models\UserSubscription([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'tipo' => 'mensual',
                    'estado' => 'impagada',
                    'monto_pagado' => $plan->precio_mensual,
                    'fecha_inicio' => now()->subMonth(),
                    'fecha_expiracion' => now(),
                ]);
                $subscription->setRelation('plan', $plan);
            }

            return view('emails.payment-failed', [
                'user' => $user,
                'subscription' => $subscription,
                'plan' => $subscription->plan,
            ]);
        });

        Route::get('/subscription-renewed', function () {
            $user = auth()->user();
            $subscription = \App\Models\UserSubscription::where('user_id', $user->id)->first();

            if (!$subscription) {
                $plan = \App\Models\Plan::where('activo', true)->where('precio_mensual', '>', 0)->first();
                $subscription = new \App\Models\UserSubscription([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'tipo' => 'mensual',
                    'estado' => 'activa',
                    'monto_pagado' => $plan->precio_mensual,
                    'fecha_inicio' => now()->subMonth(),
                    'fecha_expiracion' => now()->addMonth(),
                ]);
                $subscription->setRelation('plan', $plan);
            }

            return view('emails.subscription-renewed', [
                'user' => $user,
                'subscription' => $subscription,
                'plan' => $subscription->plan,
            ]);
        });
    });

    // Panel de Administración (solo para administradores)
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
        Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::post('/reports/{reportId}', [AdminController::class, 'updateReport'])->name('reports.update');
        Route::get('/verification', [AdminController::class, 'verificationQueue'])->name('verification');
        Route::post('/verify/{requestId}', [AdminController::class, 'verifyProfile'])->name('verify');
        Route::post('/verification/{requestId}/reject', [AdminController::class, 'rejectVerification'])->name('verification.reject');
        Route::post('/unverify/{profileId}', [AdminController::class, 'unverifyProfile'])->name('unverify');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/{userId}/suspend', [AdminController::class, 'suspendUser'])->name('users.suspend');
        Route::post('/users/{userId}/activate', [AdminController::class, 'activateUser'])->name('users.activate');

        // Gestión de SEO
        Route::get('/seo', [AdminController::class, 'seoIndex'])->name('seo.index');
        Route::get('/seo/create', [AdminController::class, 'seoCreate'])->name('seo.create');
        Route::post('/seo', [AdminController::class, 'seoStore'])->name('seo.store');
        Route::get('/seo/{id}/edit', [AdminController::class, 'seoEdit'])->name('seo.edit');
        Route::put('/seo/{id}', [AdminController::class, 'seoUpdate'])->name('seo.update');
        Route::delete('/seo/{id}', [AdminController::class, 'seoDestroy'])->name('seo.destroy');

        // Gestión de Planes
        Route::get('/plans', [AdminController::class, 'plansIndex'])->name('plans.index');
        Route::get('/plans/create', [AdminController::class, 'plansCreate'])->name('plans.create');
        Route::post('/plans', [AdminController::class, 'plansStore'])->name('plans.store');
        Route::get('/plans/{id}/edit', [AdminController::class, 'plansEdit'])->name('plans.edit');
        Route::put('/plans/{id}', [AdminController::class, 'plansUpdate'])->name('plans.update');
        Route::delete('/plans/{id}', [AdminController::class, 'plansDestroy'])->name('plans.destroy');

        // Gestión de Contenidos del Sitio
        Route::get('/content', [ContentController::class, 'index'])->name('content.index');
        Route::put('/content', [ContentController::class, 'update'])->name('content.update');
        Route::get('/content/reset/{key}', [ContentController::class, 'reset'])->name('content.reset');
    });
});

// Rutas de páginas legales (públicas - no requieren autenticación)
Route::get('/aviso-legal', [LegalController::class, 'avisoLegal'])->name('legal.aviso-legal');
Route::get('/politica-privacidad', [LegalController::class, 'privacidad'])->name('legal.privacidad');
Route::get('/politica-cookies', [LegalController::class, 'cookies'])->name('legal.cookies');
Route::get('/terminos-condiciones', [LegalController::class, 'terminos'])->name('legal.terminos');
Route::get('/terminos-contratacion', [LegalController::class, 'terminosContratacion'])->name('legal.contract-terms');
Route::get('/condiciones-pago', [LegalController::class, 'condicionesPago'])->name('legal.payment-conditions');

require __DIR__.'/auth.php';
