<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Rfq;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AdminNotificationHelper;
use App\Notifications\MarketplaceNotification;
use Illuminate\Support\Facades\DB;

use App\Models\User;


class RfqQuotationController extends Controller
{
    /**
     * -------------------------------------------------------------
     * SHOW QUOTATION FORM
     * -------------------------------------------------------------
     * Seller can create/edit quotation only for their own RFQ.
     */
    public function create(Rfq $rfq)
    {
        $seller = $this->getSeller();

        // Security: RFQ must belong to logged-in seller
        if ((int) $rfq->seller_id !== (int) $seller->id) {
            abort(403);
        }

        $rfq->load([
            'requirement',
            'requirement.listing',
            'requirement.user',
        ]);

        if (!$rfq->requirement) {
            abort(404, 'Requirement associated with this RFQ was not found.');
        }

        $requirement = $rfq->requirement;

        if (!$requirement->user_id) {
            abort(404, 'Buyer associated with this requirement was not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | Determine category
        |--------------------------------------------------------------------------
        */
        $category = $this->getCategory($rfq);

        /*
        |--------------------------------------------------------------------------
        | Find existing quotation
        |--------------------------------------------------------------------------
        | This allows the seller to edit/resend an existing quotation.
        */
        $quotation = Quotation::where('requirement_id', $requirement->id)
            ->where('seller_id', $seller->id)
            ->latest()
            ->first();

        return view(
            'seller.rfqs.quotation',
            compact(
                'rfq',
                'requirement',
                'quotation',
                'category',
                'seller'
            )
        );
    }


    /**
     * -------------------------------------------------------------
     * STORE / UPDATE QUOTATION
     * -------------------------------------------------------------
     */
    public function store(Request $request, Rfq $rfq)
    {
        $seller = $this->getSeller();

        // Security: RFQ must belong to logged-in seller
        if ((int) $rfq->seller_id !== (int) $seller->id) {
            abort(403);
        }

        $rfq->load([
            'requirement',
            'requirement.listing',
            'requirement.user',
        ]);

        if (!$rfq->requirement) {
            abort(404, 'Requirement associated with this RFQ was not found.');
        }

        $requirement = $rfq->requirement;

        if (!$requirement->user_id) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Buyer account associated with this requirement could not be found.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Determine quotation category from RFQ / Requirement
        |--------------------------------------------------------------------------
        */
        $category = $this->getCategory($rfq);

        /*
        |--------------------------------------------------------------------------
        | Common validation
        |--------------------------------------------------------------------------
        */
        $rules = [
            'valid_until' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];


        /*
        |--------------------------------------------------------------------------
        | PRODUCT QUOTATION
        |--------------------------------------------------------------------------
        */
        if ($category === 'product') {

            $rules = array_merge($rules, [

                'product_name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'quantity' => [
                    'required',
                    'numeric',
                    'min:0.01',
                ],

                'unit' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'unit_price' => [
                    'required',
                    'numeric',
                    'min:0',
                ],

                'other_charges' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'tax' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | LAND QUOTATION
        |--------------------------------------------------------------------------
        */ elseif ($category === 'land') {

            $rules = array_merge($rules, [

                'land_location' => [
                    'required',
                    'string',
                    'max:500',
                ],

                'land_area' => [
                    'required',
                    'numeric',
                    'min:0.01',
                ],

                'land_area_unit' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'land_price_per_unit' => [
                    'required',
                    'numeric',
                    'min:0',
                ],

                'land_price' => [
                    'required',
                    'numeric',
                    'min:0',
                ],

                'development_charges' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'registration_charges' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'other_charges' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'tax' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | PROPERTY QUOTATION
        |--------------------------------------------------------------------------
        */ elseif ($category === 'property') {

            $rules = array_merge($rules, [

                'property_type' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'property_location' => [
                    'required',
                    'string',
                    'max:500',
                ],

                'property_area' => [
                    'required',
                    'numeric',
                    'min:0.01',
                ],

                'property_area_unit' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'property_price' => [
                    'required',
                    'numeric',
                    'min:0',
                ],

                'maintenance_charges' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'other_charges' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'tax' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | SERVICE QUOTATION
        |--------------------------------------------------------------------------
        */ elseif ($category === 'service') {

            $rules = array_merge($rules, [

                'service_name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'service_description' => [
                    'nullable',
                    'string',
                    'max:5000',
                ],

                'service_duration' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'service_charges' => [
                    'required',
                    'numeric',
                    'min:0',
                ],

                'labour_charges' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'material_charges' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'other_charges' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'tax' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | PROJECT QUOTATION
        |--------------------------------------------------------------------------
        */ elseif ($category === 'project') {

            $rules = array_merge($rules, [

                'project_name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'project_location' => [
                    'required',
                    'string',
                    'max:500',
                ],

                'project_scope' => [
                    'required',
                    'string',
                    'max:10000',
                ],

                'project_value' => [
                    'required',
                    'numeric',
                    'min:0',
                ],

                'professional_charges' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'project_timeline' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'other_charges' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'tax' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | UNKNOWN CATEGORY
        |--------------------------------------------------------------------------
        */ else {

            return redirect()
                ->route('seller.rfqs.show', $rfq->id)
                ->with(
                    'error',
                    'Quotation cannot be created because the RFQ category is not recognized.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */
        $validated = $request->validate($rules);


        /*
        |--------------------------------------------------------------------------
        | Calculate quotation total
        |--------------------------------------------------------------------------
        */
        $calculation = $this->calculateTotal(
            $category,
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Save quotation
        |--------------------------------------------------------------------------
        */
        $quotation = null;

        DB::transaction(function () use (
            $rfq,
            $requirement,
            $seller,
            $category,
            $validated,
            $calculation,
            &$quotation
        ) {

            $quotation = Quotation::updateOrCreate(
                [
                    'requirement_id' => $requirement->id,
                    'seller_id' => $seller->id,
                ],
                [
                    'buyer_id' => $requirement->user_id,

                    'category' => $category,

                    /*
            |--------------------------------------------------------------------------
            | PRODUCT
            |--------------------------------------------------------------------------
            */

                    'product_name' =>
                    $validated['product_name'] ?? null,

                    'quantity' =>
                    $validated['quantity'] ?? null,

                    'unit' =>
                    $validated['unit'] ?? null,

                    'unit_price' =>
                    $validated['unit_price'] ?? null,

                    'product_subtotal' =>
                    $calculation['product_subtotal'] ?? null,


                    /*
            |--------------------------------------------------------------------------
            | LAND
            |--------------------------------------------------------------------------
            */

                    'land_location' =>
                    $validated['land_location'] ?? null,

                    'land_area' =>
                    $validated['land_area'] ?? null,

                    'land_area_unit' =>
                    $validated['land_area_unit'] ?? null,

                    'land_price_per_unit' =>
                    $validated['land_price_per_unit'] ?? null,

                    'land_price' =>
                    $validated['land_price'] ?? null,

                    'development_charges' =>
                    $validated['development_charges'] ?? 0,

                    'registration_charges' =>
                    $validated['registration_charges'] ?? 0,


                    /*
            |--------------------------------------------------------------------------
            | PROPERTY
            |--------------------------------------------------------------------------
            */

                    'property_type' =>
                    $validated['property_type'] ?? null,

                    'property_location' =>
                    $validated['property_location'] ?? null,

                    'property_area' =>
                    $validated['property_area'] ?? null,

                    'property_area_unit' =>
                    $validated['property_area_unit'] ?? null,

                    'property_price' =>
                    $validated['property_price'] ?? null,

                    'maintenance_charges' =>
                    $validated['maintenance_charges'] ?? 0,


                    /*
            |--------------------------------------------------------------------------
            | SERVICE
            |--------------------------------------------------------------------------
            */

                    'service_name' =>
                    $validated['service_name'] ?? null,

                    'service_description' =>
                    $validated['service_description'] ?? null,

                    'service_duration' =>
                    $validated['service_duration'] ?? null,

                    'service_charges' =>
                    $validated['service_charges'] ?? 0,

                    'labour_charges' =>
                    $validated['labour_charges'] ?? 0,

                    'material_charges' =>
                    $validated['material_charges'] ?? 0,


                    /*
            |--------------------------------------------------------------------------
            | PROJECT
            |--------------------------------------------------------------------------
            */

                    'project_name' =>
                    $validated['project_name'] ?? null,

                    'project_location' =>
                    $validated['project_location'] ?? null,

                    'project_scope' =>
                    $validated['project_scope'] ?? null,

                    'project_value' =>
                    $validated['project_value'] ?? null,

                    'professional_charges' =>
                    $validated['professional_charges'] ?? 0,

                    'project_timeline' =>
                    $validated['project_timeline'] ?? null,


                    /*
            |--------------------------------------------------------------------------
            | COMMON CHARGES
            |--------------------------------------------------------------------------
            */

                    'other_charges' =>
                    $validated['other_charges'] ?? 0,

                    'tax' =>
                    $validated['tax'] ?? 0,

                    'total_amount' =>
                    $calculation['total'],


                    /*
            |--------------------------------------------------------------------------
            | COMMON
            |--------------------------------------------------------------------------
            */

                    'valid_until' =>
                    $validated['valid_until'],

                    'notes' =>
                    $validated['notes'] ?? null,

                    'status' =>
                    'sent',
                ]
            );


            /*
    |--------------------------------------------------------------------------
    | RFQ status
    |--------------------------------------------------------------------------
    */

            $rfq->update([
                'status' => 'quotation_sent',
            ]);


            /*
    |--------------------------------------------------------------------------
    | Requirement status
    |--------------------------------------------------------------------------
    */

            $requirement->update([
                'status' => 'quotation_sent',
            ]);


            /*
    |--------------------------------------------------------------------------
    | Admin notification
    |--------------------------------------------------------------------------
    */

            AdminNotificationHelper::send(
                'New Quotation Submitted',
                'Seller "' .
                    ($rfq->seller->bussiness_name ?? 'Seller') .
                    '" has submitted a quotation for requirement "' .
                    ($requirement->name ?? 'General Requirement') .
                    '".',
                'quotation',
                route(
                    'admin.quotations.show',
                    $quotation->id
                )
            );
        });


        /*
|--------------------------------------------------------------------------
| Buyer notification
|--------------------------------------------------------------------------
*/
$buyer = User::find($requirement->user_id);

if ($buyer) {

    $buyer->notify(
        new MarketplaceNotification(
            'New Quotation Received',
            'You have received a new quotation from '
                . ($seller->bussiness_name ?? 'a seller')
                . ' for '
                . ($requirement->name ?? 'your requirement')
                . '.',
            'quotation',
            route(
                'buyer.quotations.show',
                $quotation->id
            )
        )
    );
}


        return redirect()
            ->route(
                'seller.rfqs.show',
                $rfq->id
            )
            ->with(
                'success',
                'Quotation has been sent successfully.'
            );
    }


    /**
     * -------------------------------------------------------------
     * GET CATEGORY
     * -------------------------------------------------------------
     */
    private function getCategory(Rfq $rfq): string
    {
        $requirement = $rfq->requirement;

        /*
    |--------------------------------------------------------------------------
    | IMPORTANT:
    | For General Requirements, the actual category is stored in
    | requirement_type.
    |
    | Example:
    | category         = general
    | requirement_type = land
    |
    | Therefore requirement_type must be checked FIRST.
    |--------------------------------------------------------------------------
    */

        $category =
            $requirement?->requirement_type
            ?? $requirement?->category
            ?? $requirement?->listing?->category
            ?? null;

        $category = strtolower(
            trim((string) $category)
        );

        return match ($category) {

            // Product
            'building_material',
            'building-material',
            'building materials',
            'materials',
            'product',
            'products'
            => 'product',

            // Land
            'land',
            'lands',
            'plot',
            'plots',
            'land_plot',
            'land-plots',
            'land & plots',
            'land and plots'
            => 'land',

            // Property
            'property',
            'properties'
            => 'property',

            // Service
            'service',
            'services'
            => 'service',

            // Project
            'project',
            'projects'
            => 'project',

            // General
            'general',
            'requirement'
            => 'general',

            default
            => 'general',
        };
    }

    /**
     * -------------------------------------------------------------
     * CALCULATE TOTAL
     * -------------------------------------------------------------
     */
    private function calculateTotal(
        string $category,
        array $data
    ): array {

        $total = 0;

        $result = [
            'product_subtotal' => null,
            'total' => 0,
        ];


        /*
        |--------------------------------------------------------------------------
        | PRODUCT
        |--------------------------------------------------------------------------
        */
        if ($category === 'product') {

            $quantity = (float) (
                $data['quantity'] ?? 0
            );

            $unitPrice = (float) (
                $data['unit_price'] ?? 0
            );

            $subtotal =
                $quantity * $unitPrice;

            $otherCharges = (float) (
                $data['other_charges'] ?? 0
            );

            $tax = (float) (
                $data['tax'] ?? 0
            );

            $total =
                $subtotal
                + $otherCharges
                + $tax;

            $result['product_subtotal'] =
                round($subtotal, 2);
        }


        /*
        |--------------------------------------------------------------------------
        | LAND
        |--------------------------------------------------------------------------
        */ elseif ($category === 'land') {

            $landPrice = (float) (
                $data['land_price'] ?? 0
            );

            $developmentCharges = (float) (
                $data['development_charges'] ?? 0
            );

            $registrationCharges = (float) (
                $data['registration_charges'] ?? 0
            );

            $otherCharges = (float) (
                $data['other_charges'] ?? 0
            );

            $tax = (float) (
                $data['tax'] ?? 0
            );

            $total =
                $landPrice
                + $developmentCharges
                + $registrationCharges
                + $otherCharges
                + $tax;
        }


        /*
        |--------------------------------------------------------------------------
        | PROPERTY
        |--------------------------------------------------------------------------
        */ elseif ($category === 'property') {

            $propertyPrice = (float) (
                $data['property_price'] ?? 0
            );

            $maintenanceCharges = (float) (
                $data['maintenance_charges'] ?? 0
            );

            $otherCharges = (float) (
                $data['other_charges'] ?? 0
            );

            $tax = (float) (
                $data['tax'] ?? 0
            );

            $total =
                $propertyPrice
                + $maintenanceCharges
                + $otherCharges
                + $tax;
        }


        /*
        |--------------------------------------------------------------------------
        | SERVICE
        |--------------------------------------------------------------------------
        */ elseif ($category === 'service') {

            $serviceCharges = (float) (
                $data['service_charges'] ?? 0
            );

            $labourCharges = (float) (
                $data['labour_charges'] ?? 0
            );

            $materialCharges = (float) (
                $data['material_charges'] ?? 0
            );

            $otherCharges = (float) (
                $data['other_charges'] ?? 0
            );

            $tax = (float) (
                $data['tax'] ?? 0
            );

            $total =
                $serviceCharges
                + $labourCharges
                + $materialCharges
                + $otherCharges
                + $tax;
        }


        /*
        |--------------------------------------------------------------------------
        | PROJECT
        |--------------------------------------------------------------------------
        */ elseif ($category === 'project') {

            $projectValue = (float) (
                $data['project_value'] ?? 0
            );

            $professionalCharges = (float) (
                $data['professional_charges'] ?? 0
            );

            $otherCharges = (float) (
                $data['other_charges'] ?? 0
            );

            $tax = (float) (
                $data['tax'] ?? 0
            );

            $total =
                $projectValue
                + $professionalCharges
                + $otherCharges
                + $tax;
        }


        $result['total'] = round(
            $total,
            2
        );

        return $result;
    }


    /**
     * -------------------------------------------------------------
     * GET SELLER
     * -------------------------------------------------------------
     */
    private function getSeller(): Seller
    {
        return Seller::where(
            'user_id',
            Auth::id()
        )->firstOrFail();
    }
}
