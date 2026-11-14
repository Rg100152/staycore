<?php
namespace StayCore\OTA;

abstract class Connector {
    protected $apiKey;
    protected $apiSecret;
    protected $baseUrl;

    public function __construct($config) {
        $this->apiKey = $config['api_key'] ?? '';
        $this->apiSecret = $config['api_secret'] ?? '';
        $this->baseUrl = $config['base_url'] ?? '';
    }

    abstract public function syncAvailability($roomId, $date, $status);
    abstract public function syncPrice($roomId, $date, $price);
    abstract public function importBookings($fromDate, $toDate);

    protected function request($method, $endpoint, $data = []) {
        $ch = curl_init($this->baseUrl . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json',
        ]);
        if ($data) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ['http_code' => $httpCode, 'body' => json_decode($response, true)];
    }
}