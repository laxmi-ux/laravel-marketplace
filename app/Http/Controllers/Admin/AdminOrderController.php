<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\AdminNotificationHelper;
use App\Models\Order;
use App\Notifications\MarketplaceNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CATEGORY NORMALIZER
    |--------------------------------------------------------------------------
    */

    private function normalizeCategory(?string $category): string
    {
        return match (strtolower(trim((string) $category))) {

            'product',
            'products' => 'products',

            'service',
            'services' => 'services',

            'project',
            'projects' => 'projects',

            'property',
            'properties' => 'properties',

            'land' => 'land',

            default => strtolower(trim((string) $category)),
        };
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN ORDER LIST
    |--------------------------------------------------------------------------
    | Only Products, Services and Projects are shown here.
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Order::with([
            'buyer',
            'seller',
            'listing',
            'quotation',
            'requirement',
        ])
            ->whereIn('category', [
                'product',
                'products',
                'service',
                'services',
                'project',
                'projects',
            ]);

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('order_number', 'like', "%{$search}%")

                    ->orWhereHas('buyer', function ($buyer) use ($search) {

                        $buyer->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })

                    ->orWhereHas('seller', function ($seller) use ($search) {

                        $seller->where('company_name', 'like', "%{$search}%")
                            ->orWhere('business_name', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                    });
            });
        }


        /*
        |--------------------------------------------------------------------------
        | ORDER STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('order_status')) {

            $query->where(
                'order_status',
                $request->order_status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAYMENT STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_status')) {

            $query->where(
                'payment_status',
                $request->payment_status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CATEGORY FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category')) {

            $category = $this->normalizeCategory(
                $request->category
            );

            if (in_array($category, [
                'products',
                'services',
                'projects',
            ], true)) {

                $query->whereIn('category', match ($category) {

                    'products' => [
                        'product',
                        'products',
                    ],

                    'services' => [
                        'service',
                        'services',
                    ],

                    'projects' => [
                        'project',
                        'projects',
                    ],

                    default => [],
                });
            }
        }


        /*
        |--------------------------------------------------------------------------
        | GET ORDERS
        |--------------------------------------------------------------------------
        */

        $orders = $query
            ->latest('ordered_at')
            ->paginate(15)
            ->withQueryString();


        return view(
            'admin.orders.index',
            compact('orders')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN ORDER DETAILS
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $order = Order::with([
            'buyer',
            'seller',
            'listing',
            'quotation',
            'requirement',
        ])
            ->whereIn('category', [
                'product',
                'products',
                'service',
                'services',
                'project',
                'projects',
            ])
            ->findOrFail($id);


        return view(
            'admin.orders.show',
            compact('order')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE ORDER STATUS
    |--------------------------------------------------------------------------
    */

    public function updateStatus(Request $request, $id)
    {
        $order = Order::with([
            'buyer',
            'seller',
        ])
            ->whereIn('category', [
                'product',
                'products',
                'service',
                'services',
                'project',
                'projects',
            ])
            ->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | NORMALIZE CATEGORY
        |--------------------------------------------------------------------------
        */

        $category = $this->normalizeCategory(
            $order->category
        );


        /*
        |--------------------------------------------------------------------------
        | VALID STATUSES BASED ON CATEGORY
        |--------------------------------------------------------------------------
        */

        if ($category === 'products') {

            $allowedStatuses = [
                'pending',
                'confirmed',
                'processing',
                'shipped',
                'delivered',
                'completed',
            ];

        } elseif (in_array($category, [
            'services',
            'projects',
        ], true)) {

            $allowedStatuses = [
                'pending',
                'confirmed',
                'processing',
                'completed',
            ];

        } else {

            return back()->with(
                'error',
                'This order category is not supported by Order Management.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CANCELLED / REJECTED ORDERS
        |--------------------------------------------------------------------------
        */

        if (in_array($order->order_status, [
            'cancelled',
            'rejected',
        ])) {

            return back()->with(
                'error',
                'This order cannot be updated because it has already been cancelled or rejected.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'order_status' => [
                'required',
                'in:' . implode(',', $allowedStatuses),
            ],

            'admin_note' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        $newStatus = $request->order_status;
        $oldStatus = $order->order_status;


        /*
        |--------------------------------------------------------------------------
        | NO CHANGE
        |--------------------------------------------------------------------------
        */

        if (
            $oldStatus === $newStatus &&
            !$request->filled('admin_note')
        ) {

            return back()->with(
                'error',
                'No changes were made to the order.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALID STATUS TRANSITION
        |--------------------------------------------------------------------------
        */

        $validTransitions = [

            'products' => [

                'pending' => [
                    'confirmed',
                ],

                'confirmed' => [
                    'processing',
                ],

                'processing' => [
                    'shipped',
                ],

                'shipped' => [
                    'delivered',
                ],

                'delivered' => [
                    'completed',
                ],

                'completed' => [],
            ],


            'services' => [

                'pending' => [
                    'confirmed',
                ],

                'confirmed' => [
                    'processing',
                ],

                'processing' => [
                    'completed',
                ],

                'completed' => [],
            ],


            'projects' => [

                'pending' => [
                    'confirmed',
                ],

                'confirmed' => [
                    'processing',
                ],

                'processing' => [
                    'completed',
                ],

                'completed' => [],
            ],
        ];


        /*
        |--------------------------------------------------------------------------
        | CHECK TRANSITION
        |--------------------------------------------------------------------------
        */

        if ($oldStatus !== $newStatus) {

            $possibleStatuses =
                $validTransitions[$category][$oldStatus]
                ?? [];

            if (!in_array(
                $newStatus,
                $possibleStatuses,
                true
            )) {

                return back()->with(
                    'error',
                    'Invalid status transition for this ' .
                    ucfirst($category) .
                    ' order. Current status: ' .
                    ucfirst($oldStatus) .
                    '.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | PREPARE UPDATE DATA
        |--------------------------------------------------------------------------
        */

        $data = [
            'order_status' => $newStatus,
        ];


        /*
        |--------------------------------------------------------------------------
        | STATUS TIMESTAMPS
        |--------------------------------------------------------------------------
        */

        switch ($newStatus) {

            case 'confirmed':

                if (!$order->confirmed_at) {

                    $data['confirmed_at'] = now();
                }

                break;


            case 'processing':

                if (!$order->processing_at) {

                    $data['processing_at'] = now();
                }

                break;


            case 'shipped':

                if (!$order->shipped_at) {

                    $data['shipped_at'] = now();
                }

                break;


            case 'delivered':

                if (!$order->delivered_at) {

                    $data['delivered_at'] = now();
                }

                break;


            case 'completed':

                if (!$order->completed_at) {

                    $data['completed_at'] = now();
                }

                break;
        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN NOTE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('admin_note')) {

            $existingNotes = $order->notes;

            $adminNote =
                'Admin note (' .
                $newStatus .
                '): ' .
                trim($request->admin_note);

            $data['notes'] = trim(
                ($existingNotes
                    ? $existingNotes . "\n\n"
                    : '') .
                $adminNote
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SAVE + NOTIFICATIONS
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $order,
            $data,
            $newStatus,
            $oldStatus,
            $request,
            $category
        ) {

            /*
            |--------------------------------------------------------------------------
            | UPDATE ORDER
            |--------------------------------------------------------------------------
            */

            $order->update($data);


            $categoryLabel = match ($category) {

                'products' => 'Product',

                'services' => 'Service',

                'projects' => 'Project',

                default => 'Order',
            };


            $statusLabel = ucfirst($newStatus);


            /*
            |--------------------------------------------------------------------------
            | BUYER NOTIFICATION
            |--------------------------------------------------------------------------
            */

            if ($order->buyer) {

                $message =
                    "{$categoryLabel} order #{$order->order_number} " .
                    "status has been updated from " .
                    ucfirst($oldStatus) .
                    " to " .
                    $statusLabel .
                    " by Admin.";

                $order->buyer->notify(
                    new MarketplaceNotification(
                        'Order Status Updated',
                        $message,
                        'order',
                        route(
                            'buyer.orders.show',
                            $order->id
                        )
                    )
                );
            }


            /*
            |--------------------------------------------------------------------------
            | SELLER NOTIFICATION
            |--------------------------------------------------------------------------
            */

            if (
                $order->seller &&
                $order->seller->user
            ) {

                $message =
                    "{$categoryLabel} order #{$order->order_number} " .
                    "status has been updated from " .
                    ucfirst($oldStatus) .
                    " to " .
                    $statusLabel .
                    " by Admin.";

                $order->seller->user->notify(
                    new MarketplaceNotification(
                        'Order Status Updated',
                        $message,
                        'order',
                        route(
                            'seller.orders.show',
                            $order->id
                        )
                    )
                );
            }


            /*
            |--------------------------------------------------------------------------
            | ADMIN NOTIFICATION
            |--------------------------------------------------------------------------
            */

            $adminMessage =
                "{$categoryLabel} order #{$order->order_number} " .
                "status changed from " .
                ucfirst($oldStatus) .
                " to " .
                $statusLabel .
                " by Admin.";


            if ($request->filled('admin_note')) {

                $adminMessage .=
                    ' Note: ' .
                    trim($request->admin_note);
            }


            AdminNotificationHelper::send(
                'Order Status Updated',
                $adminMessage,
                'order',
                route(
                    'admin.orders.show',
                    $order->id
                )
            );
        });


        return back()->with(
            'success',
            'Order status updated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CANCEL ORDER
    |--------------------------------------------------------------------------
    */

    public function cancel(Request $request, $id)
    {
        $order = Order::with([
            'buyer',
            'seller',
        ])
            ->whereIn('category', [
                'product',
                'products',
                'service',
                'services',
                'project',
                'projects',
            ])
            ->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | COMPLETED / ALREADY CANCELLED
        |--------------------------------------------------------------------------
        */

        if (in_array($order->order_status, [
            'completed',
            'cancelled',
            'rejected',
        ])) {

            return back()->with(
                'error',
                'This order cannot be cancelled.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'reason' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | OLD STATUS
        |--------------------------------------------------------------------------
        */

        $oldStatus = $order->order_status;


        /*
        |--------------------------------------------------------------------------
        | NOTES
        |--------------------------------------------------------------------------
        */

        $notes = $order->notes;

        if ($request->filled('reason')) {

            $notes = trim(
                ($notes
                    ? $notes . "\n\n"
                    : '') .
                'Admin cancellation reason: ' .
                trim($request->reason)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SAVE + NOTIFICATIONS
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $order,
            $notes,
            $request,
            $oldStatus
        ) {

            /*
            |--------------------------------------------------------------------------
            | UPDATE ORDER
            |--------------------------------------------------------------------------
            */

            $order->update([

                'order_status' => 'cancelled',

                'cancelled_at' => now(),

                'notes' => $notes,
            ]);


            /*
            |--------------------------------------------------------------------------
            | BUYER NOTIFICATION
            |--------------------------------------------------------------------------
            */

            if ($order->buyer) {

                $message =
                    'Your order #' .
                    $order->order_number .
                    ' has been cancelled by Admin.';

                if ($request->filled('reason')) {

                    $message .=
                        ' Reason: ' .
                        trim($request->reason);
                }


                $order->buyer->notify(
                    new MarketplaceNotification(
                        'Order Cancelled',
                        $message,
                        'order',
                        route(
                            'buyer.orders.show',
                            $order->id
                        )
                    )
                );
            }


            /*
            |--------------------------------------------------------------------------
            | SELLER NOTIFICATION
            |--------------------------------------------------------------------------
            */

            if (
                $order->seller &&
                $order->seller->user
            ) {

                $message =
                    'Order #' .
                    $order->order_number .
                    ' has been cancelled by Admin.';

                if ($request->filled('reason')) {

                    $message .=
                        ' Reason: ' .
                        trim($request->reason);
                }


                $order->seller->user->notify(
                    new MarketplaceNotification(
                        'Order Cancelled',
                        $message,
                        'order',
                        route(
                            'seller.orders.show',
                            $order->id
                        )
                    )
                );
            }


            /*
            |--------------------------------------------------------------------------
            | ADMIN NOTIFICATION
            |--------------------------------------------------------------------------
            */

            $adminMessage =
                'Order #' .
                $order->order_number .
                ' has been cancelled by Admin.';

            if ($request->filled('reason')) {

                $adminMessage .=
                    ' Reason: ' .
                    trim($request->reason);
            }


            AdminNotificationHelper::send(
                'Order Cancelled',
                $adminMessage,
                'order',
                route(
                    'admin.orders.show',
                    $order->id
                )
            );
        });


        return back()->with(
            'success',
            'Order cancelled successfully.'
        );
    }
}