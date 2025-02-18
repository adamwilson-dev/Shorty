<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Services\UrlService;
use App\Services\UtilityService;
use Illuminate\Http\Request;
use App\Models\Url;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UrlController extends Controller
{
    /**
     * @var UrlService
     */
    protected UrlService $urlService;

    /**
     * @var UtilityService
     */
    private UtilityService $utilityService;

    /**
     * @param UrlService $urlService
     * @param UtilityService $utilityService
     */
    public function __construct(UrlService $urlService, UtilityService $utilityService)
    {
        $this->urlService = $urlService;
        $this->utilityService = $utilityService;
    }

    public function create(Request $request)
    {
        try {
            // Validate submission
            $request->validate([
                'long-url' => 'required|url',
                'expiry-date' => 'nullable|date_format:Y-m-d H:i',
            ]);

            // Passed validation
            $longUrl = $request->input('long-url');
            $expiryDate = $request->input('expiry-date');

            // Create the short url
            $url = $this->urlService->createShortUrl($longUrl, $expiryDate);

            return response()->json([
                'success' => true,
                'message' => 'Short URL created successfully',
                'short_url' => $url->getFullShortUrl($this->utilityService),
            ]);
        } catch (ValidationException $e) {
            if ($request->ajax()) {
                return response()->json(
                    [
                        'errors' => $e->errors(),
                    ], 422);
            }

            throw $e;
        }
    }
}
