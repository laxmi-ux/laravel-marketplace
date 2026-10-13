<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Property;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPropertyController extends Controller
{
    /**
     * Display property listings.
     */
    public function index(Request $request)
    {
        $query = Listing::with([
            'property',
            'seller',
        ])->where('category_id', 1);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%')
                    ->orWhere('state', 'like', '%' . $search . '%')
                    ->orWhere('pincode', 'like', '%' . $search . '%');

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Approval Status
        |--------------------------------------------------------------------------
        */
        if ($request->filled('approval_status')) {

            $query->where(
                'status',
                $request->approval_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Property Status
        |--------------------------------------------------------------------------
        */
        if ($request->filled('property_status')) {

            $query->whereHas('property', function ($q) use ($request) {

                $q->where(
                    'property_status',
                    $request->property_status
                );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Properties
        |--------------------------------------------------------------------------
        */
        $properties = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Dashboard Cards
        |--------------------------------------------------------------------------
        */
        $baseQuery = Listing::where(
            'category_id',
            1
        );

        $totalProperties = (clone $baseQuery)
            ->count();

        $pendingProperties = (clone $baseQuery)
            ->where('status', 'pending')
            ->count();

        $approvedProperties = (clone $baseQuery)
            ->where('status', 'approved')
            ->count();

        $rejectedProperties = (clone $baseQuery)
            ->where('status', 'rejected')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Property Status Filter Values
        |--------------------------------------------------------------------------
        */
        $propertyStatuses = Property::whereHas(
                'listing',
                function ($q) {
                    $q->where('category_id', 1);
                }
            )
            ->whereNotNull('property_status')
            ->where('property_status', '!=', '')
            ->distinct()
            ->orderBy('property_status')
            ->pluck('property_status');

        return view(
            'admin.properties.index',
            compact(
                'properties',
                'totalProperties',
                'pendingProperties',
                'approvedProperties',
                'rejectedProperties',
                'propertyStatuses'
            )
        );
    }


    /**
     * Show Add Property Listing form.
     */
    public function create()
    {
        return view(
            'admin.properties.create'
        );
    }


    /**
     * Store new property listing.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */
        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Listing Details
            |--------------------------------------------------------------------------
            */
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'listing_type' => [
                'required',
                'string',
                'max:50',
            ],


            /*
            |--------------------------------------------------------------------------
            | Property Details
            |--------------------------------------------------------------------------
            */
            'property_type' => [
                'required',
                'string',
                'max:100',
            ],

            'area' => [
                'required',
                'numeric',
                'min:0',
            ],

            'area_unit' => [
                'required',
                'string',
                'max:50',
            ],

            'bedrooms' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'bathrooms' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'amenities' => [
                'nullable',
                'array',
            ],

            'amenities.*' => [
                'string',
                'max:100',
            ],

            'property_status' => [
                'required',
                'string',
                'max:100',
            ],


            /*
            |--------------------------------------------------------------------------
            | Price
            |--------------------------------------------------------------------------
            */
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'price_unit' => [
                'required',
                'string',
                'max:50',
            ],


            /*
            |--------------------------------------------------------------------------
            | Location
            |--------------------------------------------------------------------------
            */
            'city' => [
                'required',
                'string',
                'max:100',
            ],

            'state' => [
                'required',
                'string',
                'max:100',
            ],

            'pincode' => [
                'required',
                'string',
                'max:20',
            ],


            /*
            |--------------------------------------------------------------------------
            | Main Property Image
            |--------------------------------------------------------------------------
            | Maximum 5 MB = 5120 KB
            |--------------------------------------------------------------------------
            */
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],


            /*
            |--------------------------------------------------------------------------
            | Property Documents
            |--------------------------------------------------------------------------
            | Maximum 10 documents
            | Maximum 10 MB per document
            |--------------------------------------------------------------------------
            */
            'documents' => [
                'nullable',
                'array',
                'max:10',
            ],

            'documents.*' => [
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:10240',
            ],
        ]);


        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Upload Main Property Image
            |--------------------------------------------------------------------------
            */
            $imagePath = null;

            if ($request->hasFile('image')) {

                $uploadPath = base_path(
                    'uploads/listings'
                );

                if (!file_exists($uploadPath)) {

                    mkdir(
                        $uploadPath,
                        0755,
                        true
                    );
                }

                $imageName =
                    time()
                    . '_'
                    . uniqid()
                    . '.'
                    . $request
                        ->file('image')
                        ->getClientOriginalExtension();

                $request
                    ->file('image')
                    ->move(
                        $uploadPath,
                        $imageName
                    );

                $imagePath =
                    'listings/' . $imageName;
            }


            /*
            |--------------------------------------------------------------------------
            | Create Main Listing
            |--------------------------------------------------------------------------
            */
            $listing = Listing::create([

                'title' =>
                    $validated['title'],

                'description' =>
                    $validated['description'] ?? null,

                'listing_type' =>
                    $validated['listing_type'],

                // Properties category
                'category_id' => 1,

                'price' =>
                    $validated['price'],

                'price_unit' =>
                    $validated['price_unit'],

                'city' =>
                    $validated['city'],

                'state' =>
                    $validated['state'],

                'pincode' =>
                    $validated['pincode'],

                'image' =>
                    $imagePath,

                // Admin-created listing
                'status' =>
                    'approved',

                'is_featured' =>
                    false,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Create Property Details
            |--------------------------------------------------------------------------
            */
            Property::create([

                'listing_id' =>
                    $listing->id,

                'property_type' =>
                    $validated['property_type'],

                'area' =>
                    $validated['area'],

                'area_unit' =>
                    $validated['area_unit'],

                'bedrooms' =>
                    $validated['bedrooms'] ?? null,

                'bathrooms' =>
                    $validated['bathrooms'] ?? null,

                'amenities' =>
                    $validated['amenities'] ?? [],

                'property_status' =>
                    $validated['property_status'],
            ]);


            /*
            |--------------------------------------------------------------------------
            | Upload Property Documents
            |--------------------------------------------------------------------------
            */
            if ($request->hasFile('documents')) {

                $documentUploadPath =
                    base_path(
                        'uploads/documents'
                    );

                if (!file_exists(
                    $documentUploadPath
                )) {

                    mkdir(
                        $documentUploadPath,
                        0755,
                        true
                    );
                }


                foreach (
                    $request->file('documents')
                    as $document
                ) {

                    $documentName =
                        time()
                        . '_'
                        . uniqid()
                        . '.'
                        . $document->getClientOriginalExtension();


                    $document->move(
                        $documentUploadPath,
                        $documentName
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Save Document Record
                    |--------------------------------------------------------------------------
                    */
                    Document::create([

                        'listing_id' =>
                            $listing->id,

                        'document' =>
                            'documents/' . $documentName,

                        'name' =>
                            $document->getClientOriginalName(),
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */
            DB::commit();


            return redirect()
                ->route(
                    'admin.properties.index'
                )
                ->with(
                    'success',
                    'Property listing created successfully.'
                );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Rollback
            |--------------------------------------------------------------------------
            */
            DB::rollBack();


            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Failed to create property listing: '
                    . $e->getMessage()
                );
        }
    }
}