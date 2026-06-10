<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show profile edit form
     */
    public function index()
    {
        $profile = Profile::firstOrCreate([
            'user_id' => auth()->id()
        ], [
            'full_name' => auth()->user()->name,
            'email' => auth()->user()->email
        ]);

        return view('admin.profile.index', compact('profile'));
    }

    /**
     * Update profile info
     */
    public function update(Request $request)
    {
        $profile = Profile::firstOrCreate([
            'user_id' => auth()->id()
        ]);

        $request->validate([
            'full_name' => 'required|string|max:100',
            'birth_place_date' => 'nullable|string|max:100',
            'education' => 'nullable|string|max:150',
            'address' => 'nullable|string|max:200',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:30',
            'bio' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cv_file' => 'nullable|file|mimes:pdf|max:5120',
            'background' => 'nullable|string',
            'career' => 'nullable|string',
            'interests' => 'nullable|string',
            'goals' => 'nullable|string',
        ]);

        $data = $request->except(['photo', 'cv_file']);

        // Handle Photo Upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($profile->photo) {
                Storage::disk('public')->delete($profile->photo);
            }
            $data['photo'] = $request->file('photo')->store('profiles', 'public');
        }

        // Handle CV File Upload
        if ($request->hasFile('cv_file')) {
            // Delete old CV if exists
            if ($profile->cv_file) {
                Storage::disk('public')->delete($profile->cv_file);
            }
            $data['cv_file'] = $request->file('cv_file')->store('cvs', 'public');
        }

        $profile->update($data);

        ActivityLog::record('profile_update', 'Updated personal profile details');

        return back()->with('success', 'Profil Anda telah berhasil diperbarui.');
    }
}
