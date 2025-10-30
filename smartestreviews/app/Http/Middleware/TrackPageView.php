<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PageView;
use Illuminate\Support\Facades\Log;

class TrackPageView
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Only track GET requests on client-side pages (not admin, api, or assets)
        if ($request->isMethod('GET') 
            && !$request->is('admin/*') 
            && !$request->is('api/*')
            && !$request->is('*.css')
            && !$request->is('*.js')
            && !$request->is('*.jpg')
            && !$request->is('*.png')
            && !$request->is('*.gif')
            && !$request->is('*.svg')
            && !$request->is('_debugbar/*')
            && $response->getStatusCode() === 200) {
            
            // Track in background to avoid blocking response
            try {
                $this->trackPageView($request);
            } catch (\Exception $e) {
                // Log error but don't break the page
                Log::error('Failed to track page view', [
                    'url' => $request->fullUrl(),
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        return $response;
    }
    
    /**
     * Track the page view.
     */
    private function trackPageView(Request $request): void
    {
        $postId = null;
        
        // Try to extract post_id from route parameters (for post detail pages)
        $route = $request->route();
        if ($route && $route->getName() === 'posts.show') {
            $slug = $route->parameter('slug');
            if ($slug) {
                $post = \App\Models\Post::where('slug', $slug)->first();
                if ($post) {
                    $postId = $post->id;
                }
            }
        }
        
        // Also check category pages (posts in categories)
        if ($route && $route->getName() === 'categories.show') {
            // Category page, no specific post
            $postId = null;
        }
        
        // Get or generate session ID
        $sessionId = $request->session()->getId();
        
        // Detect device type from user agent
        $deviceType = $this->detectDeviceType($request->userAgent());
        
        // Create page view record
        PageView::create([
            'url' => $request->fullUrl(),
            'path' => $request->path(),
            'post_id' => $postId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'device_type' => $deviceType,
            'browser' => $this->detectBrowser($request->userAgent()),
            'os' => $this->detectOS($request->userAgent()),
            'session_id' => $sessionId,
        ]);
    }
    
    /**
     * Detect device type from user agent.
     */
    private function detectDeviceType(?string $userAgent): ?string
    {
        if (!$userAgent) {
            return null;
        }
        
        $userAgent = strtolower($userAgent);
        
        if (preg_match('/mobile|android|iphone|ipad|tablet/i', $userAgent)) {
            if (preg_match('/tablet|ipad/i', $userAgent)) {
                return 'tablet';
            }
            return 'mobile';
        }
        
        return 'desktop';
    }
    
    /**
     * Detect browser from user agent.
     */
    private function detectBrowser(?string $userAgent): ?string
    {
        if (!$userAgent) {
            return null;
        }
        
        $userAgent = strtolower($userAgent);
        
        if (strpos($userAgent, 'chrome') !== false) return 'Chrome';
        if (strpos($userAgent, 'firefox') !== false) return 'Firefox';
        if (strpos($userAgent, 'safari') !== false && strpos($userAgent, 'chrome') === false) return 'Safari';
        if (strpos($userAgent, 'edge') !== false) return 'Edge';
        if (strpos($userAgent, 'opera') !== false) return 'Opera';
        
        return 'Unknown';
    }
    
    /**
     * Detect OS from user agent.
     */
    private function detectOS(?string $userAgent): ?string
    {
        if (!$userAgent) {
            return null;
        }
        
        $userAgent = strtolower($userAgent);
        
        if (strpos($userAgent, 'windows') !== false) return 'Windows';
        if (strpos($userAgent, 'mac') !== false) return 'macOS';
        if (strpos($userAgent, 'linux') !== false) return 'Linux';
        if (strpos($userAgent, 'android') !== false) return 'Android';
        if (strpos($userAgent, 'iphone') !== false || strpos($userAgent, 'ipad') !== false) return 'iOS';
        
        return 'Unknown';
    }
}
