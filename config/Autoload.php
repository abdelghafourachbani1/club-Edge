<?php
spl_autoload_register(function (string $className): void {
<<<<<<< HEAD
    //  mapping for App\ namespace
    if (str_starts_with($className, 'App\\')) {
        $prefix = 'App\\';
        $relativeClass = substr($className, strlen($prefix));
        $file = __DIR__ . '/../App/' . str_replace('\\', '/', $relativeClass) . '.php';
=======
    $className = ltrim($className, '\\');
>>>>>>> a1de675 (bug)

    // Only App namespace
    if (str_starts_with($className, 'App\\')) {
        $relativeClass = substr($className, 4); // remove 'App\'
        $file = __DIR__ . '/../app/' . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    // Core classes
    $file = __DIR__ . '/../core/' . str_replace('\\', '/', $className) . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }

<<<<<<< HEAD
    $roots = [
        __DIR__ . '/../core/',
        __DIR__ . '/../App/',
    ];

    foreach ($roots as $root) {
        $direct = $root . $relativeClassPath . '.php';
        if (is_file($direct)) {
            require_once $direct;
=======
    // Fallback: look in subdirectories of app
    $subdirs = ['Controllers', 'Models', 'Repositories', 'Interfaces', 'Helpers'];
    foreach ($subdirs as $subdir) {
        $file = __DIR__ . '/../app/' . $subdir . '/' . basename($className) . '.php';
        if (file_exists($file)) {
            require_once $file;
>>>>>>> a1de675 (bug)
            return;
        }
    }
});
