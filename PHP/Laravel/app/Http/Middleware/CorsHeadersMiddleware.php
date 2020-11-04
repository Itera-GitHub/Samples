<?php

namespace App\Http\Middleware;

use Closure;

class CorsHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $allowedHosts = explode(',', env('API_ALLOWED_DOMAINS'));
        $headers = [
            'Access-Control-Allow-Origin' => '',
            'Access-Control-Allow-Methods' => 'POST, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age' => '86400',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Cache-Control, Pragma'
        ];
        $requestHost = parse_url($request->headers->get('origin'), PHP_URL_HOST);
        if (in_array($requestHost, $allowedHosts)) {
            $headers['Access-Control-Allow-Origin'] = $request->headers->get('origin');
        }

        if ($request->isMethod('OPTIONS')) {
            return response()->json('OK', 200, $headers);
        }
        $response = $next($request);
        foreach ($headers as $key => $value) {
            $response->header($key, $value);
        }
        return $response;
    }
}
