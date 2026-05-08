<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

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

            // Merge guest cart items into user cart after successful login
            $this->mergeGuestCartToUserCart($user, $request);

            // Handle intended product addition after login (check session and sessionStorage)
            if ($request->session()->has('intended_product_id') || $request->hasHeader('X-Intended-Product')) {
                $this->handleIntendedProductAddition($user, $request);
            }

            if ($user->role === 'seller') {
                return redirect()->intended(route('seller.dashboard'));
            } elseif ($user->role === 'customer') {
                // Check for intended URL (like checkout) or default to shop
                $intendedUrl = $request->session()->get('intended');
                if ($intendedUrl && $intendedUrl !== url('/')) {
                    $request->session()->forget('intended');
                    return redirect($intendedUrl);
                }
                return redirect()->intended(route('shop'));
            } else {
                // Default to shop or handle error
                return redirect()->intended(route('shop'));
            }
        }

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
            'role' => 'customer', // Default role
        ]);

        Auth::login($user);

        // Merge guest cart items into user cart after successful registration
        $this->mergeGuestCartToUserCart($user, $request);

        // Handle intended product addition after registration
        if ($request->session()->has('intended_product_id')) {
            $this->handleIntendedProductAddition($user, $request);
        }

        if ($user->role === 'seller') {
            return redirect()->intended(route('seller.dashboard'));
        } elseif ($user->role === 'customer') {
            // Check for intended URL (like checkout) or default to shop
            $intendedUrl = $request->session()->get('intended');
            if ($intendedUrl && $intendedUrl !== url('/')) {
                $request->session()->forget('intended');
                return redirect($intendedUrl);
            }
            return redirect()->intended(route('shop'));
        } else {
            return redirect()->intended(route('shop'));
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Merge guest cart items into user's authenticated cart
     */
    private function mergeGuestCartToUserCart(User $user, Request $request)
    {
        $sessionId = $request->session()->getId();

        // Find guest cart (session-based)
        $guestCart = Cart::where('session_id', $sessionId)->first();

        if (!$guestCart || $guestCart->cartItems->isEmpty()) {
            return; // No guest cart items to merge
        }

        // Get or create user's authenticated cart
        $userCart = Cart::firstOrCreate(['user_id' => $user->id]);

        // Get guest cart items
        $guestCartItems = $guestCart->cartItems;

        foreach ($guestCartItems as $guestItem) {
            // Check if user already has this product in their cart
            $existingUserItem = $userCart->cartItems()
                ->where('product_id', $guestItem->product_id)
                ->first();

            if ($existingUserItem) {
                // Update quantity by adding guest cart quantity
                $existingUserItem->update([
                    'quantity' => $existingUserItem->quantity + $guestItem->quantity,
                    'price' => $guestItem->price // Update to latest price
                ]);
                // Delete the guest cart item
                $guestItem->delete();
            } else {
                // Move item from guest cart to user cart
                $guestItem->update([
                    'cart_id' => $userCart->id,
                    'session_id' => null
                ]);
            }
        }

        // Check if guest cart is now empty and delete it
        $guestCart->refresh();
        if ($guestCart->cartItems->isEmpty()) {
            $guestCart->delete();
        }
    }

    /**
     * Handle intended product addition after authentication
     */
    private function handleIntendedProductAddition(User $user, Request $request)
    {
        try {
            $productId = $request->session()->get('intended_product_id');
            $quantity = $request->session()->get('intended_quantity', 1);
            $action = $request->session()->get('intended_action');

            if ($productId && $action === 'add_to_cart') {
                // Validate product exists
                $product = Product::find($productId);
                if ($product) {
                    // Get or create user cart
                    $cart = Cart::firstOrCreate(['user_id' => $user->id]);

                    // Check if item already exists in cart
                    $existingItem = $cart->cartItems()->where('product_id', $productId)->first();

                    if ($existingItem) {
                        $existingItem->update([
                            'quantity' => $existingItem->quantity + $quantity,
                            'price' => $product->new_price // Update to latest price
                        ]);
                    } else {
                        CartItem::create([
                            'cart_id' => $cart->id,
                            'product_id' => $productId,
                            'quantity' => $quantity,
                            'price' => $product->new_price
                        ]);
                    }
                }
            }

            // Clear the intended product session data
            $request->session()->forget(['intended_product_id', 'intended_quantity', 'intended_action']);

        } catch (\Exception $e) {
            // Log the error but don't break the login/registration process
            Log::error('Failed to handle intended product addition: ' . $e->getMessage());

            // Clear the session data anyway to prevent infinite loops
            $request->session()->forget(['intended_product_id', 'intended_quantity', 'intended_action']);
        }
    }
}
