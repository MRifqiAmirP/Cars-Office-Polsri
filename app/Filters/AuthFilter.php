<?php

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
    // public function before(RequestInterface $request, $arguments = null)
    // {
    //     if (!session('isLoggedIn')) {
    //         return responseError('Harus login terlebih dahulu', 401, 'Unauthorized');
    //         // return redirect()->to(base_url('/login'));
    //     }
    // }

    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session('isLoggedIn')) {
            if ($this->isApiRequest($request)) {
                return responseError('Harus login terlebih dahulu', 401, 'Unauthorized');
            } else {
                return redirect()->to(base_url('/login'));
            }
        }
    }

    // public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    // {
    //     // Do something here if needed
    // }

    /**
     * Deteksi apakah request berasal dari API tools atau browser
     */
    private function isApiRequest(RequestInterface $request): bool
    {
        // 1. Cek header X-Requested-With (biasa digunakan jQuery/AJAX)
        $xRequestedWith = $request->getHeaderLine('X-Requested-With');
        if ($xRequestedWith === 'XMLHttpRequest') {
            return true; // AJAX request dari browser
        }

        // 2. Cek header Accept untuk JSON response
        $acceptHeader = $request->getHeaderLine('Accept');
        if (strpos($acceptHeader, 'application/json') !== false) {
            return true;
        }

        // 3. Cek header Content-Type
        $contentType = $request->getHeaderLine('Content-Type');
        if (strpos($contentType, 'application/json') !== false) {
            return true;
        }

        // 4. Cek jika request memiliki origin header (biasa dari API tools)
        $origin = $request->getHeaderLine('Origin');
        $userAgent = $request->getHeaderLine('User-Agent');
        
        // Jika tidak ada origin dan user-agent, kemungkinan dari API tools
        if (empty($origin) && empty($userAgent)) {
            return true;
        }

        // 5. Cek User-Agent untuk deteksi Postman/Insomnia
        if ($this->isApiToolUserAgent($userAgent)) {
            return true;
        }

        // 6. Cek jika request melalui AJAX dari frontend (browser)
        if ($request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {
            return true;
        }

        // Default: dianggap browser request
        return false;
    }

    /**
     * Deteksi User-Agent tools API seperti Postman, Insomnia, dll
     */
    private function isApiToolUserAgent(string $userAgent): bool
    {
        $apiTools = [
            'postman', 'insomnia', 'thunder-client', 
            'curl', 'wget', 'httpie', 'rest-client',
            'paw', 'bruno', 'hopper'
        ];

        $userAgentLower = strtolower($userAgent);

        foreach ($apiTools as $tool) {
            if (strpos($userAgentLower, $tool) !== false) {
                return true;
            }
        }

        return false;
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
