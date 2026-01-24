<?php
require_once __DIR__ . '/../config/App.php';

Auth::check();

echo '<pre>';
print_r($_SESSION);
echo '</pre>';

echo Auth::isPresident() ? 'PRESIDENT OK': 'NOT PRESIDENT';
