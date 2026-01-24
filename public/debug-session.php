<?php
session_start();

echo "<h1>🔍 Debug Session</h1>";
echo "<h2>Contenu de \$_SESSION :</h2>";
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";

echo "<h2>Tests des clés :</h2>";
echo "isset(\$_SESSION['user']) ? <strong>" . (isset($_SESSION['user']) ? 'OUI ✅' : 'NON ❌') . "</strong><br>";
echo "isset(\$_SESSION['user_id']) ? <strong>" . (isset($_SESSION['user_id']) ? 'OUI ✅' : 'NON ❌') . "</strong><br>";
echo "isset(\$_SESSION['role']) ? <strong>" . (isset($_SESSION['role']) ? 'OUI ✅' : 'NON ❌') . "</strong><br>";
echo "isset(\$_SESSION['club_id']) ? <strong>" . (isset($_SESSION['club_id']) ? 'OUI ✅' : 'NON ❌') . "</strong><br>";

if (isset($_SESSION['role'])) {
    echo "<br>Role actuel : <strong style='color: green;'>{$_SESSION['role']}</strong>";
}

echo "<br><br><a href='/club-Edge/events'>← Retour aux événements</a>";

