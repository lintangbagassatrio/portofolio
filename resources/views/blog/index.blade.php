@extends('layouts.app')

@section('title', 'Blog & Artikel Terbaru')

@section('content')
<section class="section" style="padding-top: 120px;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 40px;">
            <h5 class="hero-subtitle text-gradient">Blog</h5>
            <h2>Artikel & Catatan Teknologi</h2>
        </div>

        <!-- Search Form & Category Filters -->
        <form action="{{ route('blog') }}" method="GET" style="margin-bottom: 50px;">
            <div class="portfolio-search">
                <input type="text" name="search" class="search-input" placeholder="Cari artikel..." value="{{ request('search') }}">
                <button type="submit" class="search-icon" style="background: none; border: none; cursor: pointer;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>

            <div class="portfolio-filters">
                <a href="{{ route('blog', ['search' => request('search')]) }}" class="filter-btn {{ !request('category') ? 'active' : '' }}">Semua Kategori</a>
                @foreach($categories as $cat)
                    <a href="{{ route('blog', ['category' => $cat->slug, 'search' => request('search')]) }}" class="filter-btn {{ request('category') == $cat->slug ? 'active' : '' }}">{{ $cat->name }}</a>
                @endforeach
            </div>
        </form>

        <!-- Blogs Grid -->
        @if($blogs->count() > 0)
            <div class="blog-grid">
                @foreach($blogs as $blog)
                    <div class="blog-card">
                        <div class="blog-thumbnail">
                            @if($blog->thumbnail)
                                <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="{{ $blog->title }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&q=80&w=400&h=250" alt="Blog Default">
                            @endif
                        </div>
                        <div class="blog-info">
                            <span class="blog-date">
                                {{ $blog->created_at->format('d M Y') }} • 
                                <span class="text-gradient" style="font-weight: 700;">{{ $blog->category->name }}</span>
                            </span>
                            <h4 class="blog-card-title">
                                <a href="{{ route('blog.detail', $blog->slug) }}">{{ $blog->title }}</a>
                            </h4>
                            <p class="blog-card-desc">
                                {{ Str::limit(strip_tags($blog->content), 120) }}
                            </p>
                            <div class="blog-author-info" style="justify-content: space-between;">
                                <span><i class="fa-solid fa-user"></i> {{ $blog->user->name }}</span>
                                <span><i class="fa-solid fa-eye"></i> {{ $blog->views }} views</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination Links -->
            <div style="margin-top: 50px; display: flex; justify-content: center;">
                {{ $blogs->appends(request()->query())->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 60px 0; color: var(--text-secondary);">
                <i class="fa-solid fa-newspaper" style="font-size: 3rem; margin-bottom: 16px; color: var(--border-color);"></i>
                <p>Tidak ada artikel yang ditemukan.</p>
            </div>
        @endif
    </div>
</section>
@endsection
