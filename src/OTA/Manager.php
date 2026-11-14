<?php
namespace StayCore\OTA;

use StayCore\OTA\BookingCom;
use StayCore\OTA\Airbnb;

class Manager {
    private $connectors = [];

    public function addConnector($name, Connector $connector) {
        $this->connectors[$name] = $connector;
    }

    public function syncAllRooms($pdo) {
        $stmt = $pdo->query("SELECT id, room_number, status, price_per_night FROM rooms");
        $rooms = $stmt->fetchAll();

        $results = [];
        foreach ($rooms as $room) {
            foreach ($this->connectors as $name => $conn) {
                $res = $conn->syncAvailability($room['id'], date('Y-m-d'), $room['status']);
                $res2 = $conn->syncPrice($room['id'], date('Y-m-d'), $room['price_per_night']);
                $results[] = [
                    'room' => $room['room_number'],
                    'channel' => $name,
                    'availability' => $res['http_code'] === 200 ? 'synced' : 'failed',
                    'price' => $res2['http_code'] === 200 ? 'synced' : 'failed',
                ];
            }
        }
        return $results;
    }

    public function getConnector($name) {
        return $this->connectors[$name] ?? null;
    }
}