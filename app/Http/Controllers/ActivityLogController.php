<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\EntrepriseSiege;
use App\Scopes\SiegeScope;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ActivityLogController extends Controller
{
    private function applyFilters($query, array $filters): void
    {
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
        if (!empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }
        if (!empty($filters['user_email'])) {
            $query->where('user_email', 'like', '%' . $filters['user_email'] . '%');
        }
        if (!empty($filters['SiegeID'])) {
            $query->where('SiegeID', $filters['SiegeID']);
        }
        if (!empty($filters['model_type'])) {
            $query->where('model_type', $filters['model_type']);
        }
    }

    public function index(Request $request)
    {
        $filters = $request->only([
            'date_from', 'date_to', 'action', 'user_email', 'SiegeID', 'model_type'
        ]);

        $query = ActivityLog::query()->orderBy('created_at', 'desc');
        $this->applyFilters($query, $filters);

        $logs       = $query->paginate(5)->withQueryString();
        $sieges     = EntrepriseSiege::withoutGlobalScope(SiegeScope::class)->get();
        $actions    = ActivityLog::select('action')->distinct()->orderBy('action')->pluck('action');
        $modelTypes = ActivityLog::select('model_type')->distinct()
                        ->whereNotNull('model_type')->orderBy('model_type')->pluck('model_type');

        return view('activity-logs.index', compact('logs', 'filters', 'sieges', 'actions', 'modelTypes'));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $filters = $request->only([
            'date_from', 'date_to', 'action', 'user_email', 'SiegeID', 'model_type'
        ]);

        $query = ActivityLog::query()->orderBy('created_at', 'desc');
        $this->applyFilters($query, $filters);

        $logs   = $query->get();
        $sieges = EntrepriseSiege::withoutGlobalScope(SiegeScope::class)->pluck('Nom', 'ID');

        ActivityLogService::log(action: 'export_csv', modelType: 'ActivityLog');

        $filename = 'activity_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () use ($logs, $sieges) {
            $handle = fopen('php://output', 'w');

            // BOM UTF-8 pour compatibilité Excel
            fwrite($handle, "\xEF\xBB\xBF");

            // En-têtes
            fputcsv($handle, [
                'Date / Heure', 'Utilisateur', 'Rôle', 'Action',
                'Entité', 'ID', 'Détails', 'IP', 'Siège',
            ], ';');

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->created_at->format('d/m/Y H:i:s'),
                    $log->user_email,
                    $log->user_role ?? 'N/A',
                    $log->action,
                    $log->model_type ?? '—',
                    $log->model_id ?? '—',
                    $log->model_label ?? $log->description ?? '—',
                    $log->ip_address,
                    $log->SiegeID ? ($sieges[$log->SiegeID] ?? $log->SiegeID) : '—',
                ], ';');
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
