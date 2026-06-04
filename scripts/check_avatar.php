<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach(\App\Models\Blog::pending()->with('user')->get() as $b) {
    echo $b->user->name . ' - ' . ($b->user->avatar ?? 'null') . "\n";
}
