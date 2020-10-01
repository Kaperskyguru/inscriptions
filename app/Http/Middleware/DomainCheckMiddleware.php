<?php

namespace App\Http\Middleware;

use Closure;

class DomainCheckMiddleware
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
        $allowedHosts = explode(',', env('ALLOWED_DOMAINS'));

        $requestHost = $request->headers->get('host');
        //$requestHost = parse_url($request->headers->get('host'),  PHP_URL_HOST);

        
        $config = app('config');
        if($requestHost == 'inscriptions.flofest.test' || $requestHost == 'inscriptions.flofest.ca' || $requestHost == 'flofest.yanslab.com' || $requestHost == 'flofest.hitthefloor.ca') {
            $config->set('EVENT_TYPE_ID', 2);
            $config->set('EVENT_TYPE_NAME', 'FLOFEST');
            $config->set('EVENT_TYPE_THEME', 'flofest');
        }else {
            $config->set('EVENT_TYPE_ID', 1);
            $config->set('EVENT_TYPE_NAME', 'Hit the floor');
            $config->set('EVENT_TYPE_THEME', 'htf');
        }
        
        // if(!app()->runningUnitTests()) {
        //     if(!\in_array($requestHost, $allowedHosts, false)) {
        //         // $requestInfo = [
        //         //     'host' => $requestHost,
        //         //     'ip' => $request->getClientIp(),
        //         //     'url' => $request->getRequestUri(),
        //         //     'agent' => $request->header('User-Agent'),
        //         // ];
        //         // event(new UnauthorizedAccess($requestInfo));

        //         // throw new SuspiciousOperationException('This host is not allowed');
        //     }
        // }
        return $next($request);
    }
}
