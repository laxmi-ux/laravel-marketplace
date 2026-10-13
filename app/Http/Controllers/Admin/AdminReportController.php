<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Helpers\BuyerNotificationHelper;
use App\Models\Seller;
use App\Helpers\SellerNotificationHelper;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with([
            'reporter',
            'reportedUser',
            'listing'
        ])->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->paginate(15)->withQueryString();

        $counts = [
            'total' => Report::count(),
            'pending' => Report::where('status', 'pending')->count(),
            'under_review' => Report::where('status', 'under_review')->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
            'dismissed' => Report::where('status', 'dismissed')->count(),
        ];

        return view('admin.reports.index', compact('reports', 'counts'));
    }

    public function show(Report $report)
    {
        $report->load([
            'reporter',
            'reportedUser',
            'listing'
        ]);

        return view('admin.reports.show', compact('report'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,under_review,resolved,dismissed'
            ],
            'admin_notes' => [
                'nullable',
                'string',
                'max:5000'
            ],
        ]);

        $report = Report::with('reporter')->findOrFail($id);

        $oldStatus = $report->status;
        $newStatus = $validated['status'];

        // Save the updated report.
        $report->status = $newStatus;
        $report->admin_notes = $validated['admin_notes'] ?? null;
        $report->save();

        // Notify the reporter only when the status actually changes.
        if (
            $oldStatus !== $newStatus &&
            $report->reporter &&
            $report->reporter_id
        ) {
            $statusMessages = [
                'pending' => 'Your report is now pending.',
                'under_review' => 'Your report is now under review.',
                'resolved' => 'Your report has been resolved.',
                'dismissed' => 'Your report has been dismissed.',
            ];

            $message = (
                $statusMessages[$newStatus]
                ?? 'Your report status has changed.'
            ) . ' Report ID: #' . $report->id;

            // Relative URL avoids localhost/127.0.0.1 host mismatches.
            $reportUrl = route(
                'buyer.reports.show',
                ['report' => $report->id],
                false
            );

            BuyerNotificationHelper::send(
                (int) $report->reporter_id,
                'Report Status Updated',
                $message,
                'report',
                $reportUrl
            );

       
// Notify the reported seller when the report status changes.
if ($oldStatus !== $newStatus && $report->reported_user_id) {

    $reportedSeller = Seller::where(
        'user_id',
        $report->reported_user_id
    )->first();

    if ($reportedSeller) {

        $sellerStatusMessages = [
            'pending' => 'A report concerning you or your listing is pending.',
            'under_review' => 'The admin is reviewing a report concerning you or your listing.',
            'resolved' => 'The report concerning you or your listing has been resolved.',
            'dismissed' => 'The report concerning you or your listing has been dismissed.',
        ];

        $sellerMessage = (
            $sellerStatusMessages[$newStatus]
            ?? 'The status of a report concerning you or your listing has changed.'
        ) . ' Report ID: #' . $report->id;

        $sellerNotificationUrl = route(
            'seller.notifications.index',
            [],
            false
        );

        SellerNotificationHelper::send(
            (int) $reportedSeller->id,
            'Report Status Updated',
            $sellerMessage,
            'report',
            $sellerNotificationUrl
        );
    }
}

        }

        return redirect()
            ->route('admin.reports.show', $report->id)
            ->with(
                'success',
                'Report updated successfully.'
                . (
                    $oldStatus !== $newStatus
                        ? ' The reporter has been notified.'
                        : ''
                )
            );
    }
}
