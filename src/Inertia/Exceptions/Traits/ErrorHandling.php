<?php

namespace IMPelevin\PSPShared\Inertia\Exceptions\Traits;

use Inertia\Inertia;
use Throwable;

trait ErrorHandling
{
    /**
     * Prepare exception for rendering.
     *
     */
    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        if (!app()->environment(['local', 'testing']) && in_array($response->status(), [500, 503, 404, 403])) {
            return Inertia::render('Error', ['status' => $response->status()])
                ->toResponse($request)
                ->setStatusCode($response->status());
        }

        return $response;
    }
}
