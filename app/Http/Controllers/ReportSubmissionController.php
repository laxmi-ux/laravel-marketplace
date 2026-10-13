<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Report;
use App\Models\Seller;
use App\Helpers\AdminNotificationHelper;
use App\Helpers\SellerNotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportSubmissionController extends Controller
{
    /**
     * Display the report form for a listing or seller.
     */
    public function create(Request $request)
    {
        $type = $request->query('type');
        $id = $request->query('id');

        abort_unless(
            in_array($type, ['listing', 'seller'], true),
            404
        );

        if ($type === 'listing') {
            $target = Listing::with('seller')->findOrFail($id);
        } else {
            $target = Seller::findOrFail($id);
        }

        return view('reports.create', compact('type', 'target'));
    }

    /**
     * Store a report and notify the admin and reported seller.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:listing,seller'],
            'target_id' => ['required', 'integer', 'min:1'],
            'reason' => [
                'required',
                'in:spam,misleading_information,fraud,inappropriate,poor_service,other',
            ],
            'description' => [
                'required',
                'string',
                'min:10',
                'max:3000',
            ],
        ]);

        $listingId = null;
        $reportedUserId = null;
        $seller = null;

        // Identify the seller and listing being reported.
        if ($validated['type'] === 'listing') {
            $listing = Listing::with('seller')
                ->findOrFail($validated['target_id']);

            $seller = $listing->seller;

            $listingId = $listing->id;
            $subject = 'Listing report #' . $listing->id;

            if ($seller) {
                $reportedUserId = $seller->user_id;
            }
        } else {
            $seller = Seller::findOrFail(
                $validated['target_id']
            );

            $reportedUserId = $seller->user_id;
            $subject = 'Seller report #' . $seller->id;
        }

        // Prevent reporting your own listing or seller account.
        if (
            $reportedUserId !== null &&
            (int) $reportedUserId === (int) Auth::id()
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'target_id' =>
                        'You cannot report your own listing or seller account.',
                ]);
        }

        // Save the report.
        $report = Report::create([
            'reporter_id' => Auth::id(),
            'reported_user_id' => $reportedUserId,
            'listing_id' => $listingId,
            'subject' => $subject,
            'reason' => $validated['reason'],
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        // Notify all admins.
        $adminReportUrl = route(
            'admin.reports.show',
            ['report' => $report->id],
            false
        );

        AdminNotificationHelper::send(
            'New ' . ucfirst($validated['type']) . ' Report',
            'A new ' . $validated['type']
                . ' report has been submitted. Report ID: #'
                . $report->id,
            'report',
            $adminReportUrl
        );

        // Notify the reported seller, if one exists.
        if ($seller && $seller->user_id) {
            $sellerReportUrl = route(
                'seller.notifications.index',
                [],
                false
            );

            $targetDescription = $validated['type'] === 'listing'
                ? 'One of your listings has been reported.'
                : 'A report has been submitted against your seller account.';

            SellerNotificationHelper::send(
                (int) $seller->id,
                'Report Received',
                $targetDescription
                    . ' The report is pending admin review. Report ID: #'
                    . $report->id,
                'report',
                $sellerReportUrl
            );
        }

        return redirect()
            ->route('reports.create', [
                'type' => $validated['type'],
                'id' => $validated['target_id'],
            ])
            ->with(
                'success',
                'Your report has been submitted successfully.'
            );
    }
}
