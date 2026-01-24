<?php
session_start();

echo "<h1>🔍 Session Check</h1>";
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";

echo "<h2>Session ID:</h2>";
echo "<p>" . session_id() . "</p>";

echo "<h2>Cookie:</h2>";
echo "<pre>";
var_dump($_COOKIE);
echo "</pre>";

echo "<hr>";
echo "<a href='/club-Edge/events'>→ Go to events</a>";