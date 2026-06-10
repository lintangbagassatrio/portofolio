<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Skill;
use App\Models\PortfolioCategory;
use App\Models\Portfolio;
use App\Models\Experience;
use App\Models\Certificate;
use App\Models\BlogCategory;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Public Home Page
     */
    public function index()
    {
        $profile = Profile::first();
        
        // Stats
        $stats = [
            'projects' => Portfolio::where('status', 'completed')->count(),
            'experience' => Experience::whereIn('type', ['work', 'freelance'])->count(),
            'certificates' => Certificate::count(),
            'skills' => Skill::count(),
        ];

        // Highlights
        $featuredPortfolios = Portfolio::with('category')->where('is_featured', true)->latest()->take(3)->get();
        $latestExperience = Experience::orderBy('start_date', 'desc')->first();
        $latestBlog = Blog::with('category')->where('status', 'published')->latest()->first();

        // Technical skills highlight
        $skills = Skill::where('category', 'technical')->orderBy('level', 'desc')->take(6)->get();

        return view('home', compact('profile', 'stats', 'featuredPortfolios', 'latestExperience', 'latestBlog', 'skills'));
    }

    /**
     * Public About Me Page
     */
    public function about()
    {
        $profile = Profile::first();
        $education = Experience::where('type', 'organization')->orWhere('title', 'like', '%student%')->orderBy('start_date', 'desc')->get();
        
        // Split experiences:
        $workExperiences = Experience::whereIn('type', ['work', 'internship', 'freelance'])->orderBy('start_date', 'desc')->get();
        $educationTimeline = Experience::where('type', 'organization')->orderBy('start_date', 'desc')->get(); // we can use specific types or keywords
        
        $certificates = Certificate::orderBy('year', 'desc')->get();

        return view('about', compact('profile', 'workExperiences', 'educationTimeline', 'certificates'));
    }

    /**
     * Public Portfolio Page (with search & filters)
     */
    public function portfolio(Request $request)
    {
        $categories = PortfolioCategory::all();
        $query = Portfolio::with('category');

        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $portfolios = $query->latest()->paginate(6);

        return view('portfolio', compact('portfolios', 'categories'));
    }

    /**
     * Public Portfolio Detail
     */
    public function portfolioDetail($slug)
    {
        $portfolio = Portfolio::with('category')->where('slug', $slug)->firstOrFail();
        return response()->json($portfolio);
    }

    /**
     * Public Skills Page
     */
    public function skills()
    {
        $technicalSkills = Skill::where('category', 'technical')->orderBy('level', 'desc')->get();
        $softSkills = Skill::where('category', 'soft')->orderBy('level', 'desc')->get();

        return view('skills', compact('technicalSkills', 'softSkills'));
    }

    /**
     * Public Experiences Page
     */
    public function experiences()
    {
        // Group experiences by type
        $experiences = Experience::orderBy('start_date', 'desc')->get();
        return view('experiences', compact('experiences'));
    }

    /**
     * Public Certificates Page
     */
    public function certificates()
    {
        $certificates = Certificate::orderBy('year', 'desc')->get();
        return view('certificates', compact('certificates'));
    }

    /**
     * Public Blog List
     */
    public function blog(Request $request)
    {
        $categories = BlogCategory::all();
        $query = Blog::with(['category', 'user'])->where('status', 'published');

        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $blogs = $query->latest()->paginate(6);

        return view('blog.index', compact('blogs', 'categories'));
    }

    /**
     * Public Blog Detail
     */
    public function blogDetail($slug)
    {
        $blog = Blog::with(['category', 'user', 'approvedComments'])->where('slug', $slug)->firstOrFail();
        
        // Increment views
        $blog->increment('views');

        // Fetch related blogs
        $relatedBlogs = Blog::where('category_id', $blog->category_id)
            ->where('id', '!=', $blog->id)
            ->where('status', 'published')
            ->take(3)->get();

        return view('blog.detail', compact('blog', 'relatedBlogs'));
    }

    /**
     * Store Comment for Blog
     */
    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'comment' => 'required|string|max:1000',
        ]);

        BlogComment::create([
            'blog_id' => $id,
            'name' => $request->name,
            'email' => $request->email,
            'comment' => $request->comment,
            'is_approved' => true, // Auto-approve in default mode
        ]);

        ActivityLog::record('blog_comment', 'New comment submitted by ' . $request->name);

        return back()->with('success', 'Your comment has been posted successfully!');
    }

    /**
     * Public Contact Page
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Store Contact Message
     */
    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_read' => false,
        ]);

        ActivityLog::record('contact_form', 'Contact message sent by ' . $request->name . ' - ' . $request->subject);

        return back()->with('success', 'Thank you! Your message has been sent successfully. We will get back to you soon.');
    }
}
