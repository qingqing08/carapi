<?php

namespace App\Http\Middleware;

use Closure;

class AccessControlAllowOrigin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
//        $response->header('Access-Control-Allow-Origin: *');
//        $response->header("Access-Control-Allow-Credentials: true");
//        $response->header("Access-Control-Allow-Methods: *");
//        $response->header("Access-Control-Allow-Headers: Content-Type,Access-Token");
//        $response->header("Access-Control-Expose-Headers: *");

        $origin = $request->server('HTTP_ORIGIN') ? $request->server('HTTP_ORIGIN') : '';
//        return json_encode($origin);
        $allow_origin = [
            'http://192.168.199.205:8080',
            'http://localhost:8080',
            'http://cmf.qc110.cn',
        ];

        if (in_array($origin, $allow_origin)) {
            $response->headers->add(['Access-Control-Allow-Origin' => $origin]);
            $response->headers->add(['Access-Control-Allow-Headers' => 'Origin, Content-Type, Cookie,X-CSRF-TOKEN, Accept,Authorization']);
            $response->headers->add(['Access-Control-Expose-Headers' => 'Authorization,authenticated']);
            $response->headers->add(['Access-Control-Allow-Methods' => 'GET, POST, PATCH, PUT, OPTIONS']);
            $response->headers->add(['Access-Control-Allow-Credentials' => 'true']);
        }

        return $response;
    }
}
