@extends('layouts.app')

@section('title', $blog->title . ' - Tech Blog')

@section('content')
<section class="section" style="padding-top: 120px;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 48px; align-items: start;">
            <!-- Article Body -->
            <div>
                <!-- Navigation Breadcrumb -->
                <div style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-secondary);">
                    <a href="{{ route('home') }}">Home</a> / 
                    <a href="{{ route('blog') }}">Blog</a> / 
                    <span style="color: var(--accent); font-weight: 500;">{{ Str::limit($blog->title, 25) }}</span>
                </div>

                <!-- Title & Meta -->
                <h1 style="font-size: 2.5rem; line-height: 1.25; margin-bottom: 16px;">{{ $blog->title }}</h1>
                
                <div style="display: flex; gap: 20px; font-size: 0.88rem; color: var(--text-secondary); margin-bottom: 32px; border-bottom: 1px solid var(--border-color); padding-bottom: 16px; flex-wrap: wrap;">
                    <span><i class="fa-solid fa-calendar" style="color: var(--accent);"></i> {{ $blog->created_at->format('d M Y') }}</span>
                    <span><i class="fa-solid fa-user" style="color: var(--accent);"></i> {{ $blog->user->name }}</span>
                    <span><i class="fa-solid fa-folder" style="color: var(--accent);"></i> {{ $blog->category->name }}</span>
                    <span><i class="fa-solid fa-eye" style="color: var(--accent);"></i> {{ $blog->views }} Views</span>
                </div>

                <!-- Featured Image -->
                <div style="width: 100%; height: 400px; border-radius: var(--border-radius-md); overflow: hidden; margin-bottom: 32px; background-color: var(--bg-tertiary);">
                    @if($blog->thumbnail)
                        <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="{{ $blog->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&q=80&w=800&h=400" alt="Blog Default" style="width: 100%; height: 100%; object-fit: cover;">
                    @endif
                </div>

                <!-- Content -->
                <div style="font-size: 1.1rem; line-height: 1.8; color: var(--text-primary); margin-bottom: 40px; text-align: left; white-space: pre-wrap;">
                    {!! $blog->content !!}
                </div>

                <!-- Tags -->
                @if(is_array($blog->tags) && count($blog->tags) > 0)
                    <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 48px; border-bottom: 1px solid var(--border-color); padding-bottom: 24px;">
                        <strong style="margin-right: 8px; align-self: center; font-size: 0.95rem;">Tags:</strong>
                        @foreach($blog->tags as $tag)
                            <span class="tech-badge" style="padding: 6px 12px; border-radius: 20px;">#{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                <!-- Comments Section -->
                <div style="margin-bottom: 60px;">
                    <h3 style="font-size: 1.5rem; margin-bottom: 24px;"><i class="fa-solid fa-comments text-gradient"></i> Komentar ({{ $blog->approvedComments->count() }})</h3>
                    
                    <!-- Comment list -->
                    <div style="display: flex; flex-direction: column; gap: 20px; margin-bottom: 40px;">
                        @forelse($blog->approvedComments as $comment)
                            <div class="timeline-content" style="text-align: left; border-left: 3px solid var(--accent); padding-left: 20px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                    <strong style="font-size: 1.05rem;">{{ $comment->name }}</strong>
                                    <span style="font-size: 0.8rem; color: var(--text-secondary);">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p style="color: var(--text-secondary); font-size: 0.95rem; line-height: 1.6;">{{ $comment->comment }}</p>
                            </div>
                        @empty
                            <p style="color: var(--text-secondary);">Belum ada komentar. Jadilah yang pertama memberikan tanggapan!</p>
                        @endforelse
                    </div>

                    <!-- Add Comment Form -->
                    <div class="contact-form-card" style="padding: 32px;">
                        <h4 style="font-size: 1.25rem; margin-bottom: 20px;">Tinggalkan Komentar</h4>
                        <form action="{{ route('blog.comment', $blog->id) }}" method="POST">
                            @csrf
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap *</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Nama Anda" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Alamat Email *</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="email@contoh.com" required>
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: 10px;">
                                <label for="comment">Komentar *</label>
                                <textarea name="comment" id="comment" rows="5" class="form-control" placeholder="Tulis tanggapan Anda di sini..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary" style="margin-top: 16px;">Kirim Komentar <i class="fa-solid fa-paper-plane"></i></button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Related Posts -->
                <div class="contact-form-card" style="padding: 24px; text-align: left;">
                    <h3 style="font-size: 1.2rem; margin-bottom: 20px; border-bottom: 2px solid var(--border-color); padding-bottom: 8px;"><i class="fa-solid fa-link text-gradient"></i> Artikel Terkait</h3>
                    <div style="display: flex; flex-direction: column; gap: 20px;">
                        @forelse($relatedBlogs as $relBlog)
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                <a href="{{ route('blog.detail', $relBlog->slug) }}" style="font-weight: 600; font-size: 0.95rem; line-height: 1.4; color: var(--text-primary);" class="portfolio-link">
                                    {{ $relBlog->title }}
                                </a>
                                <span style="font-size: 0.75rem; color: var(--text-secondary);">
                                    {{ $relBlog->created_at->format('d M Y') }}
                                </span>
                            </div>
                        @empty
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">Tidak ada artikel terkait.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
