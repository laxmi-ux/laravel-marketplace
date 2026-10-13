<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Helpers\SettingHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:150'],
            'contact_email' => ['nullable', 'email', 'max:150'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ]);

        // Save general settings.
        foreach (['site_name', 'contact_email', 'contact_phone', 'address'] as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $validated[$key] ?? null]
            );
        }

        // Upload and save the new logo.
        if ($request->hasFile('logo')) {
            $oldLogo = Setting::where('key', 'logo_path')->value('value');

            $logoPath = $request->file('logo')->store('settings', 'public');

            if (!$logoPath) {
                return back()
                    ->withInput()
                    ->withErrors(['logo' => 'Logo upload failed. Please try again.']);
            }

            Setting::updateOrCreate(
                ['key' => 'logo_path'],
                ['value' => $logoPath]
            );

            // Remove the previous uploaded logo.
            if (
                $oldLogo &&
                $oldLogo !== $logoPath &&
                Storage::disk('public')->exists($oldLogo)
            ) {
                Storage::disk('public')->delete($oldLogo);
            }
        }

        SettingHelper::clearCache();

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
