@extends('layouts.admin')

@section('title', 'Kelola Pengalaman')

@section('content')
<div class="admin-title-row">
    <div>
        <h1 style="font-size: 2rem;">Kelola Pengalaman & Timeline</h1>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Daftar dan kelola riwayat pekerjaan, magang, proyek freelance, atau organisasi Anda.</p>
    </div>
    
    <a href="{{ route('admin.experiences.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Pengalaman Baru</a>
</div>

<!-- Experiences Table -->
<div class="admin-card">
    <div class="admin-table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Jabatan / Posisi</th>
                    <th>Instansi / Perusahaan</th>
                    <th>Kategori</th>
                    <th>Periode</th>
                    <th>Keterangan Singkat</th>
                    <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($experiences as $exp)
                    <tr>
                        <td><strong>{{ $exp->title }}</strong></td>
                        <td>{{ $exp->company }}</td>
                        <td>
                            @if($exp->type == 'work')
                                <span class="badge badge-info">Pekerjaan</span>
                            @elseif($exp->type == 'internship')
                                <span class="badge badge-success">Magang</span>
                            @elseif($exp->type == 'freelance')
                                <span class="badge badge-warning">Freelance</span>
                            @else
                                <span class="badge" style="background-color: var(--border-color); color: var(--text-secondary);">Organisasi</span>
                            @endif
                        </td>
                        <td style="white-space: nowrap;">
                            {{ $exp->start_date->format('M Y') }} - 
                            {{ $exp->is_current ? 'Sekarang' : ($exp->end_date ? $exp->end_date->format('M Y') : '-') }}
                        </td>
                        <td>{{ Str::limit(strip_tags($exp->description), 80) }}</td>
                        <td>
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('admin.experiences.edit', $exp->id) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                
                                <form action="{{ route('admin.experiences.destroy', $exp->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem; color: #ef4444;" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-secondary); padding: 40px 0;">Belum ada riwayat pengalaman.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
