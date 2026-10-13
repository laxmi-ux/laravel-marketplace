<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class BuyerReportController extends Controller
{
    public function show($id)
    {
        $report = Report::where('id', $id)
            ->where('reporter_id', Auth::id())
            ->firstOrFail();

        return view('buyer.reports.show', compact('report'));
    }
}

