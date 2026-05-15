<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Services\SecurityLogger;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Log successful login
            app(SecurityLogger::class)->logSuccessfulLogin();

            // Merge guest cart items into user cart after successful login
            $this->mergeGuestCartToUserCart($user, $request);

            // Handle intended product addition after login (check session and sessionStorage)
            if ($request->session()->has('intended_product_id') || $request->hasHeader('X-Intended-Product')) {
                $this->handleIntendedProductAddition($user, $request);
            }

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'seller') {
                return redirect()->route('seller.dashboard');
            } elseif ($user->role === 'customer') {
                $intendedUrl = $request->session()->get('intended');

                if ($intendedUrl && $intendedUrl !== url('/')) {
                    $request->session()->forget('intended');
                    return redirect($intendedUrl);
                }

                return redirect()->intended(route('shop'));
            }

            return redirect()->intended(route('shop'));
        }

        // Log failed login attempt
        app(SecurityLogger::class)->logFailedLogin($request->email);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        Auth::login($user);

        // Merge guest cart items into user cart after successful registration
        $this->mergeGuestCartToUserCart($user, $request);

        // Handle intended product addition after registration
        if ($request->session()->has('intended_product_id')) {
            $this->handleIntendedProductAddition($user, $request);
        }

        if ($user->role === 'seller') {
            return redirect()->route('seller.dashboard');
        } elseif ($user->role === 'customer') {
            $intendedUrl = $request->session()->get('intended');

            if ($intendedUrl && $intendedUrl !== url('/')) {
                $request->session()->forget('intended');
                return redirect($intendedUrl);
            }

            return redirect()->intended(route('shop'));
        }

        return redirect()->intended(route('shop'));
    }

    public function logout(Request $request)
    {
        // Log logout before destroying session
        app(SecurityLogger::class)->logEvent(
            'user_logout',
            'User logged out successfully',
            [],
            'low'
        );

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Merge guest cart items into user's authenticated cart.
     */
    private function mergeGuestCartToUserCart(User $user, Request $request)
    {
        $sessionId = $request->session()->getId();

        $guestCart = Cart::where('session_id', $sessionId)->first();

        if (!$guestCart || $guestCart->cartItems->isEmpty()) {
            return;
        }

        $userCart = Cart::firstOrCreate(['user_id' => $user->getKey()]);

        foreach ($guestCart->cartItems as $guestItem) {
            $existingUserItem = $userCart->cartItems()
                ->where('product_id', $guestItem->product_id)
                ->first();

            if ($existingUserItem) {
                $existingUserItem->update([
                    'quantity' => $existingUserItem->quantity + $guestItem->quantity,
                    'price' => $guestItem->price,
                ]);

                $guestItem->delete();
            } else {
                $guestItem->update([
                    'cart_id' => $userCart->id,
                    'session_id' => null,
                ]);
            }
        }

        $guestCart->refresh();

        if ($guestCart->cartItems->isEmpty()) {
            $guestCart->delete();
        }
    }

    /**
     * Handle intended product addition after authentication.
     */
    private function handleIntendedProductAddition(User $user, Request $request)
    {
        try {
            $productId = $request->session()->get('intended_product_id');
            $quantity = $request->session()->get('intended_quantity', 1);
            $action = $request->session()->get('intended_action');

            if ($productId && $action === 'add_to_cart') {
                $product = Product::find($productId);

                if ($product) {
                    $cart = Cart::firstOrCreate(['user_id' => $user->getKey()]);
                    $existingItem = $cart->cartItems()->where('product_id', $productId)->first();

                    if ($existingItem) {
                        $existingItem->update([
                            'quantity' => $existingItem->quantity + $quantity,
                            'price' => $product->new_price,
                        ]);
                    } else {
                        CartItem::create([
                            'cart_id' => $cart->id,
                            'product_id' => $productId,
                            'quantity' => $quantity,
                            'price' => $product->new_price,
                        ]);
                    }
                }
            }

            $request->session()->forget(['intended_product_id', 'intended_quantity', 'intended_action']);
        } catch (\Exception $e) {
            Log::error('Failed to handle intended product addition: ' . $e->getMessage());
            $request->session()->forget(['intended_product_id', 'intended_quantity', 'intended_action']);
        }
    }
}