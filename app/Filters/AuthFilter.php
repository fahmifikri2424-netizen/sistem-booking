<?php

//memcegah akses tanpa login

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in')) {  //Kalau user belum login, arahkan ke halaman login
            return redirect()->to('/login');
        }

        $current = uri_string();  //Ambil URL yang sedang dibuka sekarang  

        // cek role
        if (!empty($arguments)) {  //cek role di route
            if (!in_array(session()->get('role'), $arguments)) {  //memastikan agar role sesuai

                // redirect sesuai role
                if (session()->get('role') == 'admin') { //masuk ke admin
                    return redirect()->to('/admin');
                } elseif (session()->get('role') == 'staff') { //masuk ke staff
                    return redirect()->to('/staff');
                } elseif (session()->get('role') == 'user') {  //masuk ke user
                    return redirect()->to('/user'); 
                }
            }
        }

    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
