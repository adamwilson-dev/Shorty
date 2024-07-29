<?php

namespace App\Http\Controllers;

use App\Services\UrlService;
use App\Services\UtilityService;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    /**
     * @param Request $request
     * @param UtilityService $utilityService
     */
    public function redirect(
        Request $request,
        UtilityService $utilityService,
        UrlService $urlService
    ) {
        // Get the URL path to attempt looking up the short URL
        $urlPath = $utilityService->getUrlPath();

        // Attempt to load the URL
        $url = $urlService->getUrlByPath($urlPath);
        if($url) {
            // @todo - Make sure it's still enabled

            // Increment visits
            $url->total_visits++;
            $url->save();

            // Redirect to URL
            return redirect($url->long_url);
        }

        // @toto - redirect to 404 page
    }
}
