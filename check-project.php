<?php
/**
 * Laravel Project Health Check Script
 * Save as: check-project.php in root directory
 * Run: php check-project.php
 */

echo "=================================\n";
echo "   LARAVEL PROJECT HEALTH CHECK  \n";
echo "=================================\n\n";

$checks = [];
$errors = [];
$warnings = [];

// 1. Check .env file
echo "✓ Checking .env file...\n";
if (file_exists('.env')) {
    $checks[] = ".env file exists";
    
    $env = file_get_contents('.env');
    
    if (strpos($env, 'APP_KEY=base64:') !== false && strlen(trim(explode('APP_KEY=', $env)[1])) > 20) {
        $checks[] = "APP_KEY is set";
    } else {
        $errors[] = "APP_KEY not properly set. Run: php artisan key:generate";
    }
    
    if (strpos($env, 'DB_DATABASE=lacuisinengot') !== false) {
        $checks[] = "Database name configured";
    } else {
        $warnings[] = "DB_DATABASE might not be set to 'lacuisinengot'";
    }
} else {
    $errors[] = ".env file not found. Copy from .env.example";
}

// 2. Check vendor directory
echo "✓ Checking Composer dependencies...\n";
if (is_dir('vendor') && file_exists('vendor/autoload.php')) {
    $checks[] = "Composer dependencies installed";
} else {
    $errors[] = "vendor/ directory missing. Run: composer install";
}

// 3. Check node_modules
echo "✓ Checking Node dependencies...\n";
if (is_dir('node_modules')) {
    $checks[] = "Node modules installed";
} else {
    $warnings[] = "node_modules/ missing. Run: npm install";
}

// 4. Check public/build
echo "✓ Checking built assets...\n";
if (is_dir('public/build') || file_exists('public/mix-manifest.json')) {
    $checks[] = "Assets are built";
} else {
    $warnings[] = "Assets not built. Run: npm run build";
}

// 5. Check storage directories
echo "✓ Checking storage directories...\n";
$storageDirs = [
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/framework/cache',
    'storage/app/public',
    'storage/logs'
];

foreach ($storageDirs as $dir) {
    if (is_dir($dir)) {
        $checks[] = "$dir exists";
    } else {
        $errors[] = "$dir missing. Create it manually or run: php artisan storage:link";
    }
}

// 6. Check storage link
echo "✓ Checking storage link...\n";
if (is_link('public/storage') || is_dir('public/storage')) {
    $checks[] = "Storage symlink exists";
} else {
    $warnings[] = "Storage symlink missing. Run: php artisan storage:link";
}

// 7. Check image directories
echo "✓ Checking image directories...\n";
$imageDirs = [
    'storage/app/public/assets',
    'storage/app/public/assets/images'
];

foreach ($imageDirs as $dir) {
    if (is_dir($dir)) {
        $checks[] = "$dir exists";
    } else {
        $warnings[] = "$dir missing. Create it: mkdir -p $dir";
    }
}

// 8. Check required images from schema.sql
echo "✓ Checking product images...\n";
$requiredImages = [
    'entremets-rose.jpg',
    'lime-and-basil-entremets.jpg',
    'blanche-figues&framboises.jpg',
    'mousse-chanh-day.jpg',
    'mousse-dua-luoi.jpg',
    'mousse-viet-quat.jpg',
    'orange-serenade.jpg',
    'strawberry-cloud-cake.jpg',
    'earl-grey-bloom.jpg',
    'non.jpg',
    'phaohoa.jpg',
    'trang-tri.jpg',
    'buy-1-get-1.jpg',
    'free-ship.jpg',
    'gg.jpg'
];

$missingImages = [];
foreach ($requiredImages as $img) {
    $path = "storage/app/public/assets/images/$img";
    if (!file_exists($path)) {
        $missingImages[] = $img;
    }
}

if (empty($missingImages)) {
    $checks[] = "All product images present";
} else {
    $warnings[] = "Missing " . count($missingImages) . " images: " . implode(', ', array_slice($missingImages, 0, 5)) . (count($missingImages) > 5 ? '...' : '');
}

