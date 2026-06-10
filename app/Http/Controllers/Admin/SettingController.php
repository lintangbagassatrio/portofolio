<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display settings panel
     */
    public function index()
    {
        $settings = [
            'site_name' => Setting::get('site_name', 'My Portfolio'),
            'site_logo' => Setting::get('site_logo'),
            'site_favicon' => Setting::get('site_favicon'),
            'meta_title' => Setting::get('meta_title'),
            'meta_description' => Setting::get('meta_description'),
            'meta_keywords' => Setting::get('meta_keywords'),
            'social_whatsapp' => Setting::get('social_whatsapp'),
            'social_email' => Setting::get('social_email'),
            'social_linkedin' => Setting::get('social_linkedin'),
            'social_github' => Setting::get('social_github'),
            'social_instagram' => Setting::get('social_instagram'),
            'footer_text' => Setting::get('footer_text', '© 2026 Portfolio. All rights reserved.'),
            'google_analytics' => Setting::get('google_analytics'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings configuration
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:100',
            'site_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:1024',
            'site_favicon' => 'nullable|image|mimes:jpg,jpeg,png,webp,ico|max:512',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:300',
            'social_whatsapp' => 'nullable|string|max:30',
            'social_email' => 'nullable|email|max:100',
            'social_linkedin' => 'nullable|url',
            'social_github' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'footer_text' => 'nullable|string|max:200',
            'google_analytics' => 'nullable|string',
        ]);

        // Simple loop through request inputs and update
        $inputs = $request->except(['_token', 'site_logo', 'site_favicon']);

        foreach ($inputs as $key => $value) {
            Setting::set($key, $value, $key === 'meta_description' || $key === 'google_analytics' ? 'textarea' : 'text');
        }

        // Handle Site Logo Image Upload
        if ($request->hasFile('site_logo')) {
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            $logoPath = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', $logoPath, 'image');
        }

        // Handle Site Favicon Upload
        if ($request->hasFile('site_favicon')) {
            $oldFavicon = Setting::get('site_favicon');
            if ($oldFavicon) {
                Storage::disk('public')->delete($oldFavicon);
            }
            $faviconPath = $request->file('site_favicon')->store('settings', 'public');
            Setting::set('site_favicon', $faviconPath, 'image');
        }

        ActivityLog::record('settings_update', 'Updated website configurations and SEO parameters');

        return back()->with('success', 'Pengaturan website telah berhasil diperbarui.');
    }
}
