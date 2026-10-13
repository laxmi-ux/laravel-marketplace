<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\AdminNotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Registration
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            // Personal Information
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
            ],

            'phone' => [
                'required',
                'string',
                'max:15',
            ],

            // Account Type
            'role' => [
                'required',
                'in:buyer,seller',
            ],

            /*
            |--------------------------------------------------------------------------
            | Seller Information
            |--------------------------------------------------------------------------
            */

            'seller_type' => [
                'nullable',
                'required_if:role,seller',
                'in:individual,company',
            ],

            'business_name' => [
                'nullable',
                'required_if:role,seller',
                'string',
                'max:255',
            ],

            'business_category' => [
                'nullable',
                'required_if:role,seller',
                'string',
                'max:255',
            ],

            'gst_number' => [
                'nullable',
                'string',
                'max:20',
            ],

            'business_address' => [
                'nullable',
                'required_if:role,seller',
                'string',
                'max:1000',
            ],

            'city' => [
                'nullable',
                'required_if:role,seller',
                'string',
                'max:255',
            ],

            'state' => [
                'nullable',
                'required_if:role,seller',
                'string',
                'max:255',
            ],

            'pincode' => [
                'nullable',
                'required_if:role,seller',
                'string',
                'max:10',
            ],

            // Password
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create User
        |--------------------------------------------------------------------------
        */

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],

            // Seller information
            'seller_type' => $validated['seller_type'] ?? null,
            'business_name' => $validated['business_name'] ?? null,
            'business_category' => $validated['business_category'] ?? null,
            'gst_number' => $validated['gst_number'] ?? null,
            'business_address' => $validated['business_address'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'pincode' => $validated['pincode'] ?? null,

            'password' => Hash::make($validated['password']),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Seller Registration
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'seller') {

            $businessName = $validated['business_name'] ?? $user->name;

            $seller = Seller::create([
                'user_id' => $user->id,

                // Existing database column
                'bussiness_name' => $businessName,

                'category' => $validated['business_category'] ?? 'other',

                'location' => $validated['city'] ?? '',

                'links' => '',

                'logo' => '',

                // New seller requires verification
                'is_verified' => false,

                'is_featured' => false,

                'slug' => Str::slug(
                    $businessName . '-' . $user->id
                ),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Admin Notification - New Seller
            |--------------------------------------------------------------------------
            */

            AdminNotificationHelper::send(
                'New Seller Registration',
                $user->name . ' has registered as a seller and requires verification.',
                'seller_verification',
                route('admin.sellers.show', $seller->id)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Buyer Registration
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'buyer') {

            /*
            |--------------------------------------------------------------------------
            | Admin Notification - New Buyer
            |--------------------------------------------------------------------------
            */

            AdminNotificationHelper::send(
                'New Buyer Registration',
                $user->name . ' has registered on the marketplace.',
                'buyer',
                route('admin.buyers.show', $user->id)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Registered Event
        |--------------------------------------------------------------------------
        */

        event(new Registered($user));

        /*
        |--------------------------------------------------------------------------
        | Login User
        |--------------------------------------------------------------------------
        */

        Auth::login($user);

        /*
        |--------------------------------------------------------------------------
        | Redirect According To Role
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'seller') {
            return redirect()->route('seller.dashboard');
        }

        return redirect()->route('buyer.dashboard');
    }
}