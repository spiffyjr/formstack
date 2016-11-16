<?php

use User\Handler;
use Psr\Http\Message;
use Zend\Diactoros;

// Basic FrontController. I decided to use Zend\Diactoros which is a server implementation 
// of PSR-7. I could have used a third-party router/mux but this project is simple enough
// I just went with plain ole' PHP. 
require __DIR__ . '/vendor/autoload.php';

// Grab our config, plain ole' PHP object.
$config = require __DIR__ . '/config/config.php';

// Setup dependencies to be injected.
// No DIC because this app is simple and it's overkill to add one for the sake of adding one

// Good ole' PDO for data access. For rapid development I'd probably use Doctrine ORM, or, at a minimum
// Doctrine DBAL.
try {
    $pdo = new PDO($config['pdo']['dsn'], $config['pdo']['user'], $config['pdo']['pass']);
} catch (PDOException $e) {
    echo $e->getMessage();
    exit(1);
}

// Our UserRepository that handles DB communication. It could be more "sophisticated"
// and hook into a mapper and/or to a service but this is a simple problem that deserves
// a simple solution. 
$userRepository = new User\Repository\Pdo($pdo);

// Basic validation for the handlers that require it
$userValidator = new User\UserValidator();

$handler = function (
    Message\ServerRequestInterface $request,
    Message\ResponseInterface $response
) use (
    $userRepository,
    $userValidator
) {
    $path = $request->getServerParams()['REQUEST_URI'];
    $userId = null;
    $handler = null;

    preg_match('@\/(\d+)$@', $path, $matches);
    if (!empty($matches)) {
        $userId = (int) $matches[1];
    }

    if ($request->getMethod() == 'GET') {
        if ($path == '/') {
            $handler = new Handler\GetAll($userRepository);
        } elseif ($userId) {
            $handler = new Handler\Get($userRepository, $userId);
        }
    } elseif ($request->getMethod() == 'POST' && $path = '/') {
        $handler = new Handler\Create($userRepository, $userValidator);
    } elseif ($request->getMethod() == 'DELETE' && $userId) {
        $handler = new Handler\Delete($userRepository, $userId);
    } elseif ($request->getMethod() == 'PUT') {
        $handler = new Handler\Update($userRepository, $userValidator, $userId);
    }

    if (!$handler instanceof Handler\Handler) {
        $handler = new Handler\NotFound();
    }

    return $handler($request);
};

$server = Diactoros\Server::createServer(
    $handler,
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$server->listen();
