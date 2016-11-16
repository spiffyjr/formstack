<?php
use User\Handler;
use Psr\Http\Message;
use Zend\Diactoros;

// Basic FrontController. I decided to use Zend\Diactoros which is a server implementation 
// of PSR-7. I could have used a third-party router/mux but this project is simple enough
// I just went with plain ole' PHP. 
require 'vendor/autoload.php';

$server = Diactoros\Server::createServer(
    function (Message\ServerRequestInterface $request, Message\ResponseInterface $response, $done) {
        $path = $request->getServerParams()['REQUEST_URI'];
        $handler = null;

        if ($request->getMethod() == 'GET') {
            if ($path == '/') {

            }
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