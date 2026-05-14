<?php

namespace App\Http\Controllers\seller;

use App\Http\Controllers\Controller;
use App\Services\MediaStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                return redirect()->route('login');
            }

            if (Auth::user()->role !== 'seller') {
                return redirect($this->redirectForRole(Auth::user()->role))
                    ->with('error', 'Please login with a seller account to manage seller profile settings.');
            }

            return $next($request);
        });
    }

    private function redirectForRole(?string $role): string
    {
        return match ($role) {
            'admin' => route('admin.dashboard'),
            'customer' => route('shop'),
            default => route('login'),
        };
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore(Auth::id())],
            'phone' => ['nullable', 'string', 'max:40'],
            'passport' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'current_password' => ['required_with:password', 'nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'seller_vat_enabled' => 'nullable|boolean',
            'seller_vat_rate' => 'required|numeric|min:0|max:100',
            'seller_delivery_payment' => 'required|in:free,customer',
            'seller_delivery_fee' => 'required_if:seller_delivery_payment,customer|numeric|min:0|max:999999999',
        ]);

        $seller = Auth::user();
        if (!empty($validated['password']) && !Hash::check($validated['current_password'] ?? '', $seller->password)) {
            return back()
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->withInput();
        }

        $profileData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ];

        if ($request->hasFile('passport')) {
            MediaStorage::delete($seller->passport);

            $profileData['passport'] = MediaStorage::upload($request->file('passport'), 'profiles', 'image');
        }

        if (!empty($validated['password'])) {
            $profileData['password'] = Hash::make($validated['password']);
        }

        $seller->forceFill(array_merge($profileData, [
            'seller_vat_enabled' => $request->boolean('seller_vat_enabled'),
            'seller_vat_rate' => $validated['seller_vat_rate'],
            'seller_delivery_payment' => $validated['seller_delivery_payment'],
            'seller_delivery_fee' => $validated['seller_delivery_payment'] === 'customer'
                ? ($validated['seller_delivery_fee'] ?? 0)
                : 0,
        ]))->save();

        return redirect()
            ->route('seller.settings')
            ->with('success', 'Profile and store settings updated successfully.');
    }
}
