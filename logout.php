<<<<<<< HEAD

<?php

// Destroy the normal customer session.
session_name('HERMOSO_SESSION');
=======
<?php

>>>>>>> 8dc5399113603236efb726c8becb8ab1ef1509ec
session_start();

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

session_destroy();

header('Location: index.php');
exit;
<<<<<<< HEAD

=======
>>>>>>> 8dc5399113603236efb726c8becb8ab1ef1509ec
