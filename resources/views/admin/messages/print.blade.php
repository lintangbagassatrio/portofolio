<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pesan Masuk - Cetak PDF</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) td {
            background-color: #fafafa;
        }
        .date {
            white-space: nowrap;
        }
        .badge {
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-read {
            border: 1px solid #4caf50;
            color: #4caf50;
        }
        .badge-unread {
            border: 1px solid #ff9800;
            color: #ff9800;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; background-color: #e3f2fd; padding: 15px; border-radius: 4px; border: 1px solid #bbdefb; display: flex; justify-content: space-between; align-items: center;">
        <span style="color: #0d47a1; font-weight: bold;">Cetak Laporan: Preview PDF</span>
        <button onclick="window.print();" style="background-color: #1976d2; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-weight: bold; cursor: pointer;"><i class="fa-solid fa-print"></i> Mulai Cetak / Simpan PDF</button>
    </div>

    <div class="header">
        <h1>Laporan Pesan Masuk (Kontak Masuk)</h1>
        <p>Dicetak pada tanggal: {{ date('d M Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 120px;">Tanggal</th>
                <th style="width: 130px;">Pengirim</th>
                <th style="width: 150px;">Subjek</th>
                <th>Pesan Lengkap</th>
                <th style="width: 80px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($messages as $msg)
                <tr>
                    <td class="date">{{ $msg->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <strong>{{ $msg->name }}</strong><br>
                        <span style="font-size: 10px; color: #555;">{{ $msg->email }}</span>
                    </td>
                    <td>{{ $msg->subject }}</td>
                    <td>{!! nl2br(e($msg->message)) !!}</td>
                    <td>
                        @if($msg->is_read)
                            <span class="badge badge-read">Dibaca</span>
                        @else
                            <span class="badge badge-unread">Unread</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        // Trigger print window automatically when page loading is complete
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
