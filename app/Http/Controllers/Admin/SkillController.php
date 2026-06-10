<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skills = Skill::orderBy('category')->orderBy('level', 'desc')->get();
        return view('admin.skills.index', compact('skills'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:skills,name',
            'category' => 'required|in:technical,soft',
            'level' => 'required|integer|min:1|max:100',
        ]);

        Skill::create($request->all());

        ActivityLog::record('skill_create', 'Added skill: ' . $request->name . ' (' . $request->category . ')');

        return back()->with('success', 'Skill baru berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $skill = Skill::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:50|unique:skills,name,' . $id,
            'category' => 'required|in:technical,soft',
            'level' => 'required|integer|min:1|max:100',
        ]);

        $skill->update($request->all());

        ActivityLog::record('skill_update', 'Updated skill: ' . $request->name);

        return back()->with('success', 'Skill berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);
        $name = $skill->name;
        $skill->delete();

        ActivityLog::record('skill_delete', 'Deleted skill: ' . $name);

        return back()->with('success', 'Skill berhasil dihapus.');
    }
}
