<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

// Run migrations
echo "Running migrations...\n";
$status = $kernel->call('migrate', ['--force' => true]);

if ($status === 0) {
    echo "✓ Migrations completed successfully.\n\n";

    // Run seeders
    echo "Running seeders...\n";
    $seedStatus = $kernel->call('db:seed', ['--force' => true]);

    if ($seedStatus === 0) {
        echo "✓ Seeders completed successfully.\n";
        echo "\n✓ Database setup complete! Your SQLite database is ready.\n";
    } else {
        echo "✗ Seeding failed with status: " . $seedStatus . "\n";
        exit(1);
    }
} else {
    echo "✗ Migration failed with status: " . $status . "\n";
    exit(1);
}
?>
