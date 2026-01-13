<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StoreIntendedDataMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if this is a POST request to cart routes and user is not authenticated
        if ($request->isMethod('post') &&
            str_starts_with($request->path(), 'cart/') &&
            !Auth::check()) {

            // Store intended product data in session
            if ($request->has('intended_product_id')) {
                session([
                    'intended_product_id' => $request->input('intended_product_id'),
                    'intended_quantity' => $request->input('intended_quantity', 1),
                    'intended_action' => $request->input('intended_action', 'add_to_cart')
                ]);
            }
        }

        return $next($request);
    }
}
