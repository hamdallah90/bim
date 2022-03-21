<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Carbon\CarbonPeriod;

class ReportController extends Controller
{
    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $results = [];
        $period = CarbonPeriod::create($request->start_date, '1 month', $request->end_date);
        $currentDate = Carbon::now()->format('Y-m-d');

        foreach ($period as $dt) {
            $month_start = $dt->copy()->startOfMonth();
            $month_end = $dt->copy()->endOfMonth();
            $query = Transaction::join('payment_records', 'payment_records.transaction_id', '=', 'transactions.id')
                        ->whereDate('transactions.created_at', '>=', $month_start)
                        ->whereDate('transactions.created_at', '<=', $month_end)
                        ->groupBy('transaction_id')
                        ->selectRaw("transaction_id, sum(payment_records.amount) as total_paid, CASE
                                WHEN sum(payment_records.amount) >= transactions.total
                                THEN 'paid'
                                WHEN date(transactions.due_on) < '$currentDate'
                                THEN 'overdue'
                                ELSE 'outstanding'
                            END AS transaction_status")->get();

            $total_paid = $query->sum(function ($item) {
                return $item['transaction_status'] == 'paid' ? $item['total_paid'] : 0;
            });
            $total_overdue = $query->sum(function ($item) {
                return $item['transaction_status'] == 'overdue' ? $item['total_paid'] : 0;
            });
            $total_outstanding = $query->sum(function ($item) {
                return $item['transaction_status'] == 'outstanding' ? $item['total_paid'] : 0;
            });

            $results[] = [
                "month" => $month_start->format('m'),
                "year" => $month_start->format('Y'),
                "paid" => $total_paid,
                "outstanding" => $total_outstanding,
                "overdue"  => $total_overdue
            ];
        }

        return response()->json($results);
    }
}
