@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="admin-title-row">
    <div>
        <h1 style="font-size: 2rem;">Ringkasan Statistik</h1>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Berikut adalah ikhtisar performa website portofolio Anda.</p>
    </div>
    
    <div style="display: flex; gap: 12px;">
        @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.backup') }}" class="btn btn-primary"><i class="fa-solid fa-database"></i> Backup Database SQL</a>
        @endif
        <a href="{{ route('admin.logs') }}" class="btn btn-secondary"><i class="fa-solid fa-file-csv"></i> Unduh Log Aktivitas</a>
    </div>
</div>

<!-- Stats Counter Grid -->
<div class="dashboard-stats-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background-color: rgba(16, 185, 129, 0.15); color: #34d399;">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="admin-stat-info">
            <span class="admin-stat-value">{{ $stats['visitors'] }}</span>
            <span class="admin-stat-label">Total Pengunjung</span>
        </div>
    </div>
    
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background-color: rgba(16, 185, 129, 0.15); color: #34d399;">
            <i class="fa-solid fa-briefcase"></i>
        </div>
        <div class="admin-stat-info">
            <span class="admin-stat-value">{{ $stats['projects'] }}</span>
            <span class="admin-stat-label">Total Project</span>
        </div>
    </div>
    
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background-color: rgba(245, 158, 11, 0.15); color: #fbbf24;">
            <i class="fa-solid fa-newspaper"></i>
        </div>
        <div class="admin-stat-info">
            <span class="admin-stat-value">{{ $stats['articles'] }}</span>
            <span class="admin-stat-label">Total Artikel</span>
        </div>
    </div>
    
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background-color: rgba(16, 185, 129, 0.15); color: #10b981;">
            <i class="fa-solid fa-award"></i>
        </div>
        <div class="admin-stat-info">
            <span class="admin-stat-value">{{ $stats['certificates'] }}</span>
            <span class="admin-stat-label">Total Sertifikat</span>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background-color: rgba(239, 68, 68, 0.15); color: #f87171;">
            <i class="fa-solid fa-envelope"></i>
        </div>
        <div class="admin-stat-info">
            <span class="admin-stat-value">{{ $stats['messages'] }}</span>
            <span class="admin-stat-label">Total Pesan</span>
        </div>
    </div>
</div>

<!-- Visitor Analytics Chart -->
<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fa-solid fa-chart-area text-gradient"></i> Statistik Pengunjung (7 Hari Terakhir)</h3>
        <span style="font-size: 0.85rem; color: var(--text-secondary); font-weight: 500;">Pembaruan Real-Time</span>
    </div>
    <div style="width: 100%; height: 320px; position: relative;">
        <!-- Canvas for Chart.js -->
        <canvas id="visitorChart" style="width: 100%; height: 100%;"></canvas>
    </div>
</div>

<!-- Logs and Messages row split -->
<div class="admin-form-split" style="grid-template-columns: 1.2fr 0.8fr;">
    <!-- Recent Activity Logs -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="fa-solid fa-list-check text-gradient"></i> Log Aktivitas Terbaru</h3>
            <span class="badge badge-info">10 Log Terbaru</span>
        </div>
        
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aktivitas</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $log)
                        <tr>
                            <td style="white-space: nowrap; font-size: 0.8rem;">{{ $log->created_at->format('d/m/y H:i') }}</td>
                            <td><strong>{{ $log->user ? $log->user->name : 'Guest/Sistem' }}</strong></td>
                            <td><span class="badge badge-info" style="font-size: 0.7rem;">{{ $log->action }}</span></td>
                            <td>{{ $log->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-secondary);">Belum ada log aktivitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Unread Messages -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="fa-solid fa-message text-gradient"></i> Pesan Belum Dibaca</h3>
            <a href="{{ route('admin.messages.index') }}" class="text-gradient" style="font-size: 0.85rem; font-weight: 700;">Semua Pesan <i class="fa-solid fa-chevron-right"></i></a>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 16px;">
            @forelse($unreadMessages as $msg)
                <div class="timeline-content" style="text-align: left; padding: 16px; border-left: 3px solid #f87171; position: relative;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 4px;">
                        <strong>{{ $msg->name }}</strong>
                        <span>{{ $msg->created_at->diffForHumans() }}</span>
                    </div>
                    <div style="font-weight: 600; font-size: 0.88rem; margin-bottom: 6px;">{{ $msg->subject }}</div>
                    <p style="font-size: 0.82rem; color: var(--text-secondary); line-height: 1.4;">{{ Str::limit($msg->message, 80) }}</p>
                    
                    <form action="{{ route('admin.messages.read', $msg->id) }}" method="POST" style="margin-top: 10px;">
                        @csrf
                        <button type="submit" class="btn btn-secondary" style="padding: 4px 10px; font-size: 0.75rem;"><i class="fa-solid fa-check"></i> Tandai Dibaca</button>
                    </form>
                </div>
            @empty
                <div style="text-align: center; padding: 32px 0; color: var(--text-secondary);">
                    <i class="fa-solid fa-circle-check" style="font-size: 2.5rem; margin-bottom: 12px; color: var(--border-color);"></i>
                    <p>Semua pesan masuk telah dibaca!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Load Chart.js CDN for visual excellence graphs -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('visitorChart').getContext('2d');
    
    // Inject PHP data arrays safely
    const labels = {!! json_encode($chartLabels) !!};
    const values = {!! json_encode($chartValues) !!};

    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
    gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Kunjungan',
                data: values,
                borderColor: '#10b981',
                borderWidth: 3,
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#34d399',
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)'
                    },
                    ticks: {
                        color: '#94a3b8',
                        font: {
                            family: 'Inter'
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#94a3b8',
                        font: {
                            family: 'Inter'
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
