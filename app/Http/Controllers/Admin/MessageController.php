<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MessageController extends Controller
{
    /**
     * Display contact messages index
     */
    public function index()
    {
        $messages = Contact::latest()->paginate(10);
        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Mark message as read
     */
    public function markAsRead($id)
    {
        $message = Contact::findOrFail($id);
        $message->update(['is_read' => true]);

        ActivityLog::record('message_read', 'Marked message from ' . $message->name . ' as read');

        return back()->with('success', 'Pesan telah ditandai sebagai dibaca.');
    }

    /**
     * Delete message
     */
    public function destroy($id)
    {
        $message = Contact::findOrFail($id);
        $name = $message->name;
        $message->delete();

        ActivityLog::record('message_delete', 'Deleted message from ' . $name);

        return back()->with('success', 'Pesan berhasil dihapus.');
    }

    /**
     * Export messages to Excel-compatible CSV format
     */
    public function exportCsv()
    {
        $messages = Contact::latest()->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=messages_export_' . date('Ymd_His') . '.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($messages) {
            $file = fopen('php://output', 'w');
            
            // CSV header columns
            fputcsv($file, ['ID', 'Nama Pengirim', 'Email', 'Subjek', 'Isi Pesan', 'Status Dibaca', 'Tanggal Dikirim']);

            foreach ($messages as $msg) {
                fputcsv($file, [
                    $msg->id,
                    $msg->name,
                    $msg->email,
                    $msg->subject,
                    $msg->message,
                    $msg->is_read ? 'Dibaca' : 'Belum Dibaca',
                    $msg->created_at->format('Y-m-d H:i:s')
                ]);
            }
            fclose($file);
        };

        ActivityLog::record('message_export_csv', 'Exported contact messages in CSV format');

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export messages to PDF (Print-Friendly Layout)
     */
    public function exportPdf()
    {
        $messages = Contact::latest()->get();
        
        ActivityLog::record('message_export_pdf', 'Generated printable PDF/HTML view of messages');

        return view('admin.messages.print', compact('messages'));
    }
}