// 9. Check database connection
echo "✓ Checking database connection...\n";
if (file_exists('.env')) {
    $env = parse_ini_file('.env');
    $dbHost = $env['DB_HOST'] ?? '127.0.0.1';
    $dbName = $env['DB_DATABASE'] ?? '';
    $dbUser = $env['DB_USERNAME'] ?? 'root';
    $dbPass = $env['DB_PASSWORD'] ?? '';
    
    try {
        $pdo = new PDO("mysql:host=$dbHost", $dbUser, $dbPass);
        $checks[] = "MySQL connection successful";
        
        // Check if database exists
        $stmt = $pdo->query("SHOW DATABASES LIKE '$dbName'");
        if ($stmt->rowCount() > 0) {
            $checks[] = "Database '$dbName' exists";
            
            // Check tables
            $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
            $stmt = $pdo->query("SHOW TABLES");
            $tableCount = $stmt->rowCount();
            
            if ($tableCount > 0) {
                $checks[] = "Database has $tableCount tables";
            } else {
                $errors[] = "Database exists but has no tables. Import schema.sql";
            }
        } else {
            $errors[] = "Database '$dbName' not found. Create it and import schema.sql";
        }
    } catch (PDOException $e) {
        $errors[] = "Cannot connect to MySQL: " . $e->getMessage();
    }
}

// 10. Check bootstrap/cache
echo "✓ Checking bootstrap cache...\n";
if (is_dir('bootstrap/cache')) {
    $checks[] = "bootstrap/cache exists";
} else {
    $errors[] = "bootstrap/cache missing. Create it: mkdir bootstrap/cache";
}

// 11. Check routes
echo "✓ Checking routes files...\n";
$routeFiles = ['routes/web.php', 'routes/api.php'];
foreach ($routeFiles as $file) {
    if (file_exists($file)) {
        $checks[] = "$file exists";
    } else {
        $warnings[] = "$file missing";
    }
}

// 12. Check key files
echo "✓ Checking essential files...\n";
$essentialFiles = [
    'composer.json',
    'package.json',
    'artisan',
    'public/index.php',
    'vite.config.js'
];

foreach ($essentialFiles as $file) {
    if (file_exists($file)) {
        $checks[] = "$file exists";
    } else {
        $errors[] = "$file missing - project may be incomplete";
    }
}

// Print Results
echo "\n=================================\n";
echo "         RESULTS SUMMARY          \n";
echo "=================================\n\n";

echo "✅ PASSED: " . count($checks) . " checks\n";
if (!empty($warnings)) {
    echo "⚠️  WARNINGS: " . count($warnings) . "\n";
}
if (!empty($errors)) {
    echo "❌ ERRORS: " . count($errors) . "\n";
}

if (!empty($errors)) {
    echo "\n❌ ERRORS TO FIX:\n";
    echo "─────────────────────────────────\n";
    foreach ($errors as $i => $error) {
        echo ($i + 1) . ". $error\n";
    }
}

if (!empty($warnings)) {
    echo "\n⚠️  WARNINGS (Optional but Recommended):\n";
    echo "─────────────────────────────────\n";
    foreach ($warnings as $i => $warning) {
        echo ($i + 1) . ". $warning\n";
    }
}

// Recommendations
echo "\n=================================\n";
echo "       NEXT STEPS                 \n";
echo "=================================\n\n";

if (empty($errors)) {
    echo "🎉 No critical errors found!\n\n";
    echo "To start the project:\n";
    echo "1. php artisan serve\n";
    echo "2. Visit: http://localhost:8000\n";
    echo "3. Login as admin: username=admin, password=password\n\n";
} else {
    echo "⚠️  Fix the errors above first, then run:\n";
    echo "php check-project.php\n\n";
}

if (in_array("Assets not built. Run: npm run build", $warnings)) {
    echo "📦 Build assets:\n";
    echo "npm run build\n\n";
}

if (count($missingImages) > 0) {
    echo "🖼️  Add missing images to:\n";
    echo "storage/app/public/assets/images/\n\n";
}

echo "=================================\n";
echo "Check complete!\n";
echo "=================================\n";