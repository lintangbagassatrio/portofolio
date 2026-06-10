<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = PortfolioCategory::all();
        $query = Portfolio::with('category');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        $portfolios = $query->latest()->paginate(10);

        return view('admin.portfolios.index', compact('portfolios', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = PortfolioCategory::all();
        return view('admin.portfolios.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200|unique:portfolios,title',
            'category_id' => 'required|exists:portfolio_categories,id',
            'description' => 'nullable|string',
            'technology_used' => 'nullable|string', // Comma separated string e.g. Laravel, MySQL
            'start_date' => 'nullable|date',
            'status' => 'required|in:completed,in-progress',
            'demo_link' => 'nullable|url',
            'github_link' => 'nullable|url',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_featured' => 'nullable|boolean',
        ]);

        $data = $request->except(['thumbnail', 'technology_used', 'is_featured']);
        $data['slug'] = Str::slug($request->title);
        $data['is_featured'] = $request->has('is_featured') ? true : false;

        // Parse technologies
        $techs = [];
        if ($request->filled('technology_used')) {
            $techs = array_values(array_filter(array_map('trim', explode(',', $request->technology_used))));
        }
        $data['technology_used'] = $techs;

        // Handle Image Thumbnail Upload
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('portfolios', 'public');
        }

        Portfolio::create($data);

        ActivityLog::record('portfolio_create', 'Created project: ' . $request->title);

        return redirect()->route('admin.portfolios.index')->with('success', 'Project baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $categories = PortfolioCategory::all();
        
        // Convert array to comma separated string for easy view editing
        $techString = '';
        if (is_array($portfolio->technology_used)) {
            $techString = implode(', ', $portfolio->technology_used);
        }

        return view('admin.portfolios.edit', compact('portfolio', 'categories', 'techString'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $portfolio = Portfolio::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:200|unique:portfolios,title,' . $id,
            'category_id' => 'required|exists:portfolio_categories,id',
            'description' => 'nullable|string',
            'technology_used' => 'nullable|string',
            'start_date' => 'nullable|date',
            'status' => 'required|in:completed,in-progress',
            'demo_link' => 'nullable|url',
            'github_link' => 'nullable|url',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_featured' => 'nullable|boolean',
        ]);

        $data = $request->except(['thumbnail', 'technology_used', 'is_featured']);
        $data['slug'] = Str::slug($request->title);
        $data['is_featured'] = $request->has('is_featured') ? true : false;

        // Parse technologies
        $techs = [];
        if ($request->filled('technology_used')) {
            $techs = array_values(array_filter(array_map('trim', explode(',', $request->technology_used))));
        }
        $data['technology_used'] = $techs;

        // Handle Image Thumbnail Upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($portfolio->thumbnail) {
                Storage::disk('public')->delete($portfolio->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('portfolios', 'public');
        }

        $portfolio->update($data);

        ActivityLog::record('portfolio_update', 'Updated project: ' . $request->title);

        return redirect()->route('admin.portfolios.index')->with('success', 'Project berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        
        // Delete image
        if ($portfolio->thumbnail) {
            Storage::disk('public')->delete($portfolio->thumbnail);
        }

        $title = $portfolio->title;
        $portfolio->delete();

        ActivityLog::record('portfolio_delete', 'Deleted project: ' . $title);

        return redirect()->route('admin.portfolios.index')->with('success', 'Project berhasil dihapus.');
    }
}
