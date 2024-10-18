<?php
require 'vendor/autoload.php';
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Server\IoServer;
require_once('application/controllers/ChatServer.php');

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatServer()
        )
    ),
    8080
);

echo "Servidor WebSocket corriendo en el puerto 8080...\n";
$server->run();
?>

