<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Seller;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ListingDocument;
use App\Models\Product;
use App\Models\Property;
use App\Models\Land;
use App\Models\Service;
use App\Models\Project;

use App\Helpers\AdminNotificationHelper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SellerListingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Get Logged-in Seller
    |--------------------------------------------------------------------------
    */

    private function getSeller()
    {
        return Seller::where(
            'user_id',
            Auth::id()
        )->firstOrFail();
    }


    /*
    |--------------------------------------------------------------------------
    | Category Slug Map
    |--------------------------------------------------------------------------
    */

    private function categorySlugMap(): array
    {
        return [
            'product'  => 'products',
            'property' => 'properties',
            'land'     => 'land',
            'service'  => 'services',
            'project'  => 'projects',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Display Seller's Listings
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $seller = $this->getSeller();

        $query = Listing::where(
            'seller_id',
            $seller->id
        )->with([
            'categoryRelation',
            'subcategoryRelation',
            'documents',
            'product',
            'property',
            'land',
            'service',
            'project',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category')) {

            $categorySlugMap = $this->categorySlugMap();

            $categoryType = $request->category;

            if (isset($categorySlugMap[$categoryType])) {

                $query->whereHas(
                    'categoryRelation',
                    function ($q) use ($categorySlugMap, $categoryType) {

                        $q->where(
                            'slugs',
                            $categorySlugMap[$categoryType]
                        );
                    }
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Get Listings
        |--------------------------------------------------------------------------
        */

        $listings = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();


        return view(
            'seller.listings.index',
            compact(
                'seller',
                'listings'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Add Listing Form
    |--------------------------------------------------------------------------
    */

    public function create(Request $request)
    {
        $type = $request->query(
            'type',
            'product'
        );


        /*
        |--------------------------------------------------------------------------
        | Allowed Types
        |--------------------------------------------------------------------------
        */

        $allowedTypes = [
            'product',
            'property',
            'land',
            'service',
            'project',
        ];


        /*
        |--------------------------------------------------------------------------
        | Validate Type
        |--------------------------------------------------------------------------
        */

        if (!in_array($type, $allowedTypes)) {

            $type = 'product';
        }


        /*
        |--------------------------------------------------------------------------
        | Listing Type -> Database Category Slug
        |--------------------------------------------------------------------------
        */

        $categorySlugMap = $this->categorySlugMap();

        $databaseCategorySlug =
            $categorySlugMap[$type];


        /*
        |--------------------------------------------------------------------------
        | Find Category
        |--------------------------------------------------------------------------
        */

        $category = Category::where(
            'slugs',
            $databaseCategorySlug
        )->first();


        /*
        |--------------------------------------------------------------------------
        | Find Subcategories
        |--------------------------------------------------------------------------
        */

        $subcategories = collect();

        if ($category) {

            $subcategories = Subcategory::where(
                'category_id',
                $category->id
            )
                ->where(
                    'status',
                    true
                )
                ->orderBy(
                    'name'
                )
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Return Create Listing View
        |--------------------------------------------------------------------------
        */

        return view(
            'seller.listings.create',
            compact(
                'type',
                'subcategories'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store New Listing
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $seller = $this->getSeller();


        /*
        |--------------------------------------------------------------------------
        | Validate Common Listing Data
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],

            'category' => [
                'required',
                'string',
                'in:product,property,land,service,project',
            ],

            /*
             * IMPORTANT:
             * Subcategory is now submitted as ID.
             */
            'subcategory_id' => [
                'nullable',
                'integer',
                'exists:subcategories,id',
            ],

            'listing_type' => [
                'required',
                'string',
                'in:sale,rent,lease,buy',
            ],

            'price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'price_unit' => [
                'nullable',
                'string',
                'max:255',
            ],

            'city' => [
                'required',
                'string',
                'max:255',
            ],

            'state' => [
                'required',
                'string',
                'max:255',
            ],

            'pincode' => [
                'required',
                'string',
                'max:10',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'documents' => [
                'nullable',
                'array',
                'max:10',
            ],

            'documents.*' => [
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:5120',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Category Type
        |--------------------------------------------------------------------------
        */

        $categoryType = $validated['category'];


        /*
        |--------------------------------------------------------------------------
        | PRODUCT VALIDATION
        |--------------------------------------------------------------------------
        */

        if ($categoryType === 'product') {

            $productData = $request->validate([

                'brand' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'product_code' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'quantity' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'unit' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'specifications' => [
                    'nullable',
                    'string',
                ],
            ]);
        } else {

            $productData = [];
        }


        /*
        |--------------------------------------------------------------------------
        | PROPERTY VALIDATION
        |--------------------------------------------------------------------------
        */

        if ($categoryType === 'property') {

            $propertyData = $request->validate([

                'property_type' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'area' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'area_unit' => [
                    'nullable',
                    'string',
                    'max:100',
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

                'property_status' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
            ]);
        } else {

            $propertyData = [];
        }


        /*
        |--------------------------------------------------------------------------
        | LAND VALIDATION
        |--------------------------------------------------------------------------
        */

        if ($categoryType === 'land') {

            $landData = $request->validate([

                'land_type' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'total_area' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'area_unit' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'survey_number' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'gat_number' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'ownership_type' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'road_access' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'zoning' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'water_availability' => [
                    'nullable',
                    'boolean',
                ],

                'electricity_availability' => [
                    'nullable',
                    'boolean',
                ],

                'nearby_infrastructure' => [
                    'nullable',
                    'string',
                    'max:5000',
                ],
            ]);
        } else {

            $landData = [];
        }


        /*
        |--------------------------------------------------------------------------
        | SERVICE VALIDATION
        |--------------------------------------------------------------------------
        */

        if ($categoryType === 'service') {

            $serviceData = $request->validate([

                'service_type' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'service_area' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'experience' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'availability' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'service_description' => [
                    'nullable',
                    'string',
                    'max:5000',
                ],
            ]);
        } else {

            $serviceData = [];
        }


        /*
        |--------------------------------------------------------------------------
        | PROJECT VALIDATION
        |--------------------------------------------------------------------------
        */

        if ($categoryType === 'project') {

            $projectData = $request->validate([

                'project_type' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'project_status' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'developer_name' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'total_area' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'area_unit' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'start_date' => [
                    'nullable',
                    'date',
                ],

                'completion_date' => [
                    'nullable',
                    'date',
                    'after_or_equal:start_date',
                ],

                'project_description' => [
                    'nullable',
                    'string',
                    'max:5000',
                ],
            ]);
        } else {

            $projectData = [];
        }


        /*
        |--------------------------------------------------------------------------
        | Find Category
        |--------------------------------------------------------------------------
        */

        $categorySlugMap = $this->categorySlugMap();

        $databaseCategorySlug =
            $categorySlugMap[$categoryType];


        $category = Category::where(
            'slugs',
            $databaseCategorySlug
        )->first();


        if (!$category) {

            return back()
                ->withInput()
                ->withErrors([
                    'category' =>
                    'Selected category was not found in the categories table.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Find Subcategory
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | We now use subcategory_id and verify that it belongs
        | to the selected category.
        |
        */

        $subcategory = null;

        if (!empty($validated['subcategory_id'])) {

            $subcategory = Subcategory::where(
                'id',
                $validated['subcategory_id']
            )
                ->where(
                    'category_id',
                    $category->id
                )
                ->first();


            if (!$subcategory) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'subcategory_id' =>
                        'Selected subcategory was not found for this category.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Database Transaction
        |--------------------------------------------------------------------------
        */

        DB::beginTransaction();

        $imagePath = null;

        $uploadedDocumentPaths = [];


        try {

            /*
            |--------------------------------------------------------------------------
            | Upload Listing Image
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('image')) {

                $image = $request->file('image');

                $uploadPath = base_path(
                    'uploads/listings'
                );

                if (!is_dir($uploadPath)) {

                    mkdir(
                        $uploadPath,
                        0755,
                        true
                    );
                }

                $imageName =
                    Str::random(40)
                    . '.'
                    . $image->getClientOriginalExtension();

                $image->move(
                    $uploadPath,
                    $imageName
                );

                $imagePath =
                    'listings/' . $imageName;
            }


            /*
            |--------------------------------------------------------------------------
            | Create Common Listing
            |--------------------------------------------------------------------------
            */

            $listing = Listing::create([

                'title' =>
                $validated['title'],

                'slug' =>
                Str::slug(
                    $validated['title']
                        . '-'
                        . uniqid()
                ),

                'description' =>
                $validated['description'],

                'seller_id' =>
                $seller->id,

                'category_id' =>
                $category->id,

                'subcategory_id' =>
                $subcategory?->id,

                'listing_type' =>
                $validated['listing_type'],

                'price' =>
                $validated['price'] ?? null,

                'price_unit' =>
                $validated['price_unit'] ?? null,

                'city' =>
                $validated['city'],

                'state' =>
                $validated['state'],

                'pincode' =>
                $validated['pincode'],

                'seller_name' =>
                $seller->bussiness_name,

                'is_verified' =>
                false,

                'is_featured' =>
                false,

                'status' =>
                'pending',

                'image' =>
                $imagePath,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Create PRODUCT Record
            |--------------------------------------------------------------------------
            */

            if ($categoryType === 'product') {

                Product::create([

                    'listing_id' =>
                    $listing->id,

                    'brand' =>
                    $productData['brand'] ?? null,

                    'product_code' =>
                    $productData['product_code'] ?? null,

                    'quantity' =>
                    $productData['quantity'] ?? null,

                    'unit' =>
                    $productData['unit'] ?? null,

                    'specifications' =>
                    !empty($productData['specifications'])
                        ? json_decode(
                            $productData['specifications'],
                            true
                        )
                        : null,
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Create PROPERTY Record
            |--------------------------------------------------------------------------
            */

            if ($categoryType === 'property') {

                Property::create([

                    'listing_id' =>
                    $listing->id,

                    'property_type' =>
                    $propertyData['property_type'],

                    'area' =>
                    $propertyData['area'] ?? null,

                    'area_unit' =>
                    $propertyData['area_unit'] ?? null,

                    'bedrooms' =>
                    $propertyData['bedrooms'] ?? null,

                    'bathrooms' =>
                    $propertyData['bathrooms'] ?? null,

                    'amenities' =>
                    $propertyData['amenities'] ?? null,

                    'property_status' =>
                    $propertyData['property_status'] ?? null,
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Create LAND Record
            |--------------------------------------------------------------------------
            */

            if ($categoryType === 'land') {

                Land::create([

                    'listing_id' =>
                    $listing->id,

                    'land_type' =>
                    $landData['land_type'],

                    'total_area' =>
                    $landData['total_area'] ?? null,

                    'area_unit' =>
                    $landData['area_unit'] ?? null,

                    'survey_number' =>
                    $landData['survey_number'] ?? null,

                    'gat_number' =>
                    $landData['gat_number'] ?? null,

                    'ownership_type' =>
                    $landData['ownership_type'] ?? null,

                    'road_access' =>
                    $landData['road_access'] ?? null,

                    'zoning' =>
                    $landData['zoning'] ?? null,

                    'water_availability' =>
                    $request->boolean(
                        'water_availability'
                    ),

                    'electricity_availability' =>
                    $request->boolean(
                        'electricity_availability'
                    ),

                    'nearby_infrastructure' =>
                    $landData['nearby_infrastructure'] ?? null,
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Create SERVICE Record
            |--------------------------------------------------------------------------
            */

            if ($categoryType === 'service') {

                Service::create([

                    'listing_id' =>
                    $listing->id,

                    'service_type' =>
                    $serviceData['service_type'],

                    'service_area' =>
                    $serviceData['service_area'] ?? null,

                    'experience' =>
                    $serviceData['experience'] ?? null,

                    'availability' =>
                    $serviceData['availability'] ?? null,

                    'description' =>
                    $serviceData['service_description'] ?? null,
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Create PROJECT Record
            |--------------------------------------------------------------------------
            */

            if ($categoryType === 'project') {

                Project::create([

                    'listing_id' =>
                    $listing->id,

                    'project_type' =>
                    $projectData['project_type'],

                    'project_status' =>
                    $projectData['project_status'] ?? null,

                    'developer_name' =>
                    $projectData['developer_name'] ?? null,

                    'total_area' =>
                    $projectData['total_area'] ?? null,

                    'area_unit' =>
                    $projectData['area_unit'] ?? null,

                    'start_date' =>
                    $projectData['start_date'] ?? null,

                    'completion_date' =>
                    $projectData['completion_date'] ?? null,

                    'description' =>
                    $projectData['project_description'] ?? null,
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Upload Private Documents
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('documents')) {

                foreach (
                    $request->file('documents')
                    as $document
                ) {

                    $documentPath =
                        $document->store(
                            'listing-documents',
                            'local'
                        );

                    $uploadedDocumentPaths[] =
                        $documentPath;


                    ListingDocument::create([

                        'listing_id' =>
                        $listing->id,

                        'document_name' =>
                        $document->getClientOriginalName(),

                        'document_path' =>
                        $documentPath,

                        'document_type' =>
                        $document->getClientMimeType(),

                        'status' =>
                        'pending',
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */

            DB::commit();

            AdminNotificationHelper::send(
                'New Listing Submitted',
                $seller->bussiness_name
                    . ' has submitted a new listing "'
                    . $listing->title
                    . '" for admin approval.',
                'listing',
                route('admin.listings.show', $listing->id)
            );

            return redirect()
                ->route('seller.listings.index')
                ->with(
                    'success',
                    'Your listing has been submitted successfully and is awaiting admin approval.'
                );
        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Delete Uploaded Documents
            |--------------------------------------------------------------------------
            */

            foreach (
                $uploadedDocumentPaths
                as $path
            ) {

                if (
                    Storage::disk('local')
                    ->exists($path)
                ) {

                    Storage::disk('local')
                        ->delete($path);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Delete Uploaded Image
            |--------------------------------------------------------------------------
            */

            if ($imagePath) {

                $fullImagePath =
                    base_path(
                        'uploads/' . $imagePath
                    );

                if (
                    file_exists($fullImagePath)
                ) {

                    unlink($fullImagePath);
                }
            }


            return back()
                ->withInput()
                ->withErrors([
                    'error' =>
                    'Something went wrong while creating the listing. '
                        . $e->getMessage(),
                ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Seller Listing
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $seller = $this->getSeller();


        $listing = Listing::where(
            'id',
            $id
        )
            ->where(
                'seller_id',
                $seller->id
            )
            ->firstOrFail();


        $listing->load([
            'categoryRelation',
            'subcategoryRelation',
            'documents',
            'product',
            'property',
            'land',
            'service',
            'project',
        ]);


        return view(
            'seller.listings.edit',
            compact(
                'seller',
                'listing'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Seller Listing
    |--------------------------------------------------------------------------
    */

    public function show(Listing $listing)
    {
        $seller = $this->getSeller();


        abort_unless(
            $listing->seller_id === $seller->id,
            403
        );


        $listing->load([
            'documents',
            'product',
            'property',
            'land',
            'service',
            'project',
            'categoryRelation',
            'subcategoryRelation',
        ]);


        return view(
            'seller.listings.show',
            compact(
                'seller',
                'listing'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Download Listing Document
    |--------------------------------------------------------------------------
    */

    public function downloadDocument(
        Listing $listing,
        ListingDocument $document
    ) {
        $seller = $this->getSeller();


        abort_unless(
            $listing->seller_id === $seller->id,
            403
        );


        abort_unless(
            $document->listing_id === $listing->id,
            404
        );


        abort_unless(
            Storage::disk('local')->exists(
                $document->document_path
            ),
            404
        );


        return Storage::disk('local')->download(
            $document->document_path,
            $document->document_name
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Seller Listing
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $seller = $this->getSeller();


        $listing = Listing::where(
            'id',
            $id
        )
            ->where(
                'seller_id',
                $seller->id
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Category Type
        |--------------------------------------------------------------------------
        */

        $categoryType = $request->input(
            'category'
        );


        /*
        |--------------------------------------------------------------------------
        | Validate Common Fields
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],

            'category' => [
                'required',
                'in:product,property,land,service,project',
            ],

            /*
             * IMPORTANT:
             * Subcategory is now submitted as ID.
             */
            'subcategory_id' => [
                'nullable',
                'integer',
                'exists:subcategories,id',
            ],

            'listing_type' => [
                'required',
                'in:sale,rent,lease,buy',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'price_unit' => [
                'nullable',
                'string',
                'max:255',
            ],

            'city' => [
                'required',
                'string',
                'max:255',
            ],

            'state' => [
                'required',
                'string',
                'max:255',
            ],

            'pincode' => [
                'required',
                'string',
                'size:6',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'documents' => [
                'nullable',
                'array',
            ],

            'documents.*' => [
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:5120',
            ],

            'remove_documents' => [
                'nullable',
                'array',
            ],

            'remove_documents.*' => [
                'integer',
                'exists:listing_documents,id',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Product Validation
        |--------------------------------------------------------------------------
        */

        if ($categoryType === 'product') {

            $productData = $request->validate([

                'brand' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'product_code' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'quantity' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'unit' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'specifications' => [
                    'nullable',
                    'string',
                ],
            ]);
        } else {

            $productData = [];
        }


        /*
        |--------------------------------------------------------------------------
        | Property Validation
        |--------------------------------------------------------------------------
        */

        if ($categoryType === 'property') {

            $propertyData = $request->validate([

                'property_type' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'area' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'area_unit' => [
                    'nullable',
                    'string',
                    'max:100',
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

                'property_status' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
            ]);
        } else {

            $propertyData = [];
        }


        /*
        |--------------------------------------------------------------------------
        | Land Validation
        |--------------------------------------------------------------------------
        */

        if ($categoryType === 'land') {

            $landData = $request->validate([

                'land_type' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'total_area' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'area_unit' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'survey_number' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'gat_number' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'ownership_type' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'road_access' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'zoning' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'water_availability' => [
                    'nullable',
                    'boolean',
                ],

                'electricity_availability' => [
                    'nullable',
                    'boolean',
                ],

                'nearby_infrastructure' => [
                    'nullable',
                    'string',
                    'max:5000',
                ],
            ]);
        } else {

            $landData = [];
        }


        /*
        |--------------------------------------------------------------------------
        | Service Validation
        |--------------------------------------------------------------------------
        */

        if ($categoryType === 'service') {

            $serviceData = $request->validate([

                'service_type' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'service_area' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'experience' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'availability' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'service_description' => [
                    'nullable',
                    'string',
                    'max:5000',
                ],
            ]);
        } else {

            $serviceData = [];
        }


        /*
        |--------------------------------------------------------------------------
        | Project Validation
        |--------------------------------------------------------------------------
        */

        if ($categoryType === 'project') {

            $projectData = $request->validate([

                'project_type' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'project_status' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'developer_name' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'total_area' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'area_unit' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'start_date' => [
                    'nullable',
                    'date',
                ],

                'completion_date' => [
                    'nullable',
                    'date',
                    'after_or_equal:start_date',
                ],

                'project_description' => [
                    'nullable',
                    'string',
                    'max:5000',
                ],
            ]);
        } else {

            $projectData = [];
        }


        /*
        |--------------------------------------------------------------------------
        | Find Category
        |--------------------------------------------------------------------------
        */

        $categorySlugMap = $this->categorySlugMap();

        $databaseCategorySlug =
            $categorySlugMap[$categoryType];


        $category = Category::where(
            'slugs',
            $databaseCategorySlug
        )->first();


        if (!$category) {

            return back()
                ->withInput()
                ->withErrors([
                    'category' =>
                    'Selected category was not found.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Find Subcategory
        |--------------------------------------------------------------------------
        */

        $subcategory = null;

        if (!empty($validated['subcategory_id'])) {

            $subcategory = Subcategory::where(
                'id',
                $validated['subcategory_id']
            )
                ->where(
                    'category_id',
                    $category->id
                )
                ->first();


            if (!$subcategory) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'subcategory_id' =>
                        'Selected subcategory was not found for this category.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Old Image
        |--------------------------------------------------------------------------
        */

        $oldImage =
            $listing->image;

        $newImagePath = null;


        /*
        |--------------------------------------------------------------------------
        | Transaction
        |--------------------------------------------------------------------------
        */

        try {

            DB::beginTransaction();


            /*
            |--------------------------------------------------------------------------
            | New Image
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('image')) {

                $image =
                    $request->file('image');


                $uploadDirectory =
                    base_path(
                        'uploads/listings'
                    );


                if (!is_dir($uploadDirectory)) {

                    mkdir(
                        $uploadDirectory,
                        0755,
                        true
                    );
                }


                $imageName =
                    Str::random(40)
                    . '.'
                    . $image->getClientOriginalExtension();


                $image->move(
                    $uploadDirectory,
                    $imageName
                );


                $newImagePath =
                    'listings/' . $imageName;
            }


            /*
            |--------------------------------------------------------------------------
            | Update Common Listing
            |--------------------------------------------------------------------------
            */

            $listing->update([

                'title' =>
                $validated['title'],

                'slug' =>
                Str::slug(
                    $validated['title']
                        . '-'
                        . uniqid()
                ),

                'description' =>
                $validated['description'],

                'category_id' =>
                $category->id,

                'subcategory_id' =>
                $subcategory?->id,

                'listing_type' =>
                $validated['listing_type'],

                'price' =>
                $validated['price'],

                'price_unit' =>
                $validated['price_unit'] ?? null,

                'city' =>
                $validated['city'],

                'state' =>
                $validated['state'],

                'pincode' =>
                $validated['pincode'],

                'seller_name' =>
                $seller->bussiness_name,

                'image' =>
                $newImagePath
                    ?? $listing->image,

                /*
                 * Seller edits require admin re-approval.
                 */
                'status' =>
                'pending',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Update / Create Product
            |--------------------------------------------------------------------------
            */

            if ($categoryType === 'product') {

                Product::updateOrCreate(

                    [
                        'listing_id' =>
                        $listing->id,
                    ],

                    [

                        'brand' =>
                        $productData['brand'] ?? null,

                        'product_code' =>
                        $productData['product_code'] ?? null,

                        'quantity' =>
                        $productData['quantity'] ?? null,

                        'unit' =>
                        $productData['unit'] ?? null,

                        'specifications' =>
                        !empty($productData['specifications'])
                            ? json_decode(
                                $productData['specifications'],
                                true
                            )
                            : null,
                    ]
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Update / Create Property
            |--------------------------------------------------------------------------
            */

            if ($categoryType === 'property') {

                Property::updateOrCreate(

                    [
                        'listing_id' =>
                        $listing->id,
                    ],

                    [

                        'property_type' =>
                        $propertyData['property_type'],

                        'area' =>
                        $propertyData['area'] ?? null,

                        'area_unit' =>
                        $propertyData['area_unit'] ?? null,

                        'bedrooms' =>
                        $propertyData['bedrooms'] ?? null,

                        'bathrooms' =>
                        $propertyData['bathrooms'] ?? null,

                        'amenities' =>
                        $propertyData['amenities'] ?? null,

                        'property_status' =>
                        $propertyData['property_status'] ?? null,
                    ]
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Update / Create Land
            |--------------------------------------------------------------------------
            */

            if ($categoryType === 'land') {

                Land::updateOrCreate(

                    [
                        'listing_id' =>
                        $listing->id,
                    ],

                    [

                        'land_type' =>
                        $landData['land_type'],

                        'total_area' =>
                        $landData['total_area'] ?? null,

                        'area_unit' =>
                        $landData['area_unit'] ?? null,

                        'survey_number' =>
                        $landData['survey_number'] ?? null,

                        'gat_number' =>
                        $landData['gat_number'] ?? null,

                        'ownership_type' =>
                        $landData['ownership_type'] ?? null,

                        'road_access' =>
                        $landData['road_access'] ?? null,

                        'zoning' =>
                        $landData['zoning'] ?? null,

                        'water_availability' =>
                        $request->boolean(
                            'water_availability'
                        ),

                        'electricity_availability' =>
                        $request->boolean(
                            'electricity_availability'
                        ),

                        'nearby_infrastructure' =>
                        $landData['nearby_infrastructure']
                            ?? null,
                    ]
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Update / Create Service
            |--------------------------------------------------------------------------
            */

            if ($categoryType === 'service') {

                Service::updateOrCreate(

                    [
                        'listing_id' =>
                        $listing->id,
                    ],

                    [

                        'service_type' =>
                        $serviceData['service_type'],

                        'service_area' =>
                        $serviceData['service_area'] ?? null,

                        'experience' =>
                        $serviceData['experience'] ?? null,

                        'availability' =>
                        $serviceData['availability'] ?? null,

                        'description' =>
                        $serviceData['service_description']
                            ?? null,
                    ]
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Update / Create Project
            |--------------------------------------------------------------------------
            */

            if ($categoryType === 'project') {

                Project::updateOrCreate(

                    [
                        'listing_id' =>
                        $listing->id,
                    ],

                    [

                        'project_type' =>
                        $projectData['project_type'],

                        'project_status' =>
                        $projectData['project_status'] ?? null,

                        'developer_name' =>
                        $projectData['developer_name'] ?? null,

                        'total_area' =>
                        $projectData['total_area'] ?? null,

                        'area_unit' =>
                        $projectData['area_unit'] ?? null,

                        'start_date' =>
                        $projectData['start_date'] ?? null,

                        'completion_date' =>
                        $projectData['completion_date'] ?? null,

                        'description' =>
                        $projectData['project_description']
                            ?? null,
                    ]
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Remove Selected Documents
            |--------------------------------------------------------------------------
            */

            if ($request->filled('remove_documents')) {

                $documentsToRemove =
                    ListingDocument::where(
                        'listing_id',
                        $listing->id
                    )
                    ->whereIn(
                        'id',
                        $request->remove_documents
                    )
                    ->get();


                foreach (
                    $documentsToRemove
                    as $document
                ) {

                    if (
                        $document->document_path &&
                        Storage::disk('local')->exists(
                            $document->document_path
                        )
                    ) {

                        Storage::disk('local')->delete(
                            $document->document_path
                        );
                    }


                    $document->delete();
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Add New Documents
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('documents')) {

                foreach (
                    $request->file('documents')
                    as $document
                ) {

                    $documentPath =
                        $document->store(
                            'listing-documents',
                            'local'
                        );


                    ListingDocument::create([

                        'listing_id' =>
                        $listing->id,

                        'document_name' =>
                        $document->getClientOriginalName(),

                        'document_path' =>
                        $documentPath,

                        'document_type' =>
                        $document->getClientMimeType(),

                        'status' =>
                        'pending',
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Delete Old Image
            |--------------------------------------------------------------------------
            */

            if (
                $newImagePath &&
                $oldImage
            ) {

                $oldImageFile =
                    base_path(
                        'uploads/' . $oldImage
                    );


                if (
                    file_exists($oldImageFile)
                ) {

                    unlink(
                        $oldImageFile
                    );
                }
            }


            return redirect()
                ->route(
                    'seller.listings.index'
                )
                ->with(
                    'success',
                    'Listing updated successfully and has been sent for admin approval.'
                );
        } catch (\Throwable $e) {

            DB::rollBack();


            /*
            |--------------------------------------------------------------------------
            | Delete New Image
            |--------------------------------------------------------------------------
            */

            if ($newImagePath) {

                $newImageFile =
                    base_path(
                        'uploads/' . $newImagePath
                    );


                if (
                    file_exists($newImageFile)
                ) {

                    unlink($newImageFile);
                }
            }


            return back()
                ->withInput()
                ->withErrors([
                    'error' =>
                    'Something went wrong while updating the listing: '
                        . $e->getMessage(),
                ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Listing
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $seller = $this->getSeller();


        $listing = Listing::where(
            'id',
            $id
        )
            ->where(
                'seller_id',
                $seller->id
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Delete Listing Image
        |--------------------------------------------------------------------------
        */

        if ($listing->image) {

            $imagePath =
                base_path(
                    'uploads/' . $listing->image
                );


            if (
                file_exists($imagePath)
            ) {

                unlink($imagePath);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Listing Documents
        |--------------------------------------------------------------------------
        */

        $documents =
            $listing->documents;


        foreach (
            $documents
            as $document
        ) {

            if (
                $document->document_path &&
                Storage::disk('local')->exists(
                    $document->document_path
                )
            ) {

                Storage::disk('local')->delete(
                    $document->document_path
                );
            }


            $document->delete();
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Listing
        |--------------------------------------------------------------------------
        */

        $listing->delete();


        return redirect()
            ->route(
                'seller.listings.index'
            )
            ->with(
                'success',
                'Listing deleted successfully.'
            );
    }
}
