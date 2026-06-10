<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::with(['category', 'user'])->latest()->paginate(10);
        
        // Fetch comments requiring moderation/review
        $comments = BlogComment::with('blog')->latest()->take(20)->get();

        return view('admin.blogs.index', compact('blogs', 'comments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = BlogCategory::all();
        return view('admin.blogs.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200|unique:blogs,title',
            'category_id' => 'required|exists:blog_categories,id',
            'content' => 'required|string',
            'tags' => 'nullable|string', // Comma separated e.g. php, laravel
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except(['thumbnail', 'tags']);
        $data['slug'] = Str::slug($request->title);
        $data['user_id'] = auth()->id();

        // Parse tags
        $tags = [];
        if ($request->filled('tags')) {
            $tags = array_values(array_filter(array_map('trim', explode(',', $request->tags))));
        }
        $data['tags'] = $tags;

        // Handle Thumbnail Upload
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('blogs', 'public');
        }

        Blog::create($data);

        ActivityLog::record('blog_create', 'Created blog post: ' . $request->title);

        return redirect()->route('admin.blogs.index')->with('success', 'Artikel baru berhasil diterbitkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        $categories = BlogCategory::all();
        
        // Convert tags array to comma separated string
        $tagString = '';
        if (is_array($blog->tags)) {
            $tagString = implode(', ', $blog->tags);
        }

        return view('admin.blogs.edit', compact('blog', 'categories', 'tagString'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:200|unique:blogs,title,' . $id,
            'category_id' => 'required|exists:blog_categories,id',
            'content' => 'required|string',
            'tags' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except(['thumbnail', 'tags']);
        $data['slug'] = Str::slug($request->title);

        // Parse tags
        $tags = [];
        if ($request->filled('tags')) {
            $tags = array_values(array_filter(array_map('trim', explode(',', $request->tags))));
        }
        $data['tags'] = $tags;

        // Handle Thumbnail Upload
        if ($request->hasFile('thumbnail')) {
            if ($blog->thumbnail) {
                Storage::disk('public')->delete($blog->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('blogs', 'public');
        }

        $blog->update($data);

        ActivityLog::record('blog_update', 'Updated blog post: ' . $request->title);

        return redirect()->route('admin.blogs.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        
        // Delete image
        if ($blog->thumbnail) {
            Storage::disk('public')->delete($blog->thumbnail);
        }

        $title = $blog->title;
        $blog->delete();

        ActivityLog::record('blog_delete', 'Deleted blog post: ' . $title);

        return redirect()->route('admin.blogs.index')->with('success', 'Artikel berhasil dihapus.');
    }

    /**
     * Approve a blog comment
     */
    public function approveComment($id)
    {
        $comment = BlogComment::findOrFail($id);
        $comment->update(['is_approved' => true]);

        ActivityLog::record('comment_approve', 'Approved blog comment by ' . $comment->name);

        return back()->with('success', 'Komentar telah disetujui.');
    }

    /**
     * Delete a blog comment
     */
    public function deleteComment($id)
    {
        $comment = BlogComment::findOrFail($id);
        $name = $comment->name;
        $comment->delete();

        ActivityLog::record('comment_delete', 'Deleted blog comment by ' . $name);

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
