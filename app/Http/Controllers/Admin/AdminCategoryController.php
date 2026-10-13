<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Listing;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminCategoryController extends Controller
{
    /**
     * Display categories.
     */
    public function index(Request $request)
    {
        $query = Category::withCount('subcategories');

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slugs', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $categories = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show category creation form.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Generate a unique category slug.
     */
    private function uniqueCategorySlug(string $name): string
    {
        $baseSlug = Str::slug($name) ?: 'category';
        $slug = $baseSlug;
        $counter = 1;

        while (Category::where('slugs', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }

    /**
     * Generate a unique subcategory slug.
     */
    private function uniqueSubcategorySlug(
        string $name,
        array &$usedSlugs
    ): string {
        $baseSlug = Str::slug($name) ?: 'subcategory';
        $slug = $baseSlug;
        $counter = 1;

        while (
            in_array($slug, $usedSlugs, true) ||
            Subcategory::where('slug', $slug)->exists()
        ) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $usedSlugs[] = $slug;

        return $slug;
    }

    /**
     * Store a category and its subcategories.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name',
            ],
            'slugs' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|url|max:2048',
            'status' => 'required|boolean',
            'subcategories' => 'nullable|array',
            'subcategories.*' => 'nullable|string|max:255',
        ]);

        try {
            $savedCount = DB::transaction(function () use ($validated) {
                // Save category.
                $category = new Category();
                $category->name = $validated['name'];

                $enteredSlug = trim($validated['slugs'] ?? '');

                $category->slugs = $this->uniqueCategorySlug(
                    $enteredSlug !== ''
                        ? $enteredSlug
                        : $validated['name']
                );

                $category->description = $validated['description'] ?? null;
                $category->image = $validated['image'] ?? null;
                $category->status = (bool) $validated['status'];
                $category->save();

                // Save subcategories.
                $savedCount = 0;
                $usedSlugs = [];
                $seenNames = [];

                foreach ($validated['subcategories'] ?? [] as $name) {
                    $name = trim((string) $name);

                    if ($name === '') {
                        continue;
                    }

                    // Avoid duplicate names in this submission.
                    $nameKey = mb_strtolower($name);

                    if (in_array($nameKey, $seenNames, true)) {
                        continue;
                    }

                    $seenNames[] = $nameKey;

                    $subcategory = new Subcategory();
                    $subcategory->category_id = $category->id;
                    $subcategory->name = $name;
                    $subcategory->slug = $this->uniqueSubcategorySlug(
                        $name,
                        $usedSlugs
                    );
                    $subcategory->status = (bool) $validated['status'];
                    $subcategory->save();

                    $savedCount++;
                }

                return $savedCount;
            });

            return redirect()
                ->route('admin.categories.index')
                ->with(
                    'success',
                    'Category saved successfully. Subcategories added: '
                    . $savedCount
                );

        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to save category and subcategories. '
                    . 'Please check the Laravel log for details.'
                );
        }
    }

    /**
     * Show category edit form.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update category details.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
            'slugs' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('categories', 'slugs')->ignore($category->id),
            ],
            'description' => 'nullable|string',
            'image' => 'nullable|url|max:2048',
            'status' => 'required|boolean',
        ]);

        $requestedSlug = trim($validated['slugs'] ?? '');
        $baseSlug = Str::slug(
            $requestedSlug !== '' ? $requestedSlug : $validated['name']
        ) ?: 'category';

        $slug = $baseSlug;
        $counter = 1;

        while (
            Category::where('slugs', $slug)
                ->where('id', '!=', $category->id)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $category->name = $validated['name'];
        $category->slugs = $slug;
        $category->description = $validated['description'] ?? null;
        $category->image = $validated['image'] ?? null;
        $category->status = (bool) $validated['status'];
        $category->save();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Delete category and its subcategories if it has no listings.
     */
    public function destroy(Category $category)
    {
        if (Listing::where('category_id', $category->id)->exists()) {
            return back()->with(
                'error',
                'This category has listings. Deactivate it instead or remove its listings first.'
            );
        }

        try {
            DB::transaction(function () use ($category) {
                $category->subcategories()->delete();
                $category->delete();
            });

            return redirect()
                ->route('admin.categories.index')
                ->with(
                    'success',
                    'Category and its subcategories deleted successfully.'
                );

        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Unable to delete this category. Please check the application logs.'
            );
        }
    }

    /**
     * Activate or deactivate a category.
     */
    public function toggleStatus(Category $category)
    {
        $category->status = ! $category->status;
        $category->save();

        return back()->with(
            'success',
            $category->status
                ? 'Category activated successfully.'
                : 'Category deactivated successfully.'
        );
    }
}
