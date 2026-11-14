<?php
namespace StayCore\OTA;

class Airbnb extends Connector {
    public function syncAvailability($roomId, $date, $status) {
        // Airbnb Listings Calendar API
        return $this->request('POST', '/calendar/update', [
            'listing_id' => $roomId,
            'date' => $date,
            'available' => $status === 'Available',
        ]);
    }

    public function syncPrice($roomId, $date, $price) {
        return $this->request('POST', '/calendar/price', [
            'listing_id' => $roomId,
            'date' => $date,
            'price' => $price,
        ]);
    }

    public function importBookings($fromDate, $toDate) {
        return $this->request('GET', '/reservations', [
            'checkin_start' => $fromDate,
            'checkout_end' => $toDate,
        ]);
    }
}