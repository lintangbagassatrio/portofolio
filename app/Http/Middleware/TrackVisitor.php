<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Don't track admin panel or AJAX routes
        if (!$request->is('admin*') && !$request->expectsJson()) {
            $ip = $request->ip();
            $userAgent = $request->userAgent();
            $page = $request->path();
            
            // Limit logging same IP & page to once every 15 minutes
            $exists = Visitor::where('ip_address', $ip)
                ->where('user_agent', $userAgent)
                ->where('page_visited', $page)
                ->where('visit_date', today())
                ->where('created_at', '>=', now()->subMinutes(15))
                ->exists();

            if (!$exists) {
                Visitor::create([
                    'ip_address' => $ip ?: '0.0.0.0',
                    'user_agent' => substr($userAgent, 0, 255),
                    'visit_date' => today(),
                    'page_visited' => $page ?: 'home',
                ]);
            }
        }

        return $next($request);
    }
}
