<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HandleTabs
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->flashTabFromQueryString($request->headers->get('referer', $request->fullUrl()));

        return $next($request);
    }

    private function flashTabFromQueryString($referer)
    {
        if ( ! Str::of($referer)->contains('tab=')) {
            session()->remove('tab');
        }

        parse_str(parse_url($referer, PHP_URL_QUERY), $array);

        if ( ! empty($array['tab'])) {
            session()->put('tab', $array['tab']);
        }
    }
}
