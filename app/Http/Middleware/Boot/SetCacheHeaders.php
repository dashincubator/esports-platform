<?php

namespace App\Http\Middleware\Boot;

/**
 *------------------------------------------------------------------------------
 *
 *  Unused For Now
 *  - Seems Like There Is No Real Benefit When Using Cloudflare. It Doesn't
 *    Accept/Return Empty Response To User If Match Is Found And We Build The
 *    Full Response On Each Page Load Anyway. Might Be Missing Something So
 *    Leaving Here For Now
 *
 */

use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class SetCacheHeaders implements Contract
{

    public function handle(Request $request, Closure $next) : Response
    {
        $response = $next($request);
        //$response = $this->setEtags($request, $response);

        return $response;
    }


    private function getEtags(Request $request) : array
    {
        return preg_split('/\s*,\s*/', $request->getHeaders()->get('If-None-Match', ''), null, PREG_SPLIT_NO_EMPTY);
    }


    private function setEtags(Request $request, Response $response) : Response
    {
        if ((!$request->isMethod('get') && !$request->isMethod('head')) || !$response->getContent()) {
            return $response;
        }

        $etag = md5($response->getContent());
        $etags = $this->getEtags($request);

        if ($etags && (in_array($etag, $etags) || in_array('*', $etags))) {
            $response->setContent('');
            $response->setStatus(304);

            foreach ([
                'Allow', 'Content-Encoding', 'Content-Language', 'Content-Length', 'Content-MD5', 'Content-Type', 'Last-Modified'
            ] as $header) {
                $response->getHeaders()->delete($header);
            }
        }

        $response->getHeaders()->set('ETag', '"' . $etag . '"');

        return $response;
    }
}
