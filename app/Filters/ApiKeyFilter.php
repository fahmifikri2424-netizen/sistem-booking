<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * ApiKeyFilter
 *
 * Memvalidasi API Key pada setiap request ke endpoint /api/*.
 *
 * Format header yang diterima:
 *   Authorization: Bearer <api_key>
 *
 * API Key dikonfigurasi via .env:
 *   api.key = your_secret_key_here
 */
class ApiKeyFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Ambil API Key dari .env
        $validKey = env('api.key', '');

        // Ambil header Authorization dari request
        $authHeader = $request->getHeaderLine('Authorization');

        // Validasi format: harus "Bearer <token>"
        if (empty($authHeader) || !str_starts_with($authHeader, 'Bearer ')) {
            return service('response')
                ->setStatusCode(401)
                ->setContentType('application/json')
                ->setJSON([
                    'status'  => 'error',
                    'code'    => 401,
                    'message' => 'Unauthorized. Header Authorization diperlukan. Format: Bearer <api_key>',
                ]);
        }

        // Ekstrak token dari header
        $token = trim(substr($authHeader, 7)); // hapus "Bearer "

        // Bandingkan token dengan API key yang valid (timing-safe)
        if (!hash_equals($validKey, $token)) {
            return service('response')
                ->setStatusCode(403)
                ->setContentType('application/json')
                ->setJSON([
                    'status'  => 'error',
                    'code'    => 403,
                    'message' => 'Forbidden. API Key tidak valid.',
                ]);
        }

        // Token valid → lanjutkan ke controller
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada aksi setelah response
    }
}
