<?php
require __DIR__ . '/vendor/autoload.php';
$app = new Illuminate\Foundation\Application(
    realpath(__DIR__)
);
$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);
$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);
$app->singleton(
    App\Contracts\DebugExceptionHandler::class,
    App\Exceptions\Handler::class
);
$app->boot();

use Illuminate\Support\Facades\DB;

try {
    $users = DB::table('users')->get();
    echo "Found " . $users->count() . " users" . PHP_EOL;
    foreach ($users as $user) {
        echo "User: {$user->first_name} {$user->last_name} - Rank: {$user->rank} - Admin: " . ($user->is_admin ? 'yes' : 'no') . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . PHP_EOL;
}
