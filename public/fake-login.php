<?php
session_start();

// Simuler connexion étudiant
if (isset($_GET['student'])) {
    $_SESSION['user_id'] = 2;
    $_SESSION['role'] = 'student';
    $_SESSION['email'] = 'jane@test.com';
    echo "<h1 style='color: green;'>✅ Connecté en tant qu'ÉTUDIANT</h1>";
    echo "<p>user_id: 2, role: student</p>";
    echo "<a href='/club-Edge/events'>→ Aller aux événements</a>";
    exit;
}

// Simuler connexion président
if (isset($_GET['president'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['role'] = 'president';
    $_SESSION['club_id'] = 1;
    $_SESSION['email'] = 'john@test.com';
    echo "<h1 style='color: green;'>✅ Connecté en tant que PRÉSIDENT</h1>";
    echo "<p>user_id: 1, role: president, club_id: 1</p>";
    echo "<a href='/club-Edge/events'>→ Aller aux événements</a>";
    exit;
}

// Déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    echo "<h1>✅ Déconnecté</h1>";
    echo "<a href='/club-Edge/events'>→ Aller aux événements</a>";
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Fake Login - TEST</title>
    <style>
        body { font-family: Arial; max-width: 600px; margin: 50px auto; padding: 20px; }
        h1 { color: #FF6B35; }
        .btn { display: inline-block; padding: 15px 30px; margin: 10px; background: #FF6B35; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; }
        .btn:hover { background: #E55A2B; }
        .logout { background: #1A1A1A; }
    </style>
</head>
<body>
    <h1>🔐 Fake Login (Pour Tests)</h1>
    
    <h2>État actuel :</h2>
    <pre><?php var_dump($_SESSION); ?></pre>
    
    <h2>Actions :</h2>
    <a href="?student" class="btn">Se connecter en ÉTUDIANT</a>
    <a href="?president" class="btn">Se connecter en PRÉSIDENT</a>
    <a href="?logout" class="btn logout">Se déconnecter</a>
    
    <hr>
    <a href="/club-Edge/events">→ Aller aux événements</a>
</body>
</html>
