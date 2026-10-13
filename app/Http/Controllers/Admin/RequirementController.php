<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Requirement;
use Illuminate\Http\Request;

class RequirementController extends Controller
{
    /**
     * Display all general requirements.
     */
    public function index(Request $request)
    {
        $query = Requirement::with('user')
            ->where('requirement_source', 'general')
            ->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('requirement_type', 'like', "%{$search}%")
                    ->orWhere('delivery_location', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requirements = $query->paginate(10)->withQueryString();

        return view(
            'admin.requirements.index',
            compact('requirements')
        );
    }

    /**
     * Display requirement details.
     */
    public function show($id)
    {
        $requirement = Requirement::with([
            'user',
            'listing',
            'seller',
        ])->findOrFail($id);

        return view(
            'admin.requirements.show',
            compact('requirement')
        );
    }
}