<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $fileName = Str::random(30) . '.' .
                $image->getClientOriginalExtension();

            $uploadDirectory = base_path('uploads/banners');

            if (!File::exists($uploadDirectory)) {
                File::makeDirectory($uploadDirectory, 0755, true);
            }

            $image->move($uploadDirectory, $fileName);

            $imagePath = 'banners/' . $fileName;
        }

        Banner::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image' => $imagePath,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'status' => $request->has('status'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Banner created successfully.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $imagePath = $banner->image;

        if ($request->hasFile('image')) {

            // Delete old image
            if ($banner->image) {

                $oldImage = base_path(
                    'uploads/' . $banner->image
                );

                if (File::exists($oldImage)) {
                    File::delete($oldImage);
                }
            }

            // Upload new image
            $image = $request->file('image');

            $fileName = Str::random(30) . '.' .
                $image->getClientOriginalExtension();

            $uploadDirectory = base_path('uploads/banners');

            if (!File::exists($uploadDirectory)) {
                File::makeDirectory(
                    $uploadDirectory,
                    0755,
                    true
                );
            }

            $image->move($uploadDirectory, $fileName);

            $imagePath = 'banners/' . $fileName;
        }

        $banner->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image' => $imagePath,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'status' => $request->has('status'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image) {

            $imagePath = base_path(
                'uploads/' . $banner->image
            );

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $banner->delete();

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Banner deleted successfully.');
    }

    public function toggleStatus(Banner $banner)
    {
        $banner->update([
            'status' => !$banner->status,
        ]);

        return back()->with(
            'success',
            'Banner status updated successfully.'
        );
    }
}