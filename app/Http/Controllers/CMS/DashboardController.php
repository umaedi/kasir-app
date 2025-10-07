<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if($request->ajax()) {
            $date = $request->get('date', Carbon::today()->toDateString());
            if($request->load === 'dashboard-summery') {
                 $report = Transaction::onDate(Carbon::parse($date))
                   ->select(
                       DB::raw('COUNT(*) as total_transactions'),
                       DB::raw('SUM(total_amount) as total_sales'),
                       DB::raw('AVG(total_amount) as average_transaction'),
                       DB::raw('MAX(total_amount) as highest_sale'),
                       DB::raw('MIN(total_amount) as lowest_sale')
                   )
                   ->first();
                   return $this->success($report, 'Summary dashboard');
            }else {
                $transactions = Transaction::onDate(Carbon::parse($date))->paginate();
                return view('cms.transactions._data_table', compact('transactions'));
            }
        }
        return view('cms.dashboard.index', [
            'title' => 'CMS Admin'
        ]);
    }
}
