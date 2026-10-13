<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminLocationController extends Controller
{
    /**
     * Display all locations.
     */
    public function index()
    {
        $locations = Location::latest()->paginate(10);

        return view('admin.locations.index', compact('locations'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.locations.create');
    }

    /**
     * Store location.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Make slug unique
        $baseSlug = $validated['slug'];
        $counter = 1;

        while (Location::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $baseSlug . '-' . $counter;
            $counter++;
        }

        $validated['status'] = $request->has('status') ? 1 : 0;

        if ($request->hasFile('image')) {
            $uploadPath = base_path('uploads/locations');

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $image = $request->file('image');

            $fileName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

            $image->move($uploadPath, $fileName);

            $validated['image'] = $fileName;
        }

        Location::create($validated);

        return redirect()
            ->route('admin.locations.index')
            ->with('success', 'Location added successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    /**
     * Update location.
     */
    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $baseSlug = $validated['slug'];
        $counter = 1;

        while (
            Location::where('slug', $validated['slug'])
                ->where('id', '!=', $location->id)
                ->exists()
        ) {
            $validated['slug'] = $baseSlug . '-' . $counter;
            $counter++;
        }

        $validated['status'] = $request->has('status') ? 1 : 0;

        if ($request->hasFile('image')) {
            $uploadPath = base_path('uploads/locations');

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Delete old image
            if (
                $location->image &&
                file_exists($uploadPath . '/' . $location->image)
            ) {
                unlink($uploadPath . '/' . $location->image);
            }

            $image = $request->file('image');

            $fileName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

            $image->move($uploadPath, $fileName);

            $validated['image'] = $fileName;
        }

        $location->update($validated);

        return redirect()
            ->route('admin.locations.index')
            ->with('success', 'Location updated successfully.');
    }

    /**
     * Delete location.
     */
    public function destroy(Location $location)
    {
        $uploadPath = base_path('uploads/locations');

        if (
            $location->image &&
            file_exists($uploadPath . '/' . $location->image)
        ) {
            unlink($uploadPath . '/' . $location->image);
        }

        $location->delete();

        return redirect()
            ->route('admin.locations.index')
            ->with('success', 'Location deleted successfully.');
    }

    /**
     * Toggle status.
     */
    public function toggleStatus(Location $location)
    {
        $location->update([
            'status' => !$location->status,
        ]);

        return back()->with('success', 'Location status updated.');
    }
}