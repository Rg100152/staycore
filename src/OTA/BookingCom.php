<?php
namespace StayCore\OTA;

class BookingCom extends Connector {
    public function syncAvailability($roomId, $date, $status) {
        // Booking.com OTA_HotelAvailNotif
        return $this->request('POST', '/ota/availability', [
            'hotel_id' => $this->apiKey,
            'room_id' => $roomId,
            'date' => $date,
            'available' => $status === 'Available',
        ]);
    }

    public function syncPrice($roomId, $date, $price) {
        return $this->request('POST', '/ota/price', [
            'hotel_id' => $this->apiKey,
            'room_id' => $roomId,
            'date' => $date,
            'price' => $price,
        ]);
    }

    public function importBookings($fromDate, $toDate) {
        return $this->request('GET', '/ota/reservations', [
            'from' => $fromDate,
            'to' => $toDate,
        ]);
    }
}