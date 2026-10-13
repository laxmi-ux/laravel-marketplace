<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show profile page.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }


    /**
     * Update profile.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Get validated data
        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Update User Profile
        |--------------------------------------------------------------------------
        */

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;


        /*
        |--------------------------------------------------------------------------
        | Seller Profile
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'seller') {

            $user->seller_type = $data['seller_type'] ?? null;
            $user->business_name = $data['business_name'] ?? null;
            $user->business_category = $data['business_category'] ?? null;
            $user->gst_number = $data['gst_number'] ?? null;
            $user->business_address = $data['business_address'] ?? null;
            $user->city = $data['city'] ?? null;
            $user->state = $data['state'] ?? null;
            $user->pincode = $data['pincode'] ?? null;


            /*
            |--------------------------------------------------------------------------
            | Update Seller Table
            |--------------------------------------------------------------------------
            */

            $seller = Seller::where('user_id', $user->id)->first();

            if ($seller) {

                // Your database currently uses "bussiness_name"
                $seller->bussiness_name = $user->business_name;

                $seller->category = $user->business_category;

                $seller->location = $user->city;

                $seller->save();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Email Verification
        |--------------------------------------------------------------------------
        */

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }


        /*
        |--------------------------------------------------------------------------
        | Save User
        |--------------------------------------------------------------------------
        */

        $user->save();


        return redirect()
            ->route('profile.edit')
            ->with('status', 'profile-updated');
    }


    /**
     * Delete account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}