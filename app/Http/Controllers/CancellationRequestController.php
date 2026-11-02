<?php

namespace App\Http\Controllers;

use App\Models\CancellationRequest;
use Illuminate\View\View;

class CancellationRequestController extends Controller
{
    public function index(): View
    {
        $cancellationRequests = CancellationRequest::with([
            'transaction',
            'requester',
            'reviewer',
        ])
            ->latest()
            ->paginate(10);

        return view('cancellation-requests.index', compact(
            'cancellationRequests'
        ));
    }
}