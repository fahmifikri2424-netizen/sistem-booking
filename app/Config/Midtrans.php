<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Midtrans extends BaseConfig
{
    /**
     * Server Key Midtrans (RAHASIA — hanya di backend).
     * Ambil dari .env: MIDTRANS_SERVER_KEY
     */
    public string $serverKey = '';

    /**
     * Client Key Midtrans (aman untuk frontend/JS).
     * Ambil dari .env: MIDTRANS_CLIENT_KEY
     */
    public string $clientKey = '';

    /**
     * Mode: false = Sandbox, true = Production
     * Ambil dari .env: MIDTRANS_IS_PRODUCTION
     */
    public bool $isProduction = false;

    /**
     * URL Snap JS (otomatis pilih sandbox/production)
     */
    public function snapJsUrl(): string
    {
        return $this->isProduction
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    public function __construct()
    {
        parent::__construct();

        // Override dari .env
        $this->serverKey    = env('MIDTRANS_SERVER_KEY', $this->serverKey);
        $this->clientKey    = env('MIDTRANS_CLIENT_KEY', $this->clientKey);
        $this->isProduction = (bool) env('MIDTRANS_IS_PRODUCTION', $this->isProduction);
    }
}
