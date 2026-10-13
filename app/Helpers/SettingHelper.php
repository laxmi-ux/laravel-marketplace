<?php

namespace App\Helpers;

use App\Models\Setting;

class SettingHelper
{
    public static function get(string $key, ?string $default = null): ?string
    {
        return cache()->remember(
            'marketplace_setting_' . $key,
            now()->addMinutes(10),
            fn() => Setting::where('key', $key)->value('value') ?? $default
        );
    }


    public static function clearCache(): void
    {
        foreach (
            [
                'site_name',
                'contact_email',
                'contact_phone',
                'address',
                'logo_path',
            ] as $key
        ) {
            cache()->forget('marketplace_setting_' . $key);
        }
    }
}
