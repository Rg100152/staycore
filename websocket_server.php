<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/database.php';
require __DIR__ . '/src/WebSocket/RoomStatusServer.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use StayCore\WebSocket\RoomStatusServer;

$port = 8080;
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new RoomStatusServer()
        )
    ),
    $port
);

echo "WebSocket server running on ws://localhost:{$port}\n";
$server->run();