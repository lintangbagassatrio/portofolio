<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $experiences = Experience::orderBy('start_date', 'desc')->get();
        return view('admin.experiences.index', compact('experiences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.experiences.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'company' => 'required|string|max:150',
            'type' => 'required|in:work,internship,freelance,organization',
            'start_date' => 'required|date',
            'end_date' => 'nullable|required_without:is_current|date|after_or_equal:start_date',
            'is_current' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['is_current'] = $request->has('is_current') ? true : false;
        
        if ($data['is_current']) {
            $data['end_date'] = null;
        }

        Experience::create($data);

        ActivityLog::record('experience_create', 'Created experience: ' . $request->title . ' at ' . $request->company);

        return redirect()->route('admin.experiences.index')->with('success', 'Riwayat pengalaman berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $experience = Experience::findOrFail($id);
        return view('admin.experiences.edit', compact('experience'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $experience = Experience::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:150',
            'company' => 'required|string|max:150',
            'type' => 'required|in:work,internship,freelance,organization',
            'start_date' => 'required|date',
            'end_date' => 'nullable|required_without:is_current|date|after_or_equal:start_date',
            'is_current' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['is_current'] = $request->has('is_current') ? true : false;
        
        if ($data['is_current']) {
            $data['end_date'] = null;
        }

        $experience->update($data);

        ActivityLog::record('experience_update', 'Updated experience: ' . $request->title);

        return redirect()->route('admin.experiences.index')->with('success', 'Riwayat pengalaman berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $experience = Experience::findOrFail($id);
        $title = $experience->title;
        $experience->delete();

        ActivityLog::record('experience_delete', 'Deleted experience: ' . $title);

        return redirect()->route('admin.experiences.index')->with('success', 'Riwayat pengalaman berhasil dihapus.');
    }
}
