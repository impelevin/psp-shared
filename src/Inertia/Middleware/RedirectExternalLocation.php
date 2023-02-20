<?php

namespace IMPelevin\PSPShared\Inertia\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RedirectExternalLocation
{

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $parentResponse = $next($request);

        $location = $parentResponse->headers->get('Location');
        if (request()->inertia() && $this->isExternal($request, $location)) {
            return inertia()->location($location);
        }

        return $parentResponse;
    }

    public function isExternal(Request $request, $location)
    {
        if (!$location) {
            return false;
        }

        $currentLocation = $request->getSchemeAndHttpHost();

        // startsWith() is used to correctly handle locations with parameters
        return !Str::startsWith($location, $currentLocation);
    }
}
