<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Yelamentryform;
use App\Models\Yelamthings;
use Log;
use DB;
use Illuminate\Support\Str;

use Carbon\Carbon;


class ReportsController extends Controller
{

		public function pl_report() {
			
			$prevYear = now()->subYear()->year;

			$data = DB::table('account_statement')
			->where('type', 'INCOME')
			->whereYear('created_at', $prevYear)
			->sum('amount');

			return view('reports.pl_report', compact('data'));
		}


		public function pl_report_calc(Request $request) {
			// dd($request->all());
							
			$fromMonth = $request->from_date; // 2025-11
			$toMonth   = $request->to_date;   // 2026-01

			$startDate = Carbon::createFromFormat('Y-m', $fromMonth)->startOfMonth();
			$endDate   = Carbon::createFromFormat('Y-m', $toMonth)->endOfMonth();

			$income = DB::table('account_statement')
				->whereBetween('created_at', [$startDate, $endDate])
				->selectRaw("
					SUM(CASE WHEN tot = 'PULLIVARI' THEN amount ELSE 0 END) as pullivari,
					SUM(CASE WHEN tot = 'YELAM' THEN amount ELSE 0 END) as yellam,
					SUM(CASE WHEN tot = 'DONATION' THEN amount ELSE 0 END) as donation,
					SUM(CASE WHEN tot = 'PM' THEN amount ELSE 0 END) as pmvari
				")
				->first();

			// Get all expense names
			$expenseMasters = DB::table('expenditure_master')
				->pluck('expenses_name');

			$expenses = [];

				foreach ($expenseMasters as $expenseName) {

					$total = DB::table('account_statement')
						->whereBetween('created_at', [$startDate, $endDate])
						->where('tot', 'EXPENSE COST')
						->where('ref_txt', $expenseName)
						->sum('amount');

					if ($total > 0) {
						$expenses[$expenseName] = $total;
					}
				}


			return response()->json([
				'pullivari' => (float) ($income->pullivari ?? 0),
				'yellam'    => (float) ($income->yellam ?? 0),
				'donation'  => (float) ($income->donation ?? 0),
				'pmvari'    => (float) ($income->pmvari ?? 0),
				'expenses'  => $expenses
			]);

		}
}

