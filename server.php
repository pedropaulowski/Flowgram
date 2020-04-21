<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Flowgram\Socket;

require dirname( __FILE__ ) . '/vendor/autoload.php';
require 'config.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Socket($pdo)
        )
    ),
    8080
);

$server->run();