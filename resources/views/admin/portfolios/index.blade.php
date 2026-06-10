@extends('layouts.admin')

@section('title', 'Kelola Portofolio')

@section('content')
<div class="admin-title-row">
    <div>
        <h1 style="font-size: 2rem;">Kelola Project Portofolio</h1>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Daftar dan kelola semua project karya/portofolio yang telah Anda selesaikan.</p>
    </div>
    
    <a href="{{ route('admin.portfolios.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Project Baru</a>
</div>

<!-- Filters & Search Card -->
<div class="admin-card">
    <form action="{{ route('admin.portfolios.index') }}" method="GET" style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap;">
        <div class="form-group" style="flex-grow: 1; margin-bottom: 0; min-width: 250px;">
            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan judul..." value="{{ request('search') }}">
        </div>
        
        <div class="form-group" style="width: 200px; margin-bottom: 0;">
            <select name="category" class="form-control">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        
        <button type="submit" class="btn btn-secondary" style="height: 46px;"><i class="fa-solid fa-filter"></i> Filter</button>
        <a href="{{ route('admin.portfolios.index') }}" class="btn btn-secondary" style="height: 46px; display: inline-flex; align-items: center; justify-content: center;">Reset</a>
    </form>
</div>

<!-- Portfolios Table -->
<div class="admin-card">
    <div class="admin-table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 70px;">Tampilan</th>
                    <th>Judul Project</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Teknologi</th>
                    <th>Featured</th>
                    <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($portfolios as $portfolio)
                    <tr>
                        <td>
                            <div style="width: 60px; height: 40px; border-radius: var(--border-radius-sm); overflow: hidden; background-color: var(--bg-tertiary);">
                                @if($portfolio->thumbnail)
                                    <img src="{{ asset('storage/' . $portfolio->thumbnail) }}" alt="Thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <img src="https://images.unsplash.com/photo-1531403009284-440f080d1e12?auto=format&fit=crop&q=80&w=60&h=40" alt="Thumbnail Default" style="width: 100%; height: 100%; object-fit: cover;">
                                @endif
                            </div>
                        </td>
                        <td>
                            <strong>{{ $portfolio->title }}</strong>
                            <div style="font-size: 0.75rem; color: var(--text-secondary);">Mulai: {{ $portfolio->start_date ? $portfolio->start_date->format('d M Y') : '-' }}</div>
                        </td>
                        <td>{{ $portfolio->category->name }}</td>
                        <td>
                            @if($portfolio->status == 'completed')
                                <span class="badge badge-success">Selesai</span>
                            @else
                                <span class="badge badge-warning">Dalam Proses</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; flex-wrap: wrap; gap: 4px; max-width: 250px;">
                                @if(is_array($portfolio->technology_used))
                                    @foreach($portfolio->technology_used as $tech)
                                        <span class="tech-badge" style="font-size: 0.7rem; padding: 2px 6px;">{{ $tech }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($portfolio->is_featured)
                                <span class="badge badge-info"><i class="fa-solid fa-star"></i> Ya</span>
                            @else
                                <span class="badge" style="background-color: var(--border-color); color: var(--text-secondary);">Tidak</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('admin.portfolios.edit', $portfolio->id) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                
                                <form action="{{ route('admin.portfolios.destroy', $portfolio->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus project ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem; color: #ef4444;" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-secondary); padding: 40px 0;">Belum ada project portofolio yang ditambahkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
        {{ $portfolios->appends(request()->query())->links() }}
    </div>
</div>
@endsection
