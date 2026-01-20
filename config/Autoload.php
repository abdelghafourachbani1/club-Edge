<?php

spl_autoload_register(function (string $className): void {
    //  mapping for App\ namespace
    if (str_starts_with($className, 'App\\')) {
        $prefix = 'App\\';
        $relativeClass = substr($className, strlen($prefix));
        $file = __DIR__ . '/../app/' . str_replace('\\', '/', $relativeClass) . '.php';

        if (is_file($file)) {
            require_once $file;
            return;
        }
    }

    // for other classes (core or other (flag) structures)
    $relativeClassPath = str_replace('\\', '/', ltrim($className, '\\'));

    $roots = [
        __DIR__ . '/../core/',
        __DIR__ . '/../app/',
    ];

    foreach ($roots as $root) {
        $direct = $root . $relativeClassPath . '.php';
        if (is_file($direct)) {
            require_once $direct;
            return;
        }

        $leaf = basename($relativeClassPath);
        $directories = [
            '',
            'Controllers/',
            'Models/',
            'Repositories/',
            'Interfaces/',
            'Helpers/',
        ];

        foreach ($directories as $directory) {
            $file = $root . $directory . $leaf . '.php';
            if (is_file($file)) {
                require_once $file;
                return;
            }
        }
    }
});
