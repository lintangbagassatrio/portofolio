@extends('layouts.admin')

@section('title', 'Kelola Blog')

@section('content')
<div class="admin-title-row">
    <div>
        <h1 style="font-size: 2rem;">Kelola Blog & Artikel</h1>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Tulis artikel baru, kelola artikel yang dipublikasikan, serta moderasi komentar pengunjung.</p>
    </div>
    
    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary"><i class="fa-solid fa-pencil"></i> Tulis Artikel Baru</a>
</div>

<div class="admin-form-split" style="grid-template-columns: 1.3fr 0.7fr;">
    <!-- Blog Posts List -->
    <div class="admin-card">
        <h3 style="margin-bottom: 20px;"><i class="fa-solid fa-newspaper text-gradient"></i> Daftar Artikel</h3>
        
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">Thumbnail</th>
                        <th>Judul Artikel</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th style="width: 120px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $blog)
                        <tr>
                            <td>
                                <div style="width: 60px; height: 40px; border-radius: var(--border-radius-sm); overflow: hidden; background-color: var(--bg-tertiary);">
                                    @if($blog->thumbnail)
                                        <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="Thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&q=80&w=60&h=40" alt="Thumbnail Default" style="width: 100%; height: 100%; object-fit: cover;">
                                    @endif
                                </div>
                            </td>
                            <td>
                                <strong>{{ $blog->title }}</strong>
                                <div style="font-size: 0.75rem; color: var(--text-secondary);">Oleh: {{ $blog->user->name }} • {{ $blog->created_at->format('d M Y') }}</div>
                            </td>
                            <td>{{ $blog->category->name }}</td>
                            <td>
                                @if($blog->status == 'published')
                                    <span class="badge badge-success">Terbit</span>
                                @else
                                    <span class="badge" style="background-color: var(--border-color); color: var(--text-secondary);">Draft</span>
                                @endif
                            </td>
                            <td>{{ $blog->views }}</td>
                            <td>
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-secondary" style="padding: 6px 10px; font-size: 0.75rem;" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                    
                                    <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-secondary" style="padding: 6px 10px; font-size: 0.75rem; color: #ef4444;" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-secondary); padding: 32px 0;">Belum ada artikel yang diterbitkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
            {{ $blogs->links() }}
        </div>
    </div>

    <!-- Comments Moderation List -->
    <div class="admin-card">
        <h3 style="margin-bottom: 20px;"><i class="fa-solid fa-comments text-gradient"></i> Moderasi Komentar</h3>
        
        <div style="display: flex; flex-direction: column; gap: 16px;">
            @forelse($comments as $comment)
                <div class="timeline-content" style="text-align: left; padding: 16px; border-left: 3px solid {{ $comment->is_approved ? 'var(--accent)' : '#fbbf24' }};">
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; margin-bottom: 6px; flex-wrap: wrap; gap: 4px;">
                        <strong>{{ $comment->name }}</strong>
                        <span style="color: var(--text-secondary);">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    
                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 8px;">
                        Pada: <a href="{{ route('blog.detail', $comment->blog->slug) }}" target="_blank" style="color: var(--accent); font-weight: 500;">{{ Str::limit($comment->blog->title, 30) }}</a>
                    </div>
                    
                    <p style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.4; background-color: var(--bg-tertiary); padding: 8px; border-radius: var(--border-radius-sm);">
                        {{ $comment->comment }}
                    </p>
                    
                    <div style="display: flex; gap: 8px; margin-top: 12px; justify-content: flex-end;">
                        @if(!$comment->is_approved)
                            <form action="{{ route('admin.blogs.comments.approve', $comment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary" style="padding: 4px 10px; font-size: 0.72rem;"><i class="fa-solid fa-check"></i> Setujui</button>
                            </form>
                        @endif
                        
                        <form action="{{ route('admin.blogs.comments.delete', $comment->id) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary" style="padding: 4px 10px; font-size: 0.72rem; color: #ef4444;"><i class="fa-solid fa-trash"></i> Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <p style="text-align: center; color: var(--text-secondary); padding: 20px 0;">Belum ada komentar masuk.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
