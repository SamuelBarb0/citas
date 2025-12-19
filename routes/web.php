<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use App\Models\Profile;

// Página de inicio pública
Route::get('/', function () {
    $perfiles = Profile::with('user')
        ->where('activo', true)
        ->inRandomOrder()
        ->limit(8)
        ->get();

    return view('welcome', compact('perfiles'));
});

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth', 'verified'])->group(function () {
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

        // Aplicar filtros
        if (request()->has('edad_min')) {
            $query->where('edad', '>=', request('edad_min'));
        }

        if (request()->has('edad_max')) {
            $query->where('edad', '<=', request('edad_max'));
        }

        if (request()->filled('ciudad')) {
            $query->where('ciudad', 'LIKE', '%' . request('ciudad') . '%');
        }

        if (request()->filled('busco')) {
            $query->where('genero', request('busco'));
        }

        if (request()->filled('intereses')) {
            $interesesBuscados = array_map('trim', explode(',', request('intereses')));
            $query->where(function($q) use ($interesesBuscados) {
                foreach ($interesesBuscados as $interes) {
                    $q->orWhereJsonContains('intereses', $interes);
                }
            });
        }

        $perfiles = $query->inRandomOrder()->limit(12)->get();

        // Verificar si hay un nuevo match pendiente de mostrar
        $newMatch = session('new_match');
        if ($newMatch) {
            session()->forget('new_match');
        }

        return view('dashboard', compact('perfiles', 'newMatch'));
    })->name('dashboard');

    // Gestión de Perfil de Usuario (Dating Profile)
    Route::get('/mi-perfil', [UserProfileController::class, 'show'])->name('user.profile.show');
    Route::get('/mi-perfil/crear', [UserProfileController::class, 'create'])->name('user.profile.create');
    Route::post('/mi-perfil', [UserProfileController::class, 'store'])->name('user.profile.store');
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
    Route::get('/super-likes', [LikeController::class, 'superLikesReceived'])->name('likes.super');

    // Matches
    Route::get('/matches', [MatchController::class, 'index'])->name('matches');
    Route::delete('/matches/{matchId}', [MatchController::class, 'destroy'])->name('matches.destroy');

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

    // Panel de Administración (solo para administradores)
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
        Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::post('/reports/{reportId}', [AdminController::class, 'updateReport'])->name('reports.update');
        Route::get('/verification', [AdminController::class, 'verificationQueue'])->name('verification');
        Route::post('/verify/{profileId}', [AdminController::class, 'verifyProfile'])->name('verify');
        Route::post('/unverify/{profileId}', [AdminController::class, 'unverifyProfile'])->name('unverify');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/{userId}/suspend', [AdminController::class, 'suspendUser'])->name('users.suspend');
        Route::post('/users/{userId}/activate', [AdminController::class, 'activateUser'])->name('users.activate');
    });
});

require __DIR__.'/auth.php';
