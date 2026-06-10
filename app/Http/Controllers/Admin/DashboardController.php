<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\Portfolio;
use App\Models\Blog;
use App\Models\Certificate;
use App\Models\Contact;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display Dashboard Main Area
     */
    public function index()
    {
        // 1. Stats Counter
        $stats = [
            'visitors' => Visitor::count(),
            'projects' => Portfolio::count(),
            'articles' => Blog::count(),
            'certificates' => Certificate::count(),
            'messages' => Contact::count(),
        ];

        // 2. Fetch Visitor Chart Data (Last 7 Days)
        $chartData = Visitor::select('visit_date', DB::raw('count(*) as count'))
            ->where('visit_date', '>=', now()->subDays(7)->toDateString())
            ->groupBy('visit_date')
            ->orderBy('visit_date', 'asc')
            ->get();

        $chartLabels = [];
        $chartValues = [];

        // Build continuous 7 days map
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $label = now()->subDays($i)->format('d M');
            $chartLabels[] = $label;
            
            $match = $chartData->firstWhere('visit_date', $date);
            $chartValues[] = $match ? $match->count : 0;
        }

        // 3. Latest Activity Logs
        $activities = ActivityLog::with('user')->latest()->take(10)->get();

        // 4. Latest Unread Contact Messages
        $unreadMessages = Contact::where('is_read', false)->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'chartLabels', 'chartValues', 'activities', 'unreadMessages'));
    }

    /**
     * Download activity logs list (CSV format)
     */
    public function logs()
    {
        $logs = ActivityLog::with('user')->latest()->get();
        
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=activity_logs_' . date('Ymd_His') . '.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'User', 'Action', 'Description', 'IP Address', 'User Agent', 'Timestamp']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user ? $log->user->name : 'System / Guest',
                    $log->action,
                    $log->description,
                    $log->ip_address,
                    $log->user_agent,
                    $log->created_at
                ]);
            }
            fclose($file);
        };

        ActivityLog::record('backup_logs', 'Exported activity logs in CSV format');

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Native PHP Database Backup Download
     */
    public function backup()
    {
        // Check role permissions (only Administrator can download backup)
        if (!Auth::user()->isAdmin()) {
            return back()->with('error', 'Hanya Administrator yang diperbolehkan mengunduh backup database.');
        }

        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $keyName = 'Tables_in_' . $dbName;

        $sqlDump = "-- Database Backup for portfolio\n";
        $sqlDump .= "-- Generated on " . date('Y-m-d H:i:s') . "\n\n";
        $sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$keyName;
            
            // Get Create Table query
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $sqlDump .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $sqlDump .= $createTable[0]->{'Create Table'} . ";\n\n";

            // Get Rows data
            $rows = DB::table($tableName)->get();
            if ($rows->count() > 0) {
                $sqlDump .= "INSERT INTO `{$tableName}` VALUES \n";
                $rowStrings = [];
                foreach ($rows as $row) {
                    $values = [];
                    foreach ((array)$row as $val) {
                        if (is_null($val)) {
                            $values[] = "NULL";
                        } else {
                            $values[] = "'" . addslashes($val) . "'";
                        }
                    }
                    $rowStrings[] = "(" . implode(', ', $values) . ")";
                }
                $sqlDump .= implode(",\n", $rowStrings) . ";\n\n\n";
            }
        }

        $sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";

        // File download headers
        $headers = [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => 'attachment; filename=db_backup_portfolio_' . date('Ymd_His') . '.sql',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        ActivityLog::record('backup_db', 'Downloaded database backup sql file');

        return response($sqlDump, 200, $headers);
    }
}
