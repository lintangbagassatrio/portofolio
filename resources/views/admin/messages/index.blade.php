@extends('layouts.admin')

@section('title', 'Kelola Pesan')

@section('content')
<div class="admin-title-row">
    <div>
        <h1 style="font-size: 2rem;">Kelola Pesan Masuk (Inbox)</h1>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Daftar pesan dan pertanyaan yang dikirimkan oleh pengunjung melalui formulir kontak.</p>
    </div>
    
    <div style="display: flex; gap: 12px;">
        <a href="{{ route('admin.messages.export.csv') }}" class="btn btn-secondary"><i class="fa-solid fa-file-excel" style="color: #107c41;"></i> Ekspor ke Excel (CSV)</a>
        <a href="{{ route('admin.messages.export.pdf') }}" target="_blank" class="btn btn-secondary"><i class="fa-solid fa-file-pdf" style="color: #ef4444;"></i> Cetak PDF</a>
    </div>
</div>

<!-- Messages List -->
<div class="admin-card">
    <div class="admin-table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 150px;">Tanggal Masuk</th>
                    <th>Nama Pengirim</th>
                    <th>Subjek Pesan</th>
                    <th>Isi Pesan</th>
                    <th style="width: 130px;">Status</th>
                    <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                    <tr style="@if(!$msg->is_read) font-weight: 600; background-color: rgba(99, 102, 241, 0.02); @endif">
                        <td style="white-space: nowrap;">{{ $msg->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <div>{{ $msg->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary); font-weight: normal;">{{ $msg->email }}</div>
                        </td>
                        <td>{{ $msg->subject }}</td>
                        <td>
                            <div style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: normal;" title="{{ $msg->message }}">
                                {{ $msg->message }}
                            </div>
                        </td>
                        <td>
                            @if($msg->is_read)
                                <span class="badge badge-success">Dibaca</span>
                            @else
                                <span class="badge badge-warning">Belum Dibaca</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                @if(!$msg->is_read)
                                    <form action="{{ route('admin.messages.read', $msg->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;" title="Tandai Dibaca"><i class="fa-solid fa-check"></i></button>
                                    </form>
                                @endif
                                
                                <button class="btn btn-secondary btn-view-msg" style="padding: 6px 12px; font-size: 0.8rem;" 
                                    data-name="{{ $msg->name }}" 
                                    data-email="{{ $msg->email }}" 
                                    data-date="{{ $msg->created_at->format('d M Y H:i') }}" 
                                    data-subject="{{ $msg->subject }}" 
                                    data-message="{{ $msg->message }}"
                                    title="Detail Pesan"><i class="fa-solid fa-eye"></i></button>

                                <form action="{{ route('admin.messages.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('Hapus pesan dari {{ $msg->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem; color: #ef4444;" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-secondary); padding: 40px 0;">Belum ada pesan masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
        {{ $messages->links() }}
    </div>
</div>

<!-- Message Detail Modal -->
<div class="modal-backdrop" id="msgModal">
    <div class="modal-card" style="max-width: 550px;">
        <div class="modal-header">
            <h3>Detail Pesan Masuk</h3>
            <button class="modal-close" id="msgModalClose"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body" style="text-align: left;">
            <div style="display: flex; flex-direction: column; gap: 12px; border-bottom: 1px solid var(--border-color); padding-bottom: 16px;">
                <div><strong>Dari:</strong> <span id="msgModalSender">-</span></div>
                <div><strong>Email:</strong> <span id="msgModalEmail">-</span></div>
                <div><strong>Tanggal:</strong> <span id="msgModalDate">-</span></div>
            </div>
            
            <div style="margin-top: 10px;">
                <h4 style="margin-bottom: 6px;">Subjek:</h4>
                <p id="msgModalSubject" style="font-weight: 600; font-size: 1rem; color: var(--text-primary);"></p>
            </div>

            <div style="margin-top: 10px;">
                <h4 style="margin-bottom: 6px;">Pesan:</h4>
                <p id="msgModalContent" style="color: var(--text-secondary); font-size: 0.95rem; line-height: 1.6; white-space: pre-wrap; background-color: var(--bg-tertiary); padding: 16px; border-radius: var(--border-radius-sm);"></p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('msgModal');
        const modalClose = document.getElementById('msgModalClose');

        document.querySelectorAll('.btn-view-msg').forEach(btn => {
            btn.addEventListener('click', () => {
                const name = btn.getAttribute('data-name');
                const email = btn.getAttribute('data-email');
                const date = btn.getAttribute('data-date');
                const subject = btn.getAttribute('data-subject');
                const message = btn.getAttribute('data-message');

                document.getElementById('msgModalSender').innerText = name;
                document.getElementById('msgModalEmail').innerText = email;
                document.getElementById('msgModalDate').innerText = date;
                document.getElementById('msgModalSubject').innerText = subject;
                document.getElementById('msgModalContent').innerText = message;

                modal.classList.add('show');
            });
        });

        modalClose.addEventListener('click', () => {
            modal.classList.remove('show');
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('show');
            }
        });
    });
</script>
@endsection
