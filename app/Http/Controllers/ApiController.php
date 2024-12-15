<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    // public function fetchDataFromSuperset()
    // {
    //     $client = new \GuzzleHttp\Client([
    //         'base_uri' => 'http://localhost:8088/api/v1/',
    //         'timeout'  => 30.0,
    //     ]);

    //     try {
    //         // Xác thực và lấy cookie session
    //         $authResponse = $client->post('security/login', [
    //             'json' => [
    //                 'username' => 'admin',
    //                 'password' => 'admin',
    //                 'provider' => 'db',
    //                 'refresh' => true,
    //             ],
    //             'http_errors' => false,
    //         ]);

    //         $sessionCookie = $this->getSessionCookie($authResponse);
    //         return response()->json($sessionCookie);

    //         // Lấy dữ liệu dashboard
    //         $dashboardResponse = $client->get('dashboard/20/charts', [
    //             'cookies' => $sessionCookie,
    //             'http_errors' => false,
    //         ]);

    //         $dashboardData = json_decode($dashboardResponse->getBody(), true);

    //         return response()->json($dashboardData);
    //     } catch (\Exception $e) {
    //         Log::error('Superset API Error: ' . $e->getMessage());
    //         return response()->json(['error' => 'Could not fetch data'], 500);
    //     }
    // }

    // /**
    //  * Lấy cookie session từ response đăng nhập
    //  *
    //  * @param \Psr\Http\Message\ResponseInterface $response
    //  * @return \GuzzleHttp\Cookie\CookieJar
    //  */
    // protected function getSessionCookie($response)
    // {
    //     $cookies = $response->getHeader('Set-Cookie');
    //     return $cookies;

    //     if (!empty($cookies)) {
    //         $cookieJar = new \GuzzleHttp\Cookie\CookieJar();
    //         foreach ($cookies as $cookie) {
    //             $parts = explode(';', $cookie);
    //             $cookieParts = explode('=', $parts[0]);
    //             $cookieJar->setCookie(new \GuzzleHttp\Cookie\SetCookie([
    //                 'Name' => $cookieParts[0],
    //                 'Value' => $cookieParts[1],
    //                 'Domain' => 'localhost',
    //                 'Path' => '/',
    //             ]));
    //         }
    //         return $cookieJar;
    //     }

    //     return null;
    // }
}
