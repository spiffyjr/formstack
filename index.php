<?php
use User\Handler;
use Psr\Http\Message;
use Zend\Diactoros;

// Basic FrontController. I decided to use Zend\Diactoros which is a server implementation 
// of PSR-7. I could have used a third-party router/mux but this project is simple enough
// I just went with plain ole' PHP. 
require 'vendor/autoload.php';

// Grab our config, plain ole' PHP object.
$config = require 'config/config.php';

// Setup dependencies to be injected.
// No DIC because this app is simple and it's overkill to add one for the sake of adding one

// Good ole' PDO for data access. For rapid development I'd probably use Doctrine ORM, or, at a minimum
// Doctrine DBAL.
$pdo = new PDO($config['pdo']['dsn'], $config['pdo']['user'], $config['pdo']['pass']);

$server = Diactoros\Server::createServer(
    function (Message\ServerRequestInterface $request, Message\ResponseInterface $response, $done) {
        $path = $request->getServerParams()['REQUEST_URI'];
        $userId = null;
        $handler = null;

        preg_match('@\/(\d+)$@', $path, $matches);
        if (!empty($matches)) {
            $userId = (int) $matches[1];
        }

        if ($request->getMethod() == 'GET') {
            if ($path == '/') {
                $handler = new Handler\GetAll();
            } else if ($userId) {
                $handler = new Handler\Get($userId);
            }
        } else if ($request->getMethod() == 'POST' && $path = '/') {
            $handler = new Handler\Create();
        } else if ($request->getMethod() == 'DELETE' && $userId) {
            $handler = new Handler\Delete($userId);
        } else if ($request->getMethod() == 'PUT') {
            $handler = new Handler\Update($userId);
        }

        if (!$handler instanceof Handler\Handler) {
            $handler = new Handler\NotFound();
        }

        return $handler($request);
    },
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$server->listen();