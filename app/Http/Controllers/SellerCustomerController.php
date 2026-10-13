<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\Order;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class SellerCustomerController extends Controller
{
    public function index(Request $request)
    {
        // Get seller profiles linked to the logged-in user.
        $sellerIds = Seller::where('user_id', Auth::id())
            ->pluck('id');

        abort_if($sellerIds->isEmpty(), 403, 'Seller profile not found.');

        // Fetch orders belonging to these seller profiles.
        $orders = Order::with(['buyer', 'listing'])
            ->whereIn('seller_id', $sellerIds)
            ->latest()
            ->get();

        // Fetch enquiries belonging to these seller profiles.
        $enquiries = Enquiry::with(['user', 'listing'])
            ->whereIn('seller_id', $sellerIds)
            ->latest()
            ->get();

        $customers = collect();

        // Add customers from orders.
        foreach ($orders as $order) {
            $buyer = $order->buyer;

            if (!$buyer) {
                continue;
            }

            $key = 'user_' . $buyer->id;

            if (!$customers->has($key)) {
                $customers->put($key, [
                    'id' => $buyer->id,
                    'name' => $buyer->name ?? 'Customer',
                    'email' => $buyer->email ?? '-',
                    'phone' => $buyer->phone ?? '-',
                    'orders' => collect(),
                    'enquiries' => collect(),
                    'last_activity' => $order->created_at,
                ]);
            }

            $customer = $customers->get($key);
            $customer['orders']->push($order);

            if (
                !$customer['last_activity'] ||
                ($order->created_at &&
                    $order->created_at->gt($customer['last_activity']))
            ) {
                $customer['last_activity'] = $order->created_at;
            }

            $customers->put($key, $customer);
        }

        // Add customers from enquiries.
        foreach ($enquiries as $enquiry) {
            $user = $enquiry->user;

            $email = strtolower(trim(
                $user->email ?? $enquiry->email ?? ''
            ));

            $key = $user
                ? 'user_' . $user->id
                : ($email !== ''
                    ? 'email_' . $email
                    : 'enquiry_' . $enquiry->id);

            if (!$customers->has($key)) {
                $customers->put($key, [
                    'id' => $user->id ?? null,
                    'name' => $user->name
                        ?? $enquiry->name
                        ?? 'Customer',
                    'email' => $user->email
                        ?? $enquiry->email
                        ?? '-',
                    'phone' => $enquiry->phone ?? '-',
                    'orders' => collect(),
                    'enquiries' => collect(),
                    'last_activity' => $enquiry->created_at,
                ]);
            }

            $customer = $customers->get($key);

            // Fill missing contact information from the enquiry.
            if (
                empty($customer['phone']) ||
                $customer['phone'] === '-'
            ) {
                $customer['phone'] = $enquiry->phone ?? '-';
            }

            if (
                empty($customer['email']) ||
                $customer['email'] === '-'
            ) {
                $customer['email'] = $enquiry->email ?? '-';
            }

            $customer['enquiries']->push($enquiry);

            if (
                !$customer['last_activity'] ||
                ($enquiry->created_at &&
                    $enquiry->created_at->gt($customer['last_activity']))
            ) {
                $customer['last_activity'] = $enquiry->created_at;
            }

            $customers->put($key, $customer);
        }

        // Search by customer name, email or phone.
        $search = trim((string) $request->input('search', ''));

        if ($search !== '') {
            $term = strtolower($search);

            $customers = $customers->filter(function ($customer) use ($term) {
                return str_contains(
                    strtolower($customer['name'] ?? ''),
                    $term
                ) || str_contains(
                    strtolower($customer['email'] ?? ''),
                    $term
                ) || str_contains(
                    strtolower($customer['phone'] ?? ''),
                    $term
                );
            });
        }

        $customers = $customers
            ->sortByDesc('last_activity')
            ->values();

        return view('seller.customers.index', compact(
            'customers',
            'search'
        ));
    }
}
