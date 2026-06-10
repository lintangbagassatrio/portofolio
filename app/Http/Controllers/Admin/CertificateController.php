<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $certificates = Certificate::orderBy('year', 'desc')->get();
        return view('admin.certificates.index', compact('certificates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.certificates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'publisher' => 'required|string|max:150',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'file_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'description' => 'nullable|string',
        ]);

        $data = $request->except(['thumbnail', 'file_path']);

        // Handle Image Thumbnail Upload
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('certificates/thumbnails', 'public');
        }

        // Handle PDF/Image File Upload
        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('certificates/files', 'public');
        }

        Certificate::create($data);

        ActivityLog::record('certificate_create', 'Created certificate: ' . $request->name);

        return redirect()->route('admin.certificates.index')->with('success', 'Sertifikat baru berhasil disimpan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $certificate = Certificate::findOrFail($id);
        return view('admin.certificates.edit', compact('certificate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $certificate = Certificate::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:200',
            'publisher' => 'required|string|max:150',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'file_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'description' => 'nullable|string',
        ]);

        $data = $request->except(['thumbnail', 'file_path']);

        // Handle Thumbnail Upload
        if ($request->hasFile('thumbnail')) {
            if ($certificate->thumbnail) {
                Storage::disk('public')->delete($certificate->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('certificates/thumbnails', 'public');
        }

        // Handle Certificate File Upload
        if ($request->hasFile('file_path')) {
            if ($certificate->file_path) {
                Storage::disk('public')->delete($certificate->file_path);
            }
            $data['file_path'] = $request->file('file_path')->store('certificates/files', 'public');
        }

        $certificate->update($data);

        ActivityLog::record('certificate_update', 'Updated certificate: ' . $request->name);

        return redirect()->route('admin.certificates.index')->with('success', 'Sertifikat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $certificate = Certificate::findOrFail($id);
        
        // Delete files
        if ($certificate->thumbnail) {
            Storage::disk('public')->delete($certificate->thumbnail);
        }
        if ($certificate->file_path) {
            Storage::disk('public')->delete($certificate->file_path);
        }

        $name = $certificate->name;
        $certificate->delete();

        ActivityLog::record('certificate_delete', 'Deleted certificate: ' . $name);

        return redirect()->route('admin.certificates.index')->with('success', 'Sertifikat berhasil dihapus.');
    }
}
