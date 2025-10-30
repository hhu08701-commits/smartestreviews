<?php

namespace App\Http\Controllers;

use App\Models\AffiliateLink;
use App\Models\ClickLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AffiliateController extends Controller
{
    /**
     * Redirect to affiliate link and log the click.
     */
    public function redirect($slug, Request $request)
    {
        $affiliateLink = AffiliateLink::enabled()
            ->where('slug', $slug)
            ->firstOrFail();

        // Log the click
        $this->logClick($affiliateLink, $request);

        // Increment click count
        $affiliateLink->incrementClicks();

        // Redirect to the actual affiliate URL
        return redirect($affiliateLink->url, 302);
    }

    /**
     * Log affiliate link click with analytics data.
     */
    private function logClick(AffiliateLink $affiliateLink, Request $request)
    {
        try {
            $clickLog = new ClickLog([
                'affiliate_link_id' => $affiliateLink->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->header('referer'),
                'utm_params' => $request->only(['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content']),
            ]);

            // Try to get geolocation data (you can use a service like ipapi.co)
            // $this->addGeolocationData($clickLog, $request->ip());

            $clickLog->save();
        } catch (\Exception $e) {
            // Log error but don't break the redirect
            Log::error('Failed to log affiliate click', [
                'affiliate_link_id' => $affiliateLink->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Add geolocation data to click log.
     */
    private function addGeolocationData(ClickLog $clickLog, $ipAddress)
    {
        try {
            // Example using ipapi.co (free tier available)
            $response = file_get_contents("http://ipapi.co/{$ipAddress}/json/");
            $data = json_decode($response, true);

            if ($data && !isset($data['error'])) {
                $clickLog->country = $data['country_code'] ?? null;
                $clickLog->city = $data['city'] ?? null;
                $clickLog->latitude = $data['latitude'] ?? null;
                $clickLog->longitude = $data['longitude'] ?? null;
            }
        } catch (\Exception $e) {
            // Silently fail geolocation lookup
        }
    }
}
