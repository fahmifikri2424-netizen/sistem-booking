<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * CorsFilter
 *
 * Filter untuk menambahkan CORS headers pada semua response API,
 * sehingga endpoint /api/* dapat diakses dari aplikasi luar (mobile app, frontend terpisah, dll).
 */
class CorsFilter implements FilterInterface
{
    /**
     * Menambahkan CORS headers sebelum request diproses.
     * Menanggapi preflight OPTIONS request secara langsung.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Tangani preflight OPTIONS request (browser CORS check)
        if ($request->getMethod() === 'options') {
            $response = service('response');
            $response->setHeader('Access-Control-Allow-Origin', '*')
                     ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                     ->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                     ->setHeader('Access-Control-Max-Age', '3600')
                     ->setStatusCode(204);

            return $response;
        }
    }

    /**
     * Menambahkan CORS headers pada response setelah controller selesai diproses.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader('Access-Control-Allow-Origin', '*')
                 ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                 ->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

        return $response;
    }
}
