<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== USUARIOS EN LA BASE DE DATOS ===\n\n";

$users = App\Models\User::select('id', 'name', 'email')->orderBy('id')->get();

foreach ($users as $user) {
    echo "ID: {$user->id} | Nombre: {$user->name} | Email: {$user->email}\n";
}

echo "\n=== TOTAL: " . $users->count() . " usuarios ===\n";
