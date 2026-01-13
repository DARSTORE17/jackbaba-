<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page
     */
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            session(['intended' => route('checkout.index')]);
            return redirect()->route('register')
                ->with('info', 'Please register or login to proceed with checkout');
        }

        $user = Auth::user();
        $cart = $this->getCart();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty');
        }

        // Calculate totals
        $cartItems = $cart->cartItems()->with('product.media')->get();
        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $taxRate = 0.18; // 18% VAT
        $taxAmount = $subtotal * $taxRate;
        $shippingCost = $subtotal >= 100000 ? 0 : 5000; // Free shipping over 100,000 TZS
        $total = $subtotal + $taxAmount + $shippingCost;

        // East African countries
        $eastAfricanCountries = [
            'TZ' => 'Tanzania (+255)',
            'KE' => 'Kenya (+254)',
            'UG' => 'Uganda (+256)',
            'RW' => 'Rwanda (+250)',
            'BI' => 'Burundi (+257)',
            'SS' => 'South Sudan (+211)',
        ];

        // Regions/States by country
        $countryRegions = [
            'TZ' => [
                'Arusha', 'Dar es Salaam', 'Dodoma', 'Geita', 'Iringa', 'Kagera', 'Katavi', 'Kigoma', 'Kilimanjaro', 'Lindi', 'Manyara', 'Mara', 'Mbeya', 'Mjini Magharibi', 'Morogoro', 'Mtwara', 'Mwanza', 'Njombe', 'Pemba North', 'Pemba South', 'Pwani', 'Rukwa', 'Ruvuma', 'Shinyanga', 'Simiyu', 'Singida', 'Songwe', 'Tabora', 'Tanga', 'Zanzibar North', 'Zanzibar South', 'Zanzibar West'
            ],
            'KE' => [
                'Baringo', 'Bomet', 'Bungoma', 'Busia', 'Elgeyo-Marakwet', 'Embu', 'Garissa', 'Homa Bay', 'Isiolo', 'Kajiado', 'Kakamega', 'Kericho', 'Kiambu', 'Kilifi', 'Kirinyaga', 'Kisii', 'Kisumu', 'Kitui', 'Kwale', 'Laikipia', 'Lamu', 'Machakos', 'Makueni', 'Mandera', 'Marsabit', 'Meru', 'Migori', 'Mombasa', 'Muranga', 'Nairobi City', 'Nakuru', 'Nandi', 'Narok', 'Nyamira', 'Nyandarua', 'Nyeri', 'Samburu', 'Siaya', 'Taita-Taveta', 'Tana River', 'Tharaka-Nithi', 'Trans Nzoia', 'Turkana', 'Uasin Gishu', 'Vihiga', 'Wajir', 'West Pokot'
            ],
            'UG' => [
                'Abim', 'Adjumani', 'Agago', 'Alebtong', 'Amolatar', 'Amudat', 'Amuria', 'Amuru', 'Apac', 'Arua', 'Budaka', 'Bududa', 'Bugiri', 'Buhweju', 'Buikwe', 'Bukedea', 'Bukomansimbi', 'Bukwo', 'Bulambuli', 'Buliisa', 'Bundibugyo', 'Bunyangabu', 'Bushenyi', 'Busia', 'Butaleja', 'Butambala', 'Butebo', 'Buvuma', 'Buyende', 'Dokolo', 'Gomba', 'Gulu', 'Hoima', 'Ibanda', 'Iganga', 'Isingiro', 'Jinja', 'Kaabong', 'Kabarole', 'Kaberamaido', 'Kagadi', 'Kakumiro', 'Kalangala', 'Kaliro', 'Kalungu', 'Kampala', 'Kamuli', 'Kamwenge', 'Kanungu', 'Kapchorwa', 'Kapelebyong', 'Kasese', 'Katakwi', 'Kayunga', 'Kibaale', 'Kiboga', 'Kibuku', 'Kikuube', 'Kiruhura', 'Kiryandongo', 'Kisoro', 'Kitgum', 'Koboko', 'Kole', 'Kotido', 'Kumi', 'Kwania', 'Kween', 'Kyankwanzi', 'Kyegegwa', 'Kyenjojo', 'Kyotera', 'Lamwo', 'Lira', 'Luuka', 'Luweero', 'Lwengo', 'Lyantonde', 'Manafwa', 'Maracha', 'Maracha-Terego', 'Masaka', 'Masindi', 'Mayuge', 'Mbale', 'Mbarara', 'Mitooma', 'Mityana', 'Moroto', 'Moyo', 'Mpigi', 'Mubende', 'Mukono', 'Nakapiripirit', 'Nakaseke', 'Nakasongola', 'Namayingo', 'Namutumba', 'Napak', 'Nebbi', 'Ngora', 'Ntoroko', 'Ntungamo', 'Nwoya', 'Omoro', 'Otuke', 'Oyam', 'Pader', 'Pakwach', 'Pallisa', 'Rakai', 'Rubanda', 'Rubirizi', 'Rukungiri', 'Sembabule', 'Serere', 'Sheema', 'Sironko', 'Soroti', 'Tororo', 'Wakiso', 'Yumbe', 'Zombo'
            ],
            'RW' => [
                'Eastern Province', 'Kigali', 'Northern Province', 'Southern Province', 'Western Province'
            ],
            'BI' => [
                'Bubanza', 'Bujumbura Mairie', 'Bujumbura Rural', 'Bururi', 'Cankuzo', 'Cibitoke', 'Gitega', 'Karuzi', 'Kayanza', 'Kirundo', 'Makamba', 'Muyinga', 'Mwaro', 'Ngozi', 'Rumonge', 'Rutana', 'Ruyigi'
            ],
            'SS' => [
                'Central Equatoria', 'Eastern Equatoria', 'Jonglei', 'Lakes', 'Northern Bahr el Ghazal', 'Unity', 'Upper Nile', 'Warrap', 'Western Bahr el Ghazal', 'Western Equatoria'
            ]
        ];

        return view('shop.checkout', compact(
            'cart',
            'cartItems',
            'subtotal',
            'taxAmount',
            'shippingCost',
            'total',
            'eastAfricanCountries',
            'countryRegions',
            'user'
        ));
    }

    /**
     * Process the checkout
     */
    public function store(Request $request)
    {
        try {
            // Validate required info form
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone_country' => 'required|string|size:2|in:TZ,KE,UG,RW,BI,SS',
                'phone_number' => 'required|string|regex:/^[0-9]{9}$/',
                'shipping_country' => 'required|string|size:2|in:TZ,KE,UG,RW,BI,SS',
                'shipping_region' => 'required|string|max:255',

                // Optional info form
                'shipping_address' => 'nullable|string|max:500',
                'shipping_state' => 'nullable|string|max:255',
                'shipping_postal_code' => 'nullable|string|max:20',
                'billing_same_as_shipping' => 'nullable|boolean',
                'billing_address' => 'nullable|string|max:500',
                'billing_city' => 'nullable|string|max:255',
                'billing_state' => 'nullable|string|max:255',
                'billing_postal_code' => 'nullable|string|max:20',
                'customer_notes' => 'nullable|string|max:1000',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return JSON validation errors for AJAX requests
            return response()->json([
                'success' => false,
                'message' => 'Please fill in all required fields correctly.',
                'errors' => $e->errors()
            ], 422);
        }

        $user = Auth::user();
        $cart = $this->getCart();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty. Please add items to your cart before checkout.'
            ], 400);
        }

        $cartItems = $cart->cartItems()->with('product')->get();

        // Check stock availability
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock for {$item->product->name}. Available stock: {$item->product->stock}"
                ], 400);
            }
        }

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $taxRate = 0.18;
        $taxAmount = $subtotal * $taxRate;
        $shippingCost = $subtotal >= 100000 ? 0 : 5000;
        $totalAmount = $subtotal + $taxAmount + $shippingCost;

        // Country codes mapping for order storage
        $countryCodes = [
            'TZ' => 'Tanzania',
            'KE' => 'Kenya',
            'UG' => 'Uganda',
            'RW' => 'Rwanda',
            'BI' => 'Burundi',
            'SS' => 'South Sudan',
        ];

        // Phone number formatting with country code
        $phoneCountryCodes = [
            'TZ' => '+255',
            'KE' => '+254',
            'UG' => '+256',
            'RW' => '+250',
            'BI' => '+257',
            'SS' => '+211',
        ];

        $formattedPhone = $phoneCountryCodes[$request->phone_country] . $request->phone_number;

        DB::beginTransaction();
        try {
            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'currency' => 'TZS',
                'customer_notes' => $request->customer_notes,
                'ordered_at' => now(),

                // Store customer info directly on order
                'customer_name' => $request->first_name . ' ' . $request->last_name,
                'customer_email' => $request->email,
                'customer_phone' => $formattedPhone,
            ]);

            // Create order items and update stock
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'product_sku' => $cartItem->product->sku ?? null,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->price,
                    'total_price' => $cartItem->price * $cartItem->quantity,
                ]);

                // Reduce product stock
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            // Create shipping address from form data
            $this->createOrderAddressFromForm($order, $request, 'shipping', $formattedPhone);

            // Create billing address (same as shipping if checked, otherwise from form)
            if ($request->boolean('billing_same_as_shipping')) {
                $this->createOrderAddressFromForm($order, $request, 'billing', $formattedPhone);
            } else {
                $this->createBillingAddressFromForm($order, $request, $formattedPhone);
            }

            // Clear the cart
            $cart->cartItems()->delete();
            $cart->delete();

            DB::commit();

            // Always return JSON for this AJAX endpoint
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully! Your order #' . $order->id . ' has been confirmed.',
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            // Always return JSON for this AJAX endpoint
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order. Please try again.'
            ], 500);
        }
    }

    /**
     * Show checkout success page
     */
    public function success(Order $order)
    {
        // Ensure user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('orderItems.product', 'orderAddresses');

        return view('shop.checkout-success', compact('order'));
    }

    /**
     * Get current user's cart
     */
    private function getCart()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->first();
        } else {
            $sessionId = Session::getId();
            return Cart::where('session_id', $sessionId)->first();
        }
    }

    /**
     * Create order address snapshot
     */
    private function createOrderAddress(Order $order, Address $address, string $type)
    {
        OrderAddress::create([
            'order_id' => $order->id,
            'type' => $type,
            'first_name' => $address->first_name,
            'last_name' => $address->last_name,
            'phone' => $address->phone,
            'email' => $address->email,
            'street_address' => $address->street_address,
            'city' => $address->city,
            'state' => $address->state,
            'postal_code' => $address->postal_code,
            'country' => $address->country,
        ]);
    }

    /**
     * Create order address from form data
     */
    private function createOrderAddressFromForm(Order $order, Request $request, string $type, string $formattedPhone)
    {
        $countryCodes = [
            'TZ' => 'Tanzania',
            'KE' => 'Kenya',
            'UG' => 'Uganda',
            'RW' => 'Rwanda',
            'BI' => 'Burundi',
            'SS' => 'South Sudan',
        ];

        // For shipping addresses
        if ($type === 'shipping') {
            OrderAddress::create([
                'order_id' => $order->id,
                'type' => $type,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $formattedPhone,
                'email' => $request->email,
                'street_address' => $request->delivery_location ?: '',
                'city' => '',
                'state' => $request->shipping_region ?: '',
                'postal_code' => $request->shipping_postal_code ?: '',
                'country' => $countryCodes[$request->shipping_country] ?? $request->shipping_country,
            ]);
        } else {
            // For billing addresses when same as shipping
            OrderAddress::create([
                'order_id' => $order->id,
                'type' => $type,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $formattedPhone,
                'email' => $request->email,
                'street_address' => $request->shipping_address ?: '',
                'city' => $request->shipping_city ?: '',
                'state' => $request->shipping_state ?: '',
                'postal_code' => $request->shipping_postal_code ?: '',
                'country' => $countryCodes[$request->shipping_country] ?? $request->shipping_country,
            ]);
        }
    }

    /**
     * Create billing address from form data
     */
    private function createBillingAddressFromForm(Order $order, Request $request, string $formattedPhone)
    {
        $countryCodes = [
            'TZ' => 'Tanzania',
            'KE' => 'Kenya',
            'UG' => 'Uganda',
            'RW' => 'Rwanda',
            'BI' => 'Burundi',
            'SS' => 'South Sudan',
        ];

        // For billing addresses (different from shipping)
        OrderAddress::create([
            'order_id' => $order->id,
            'type' => 'billing',
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $formattedPhone,
            'email' => $request->email,
            'street_address' => $request->billing_address ?: '',
            'city' => $request->billing_city ?: '',
            'state' => $request->billing_state ?: '',
            'postal_code' => $request->billing_postal_code ?: '',
            'country' => $countryCodes[$request->shipping_country] ?? $request->shipping_country, // Assuming billing country same as shipping if not specified
        ]);
    }
}
