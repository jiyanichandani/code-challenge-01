<?php
namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillStage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use App\Http\Resources\BillResource;
use Exception;

class BillController extends Controller
{
    public function getBills()
    {
        try {
            $totalSubmitted = Bill::BillCountByStatus('Submitted');
            $totalApproved = Bill::BillCountByStatus('Approved');
            $totalOnHold = Bill::BillCountByStatus('On Hold');

            $users = User::withCount([
                'bills as total_bills',
                'bills as submitted_bills' => function ($query) {
                    $query->whereHas('stage', function($q) {
                        $q->where('label', 'Submitted');
                    });
                },
                'bills as approved_bills' => function ($query) {
                    $query->whereHas('stage', function($q) {
                        $q->where('label', 'Approved');
                    });
                }
            ])->get();


            // Generate API Response
            return response()->json([
                'total_submitted' => $totalSubmitted,
                'total_approved' => $totalApproved,
                'total_on_hold' => $totalOnHold,
                'user_stats' => UserResource::collection($users),
            ]);
        } catch (Exception $e) {
            // Log the exception
            Log::error('Error fetching bills: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch bills'], 500);
        }
    }

    public function addBill(Request $request)
    {
        try {
            $validated = $request->validate([
                'bill_reference' => 'required|string',
                'bill_date' => 'required|date',
                'bill_stage_id' => 'required|exists:bill_stages,id',
            ]);

            $bill = Bill::create([
                'bill_reference' => $request->bill_reference,
                'bill_date' => $request->bill_date,
                'bill_stage_id' => $request->bill_stage_id,
                'submitted_at'=> now(),
            ]);

            // API response with 201 status code
            return new BillResource($bill, 201);
        } catch (Exception $e) {
            Log::error('Error adding bill: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to add bill'], 500);
        }
    }
}
