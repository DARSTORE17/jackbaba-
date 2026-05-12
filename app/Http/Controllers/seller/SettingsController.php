<?php

namespace App\Http\Controllers\seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || Auth::user()->role !== 'seller') {
                abort(403);
            }

            return $next($request);
        });
    }

    public function edit()
    {
        return view('seller.settings', [
            'seller' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'seller_vat_enabled' => 'nullable|boolean',
            'seller_vat_rate' => 'required|numeric|min:0|max:100',
            'seller_delivery_payment' => 'required|in:free,customer',
            'seller_delivery_fee' => 'required_if:seller_delivery_payment,customer|numeric|min:0|max:999999999',
        ]);

        $seller = Auth::user();
        $seller->update([
            'seller_vat_enabled' => $request->boolean('seller_vat_enabled'),
            'seller_vat_rate' => $validated['seller_vat_rate'],
            'seller_delivery_payment' => $validated['seller_delivery_payment'],
            'seller_delivery_fee' => $validated['seller_delivery_payment'] === 'customer'
                ? ($validated['seller_delivery_fee'] ?? 0)
                : 0,
        ]);

        return redirect()
            ->route('seller.settings')
            ->with('success', 'Checkout settings updated successfully.');
    }
}
