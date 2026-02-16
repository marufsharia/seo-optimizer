<?php

namespace HyroPlugins\SeoOptimizer\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeoRedirectMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $currentPath = $request->path();
        
        // Check for redirect
        $redirect = DB::table('seo_redirects')
            ->where('is_active', true)
            ->where(function($query) use ($currentPath) {
                $query->where('old_url', $currentPath)
                    ->orWhere('old_url', '/' . $currentPath)
                    ->orWhere('old_url', url($currentPath));
            })
            ->first();
        
        if ($redirect) {
            // Increment hits
            DB::table('seo_redirects')
                ->where('id', $redirect->id)
                ->increment('hits');
                
            return redirect($redirect->new_url, $redirect->status_code);
        }
        
        return $next($request);
    }
}
