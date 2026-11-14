<?php
namespace StayCore\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class RoomStatusServer implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);
        if (!$data) return;

        // Broadcast room status update to all connected clients
        foreach ($this->clients as $client) {
            if ($client !== $from) {
                $client->send(json_encode([
                    'type' => 'room_status_update',
                    'room_id' => $data['room_id'] ?? null,
                    'status' => $data['status'] ?? null,
                    'timestamp' => date('c'),
                ]));
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }

    public function broadcast($roomId, $status) {
        $payload = json_encode([
            'type' => 'room_status_update',
            'room_id' => $roomId,
            'status' => $status,
            'timestamp' => date('c'),
        ]);
        foreach ($this->clients as $client) {
            $client->send($payload);
        }
    }
}