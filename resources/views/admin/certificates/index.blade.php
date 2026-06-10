@extends('layouts.admin')

@section('title', 'Kelola Sertifikat')

@section('content')
<div class="admin-title-row">
    <div>
        <h1 style="font-size: 2rem;">Kelola Sertifikat & Penghargaan</h1>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Daftar dan kelola semua sertifikasi atau lisensi keahlian yang Anda miliki.</p>
    </div>
    
    <a href="{{ route('admin.certificates.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Sertifikat Baru</a>
</div>

<!-- Certificates Table -->
<div class="admin-card">
    <div class="admin-table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 80px;">Thumbnail</th>
                    <th>Nama Sertifikasi</th>
                    <th>Penerbit (Publisher)</th>
                    <th>Tahun Perolehan</th>
                    <th>File Dokumen</th>
                    <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($certificates as $cert)
                    <tr>
                        <td>
                            <div style="width: 60px; height: 40px; border-radius: var(--border-radius-sm); overflow: hidden; background-color: var(--bg-tertiary);">
                                @if($cert->thumbnail)
                                    <img src="{{ asset('storage/' . $cert->thumbnail) }}" alt="Thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <img src="https://images.unsplash.com/photo-1589330694653-ded6df53f7ec?auto=format&fit=crop&q=80&w=60&h=40" alt="Thumbnail Default" style="width: 100%; height: 100%; object-fit: cover;">
                                @endif
                            </div>
                        </td>
                        <td><strong>{{ $cert->name }}</strong></td>
                        <td>{{ $cert->publisher }}</td>
                        <td><span class="badge badge-info">{{ $cert->year }}</span></td>
                        <td>
                            @if($cert->file_path)
                                <a href="{{ asset('storage/' . $cert->file_path) }}" target="_blank" style="color: var(--accent); font-weight: 600;"><i class="fa-solid fa-file-pdf"></i> Lihat File</a>
                            @else
                                <span style="color: var(--text-secondary); font-size: 0.85rem;">Tidak Ada File</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('admin.certificates.edit', $cert->id) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                
                                <form action="{{ route('admin.certificates.destroy', $cert->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sertifikat ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem; color: #ef4444;" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-secondary); padding: 40px 0;">Belum ada data sertifikat yang disimpan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
