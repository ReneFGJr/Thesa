<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class CorsFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Allow from any origin
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization, X-CSRF-TOKEN');
        header('Access-Control-Max-Age: 3600');
        header('Access-Control-Allow-Credentials: true');

        // Handle preflight requests
        if ($request->getMethod() === 'OPTIONS') {
            return response()->setStatusCode(200);
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Allow from any origin
        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
        $response->setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization, X-CSRF-TOKEN');
        $response->setHeader('Access-Control-Allow-Credentials', 'true');

        return $response;
    }
}
