<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use DB;
use Illuminate\Support\Facades\Log;

class masterpullivari extends Controller
{

    public function masterpullivari(Request $request) {

        $data = DB::table('pullivari_master')->paginate(50);
    
        return view('office.masterpullivari', compact('data'));
    }

    public function masterpullivariValidate(Request $request) {
        //dd($request->all());
        $rules = [
            'annual_date' => 'required|date',
            'bi_annual' => 'required|date',
            'value1' => 'required|numeric',
        ];        
        
        $messages = [
            'annual_date.required' => 'Set the Annual year.',
            'by_annual.required' => 'Set the Annual Range year.',

            'annual_date.date' => 'Annual year must be a valid year date.',
            'by_annual.date' => 'Annual Range year must be a valid year date.',
        
            'value1.required' => 'Please enter the amount for Annual year.',
            'value1.numeric' => 'Annual year amount must be a number.',
        
        ];
        
        
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            \Log::error($validator->errors()->all());
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }
        else{
            return response()->json(['status' => TRUE]);
        }
        
    }
    public function masterpullivariStore(Request $request) {
        
        DB::table('pullivari_master')
        ->insert([
            'annual'=>$request->annual_date,
            'by_annual'=>$request->bi_annual,
            'annual_amt'=> $request->value1,
            'created_at'=> now(),
        ]);
        
        return response()->json(['status' => TRUE,'message'=>'Master Pullivari data saved successfully']);

    }
    
    
   
    public function annual_year(Request $request)
    {
        
        $annual=$request->date1;
        $bi_annual=$request->date2;

        // Log::info($annual);
        // Log::info($bi_annual);
        if (!empty($bi_annual) && !empty($annual)) {
            $ann_date = DB::table('pullivari_master')
                ->where(function($query) use ($annual, $bi_annual) {
                    $query->where('annual', '<=', $bi_annual)
                          ->where('by_annual', '>=', $annual);
                })
                ->first();
            }
        if ($ann_date) {
            Log::info("hello world");
            return response()->json(['exists' => true, 'data' => $ann_date]);
        }
        return response()->json(['exists' => false]);
        
    }
    
    public function edit(string $id)
    {
       
    }

    
    public function update(Request $request, string $id)
    {
        //
    }

    public function pullivari(Request $request) {
        $perPage = $request->items ?? 50; 
        $searchTerm = $request->get('search');
        
        $users = DB::table('registers')->orderby('id','desc');
        
        // Apply search filter
        if ($searchTerm) {
            $users->where('pulliid', 'LIKE', '%' . $searchTerm . '%')
            ->orwhere('name', 'LIKE', '%' . $searchTerm . '%')
            ->orwhere('native', 'LIKE', '%' . $searchTerm . '%')
            // ->orwhere('email', 'LIKE', '%' . $searchTerm . '%')
            // ->orwhere('fathername', 'LIKE', '%' . $searchTerm . '%')
            ->orwhere('whatsappnumber', 'LIKE', '%' . $searchTerm . '%');
        }

        $users = $users->paginate($perPage)->appends($request->query());

        if ($request->has('search') && $users->isEmpty() && $request->get('page', 1) > 1) {
            return redirect()->route('pullivari', [
                'search' => $request->get('search'),
                'items' => $perPage,
                'page' => 1
            ]);
        }

        $vari_amt = DB::table('pullivari_master')->get();

        //defined variables
        $now=(explode(' ',now()));
        $curr_year=(string)($now[0]);
        // Log::info('current year = ' .$curr_year);

        //checking and calculating amt for curr and prev based on year 
        foreach ($users as $item) { 
            //defining variable inside loop for amt reset for each user
            $prev_Amt=0;
            $curr_Amt=0;
            
            $created_year = explode(' ',$item->created_at);
            $user_year = $created_year[0];
            // Log::info('user year = '.$user_year);
            
            foreach ($vari_amt as $data){
                
                if($data->annual !==null &&  $data->by_annual !==null ){

                    $check_year=$data->annual;
                    $check_year1=$data->by_annual;
                    // Log::info($check_year .' to '. $check_year1);
                    
                    if($user_year<=$check_year || $user_year<=$check_year1){
                        if ($check_year <= $curr_year && $curr_year<=$check_year1){
                            
                            $curr_Amt +=$data->annual_amt;
                        }
                        else{   
                            $prev_Amt +=$data->annual_amt;
                        }
                    }
                    
                }
            }
            $item->previous_yr_amt = $prev_Amt;
            $item->current_yr_amt = $curr_Amt;
        }
        // dd($users);
        
        
        
        $idList = $users->pluck('pulliid');
        
        $lastPaidAmt = [];
        $TotalPaidAmt = [];
        
    
        foreach ($idList as $id) {
    
    
            $last_paid = DB::table('account_statement')
                ->where('ref_id', $id)
                ->where('pay_mode', 'paid')
                ->where('tot', 'PULLIVARI')
                ->orderBy('id', 'desc')
                ->select('ref_id', 'amount')
                ->first(); 
    
            // $total_paid = DB::table('expenditure_enquiry')
            //     ->where('pulli_id', $id)
            //     ->where('type', 'PULLIVARI')
            //     ->where('payment_status', 'paid')
            //     ->select('pulli_id', DB::raw('SUM(credit) as total_credit'))
            //     ->groupBy('pulli_id')
            //     ->first();
    
            // if ($total_paid) {
            //     $TotalPaidAmt[] = [
            //         'pulli_id' => $total_paid->pulli_id,
            //         'total_credit' => $total_paid->total_credit,
            //     ];
            // } else {
            //     $TotalPaidAmt[] = [
            //         'pulli_id' => $id,
            //         'total_credit' => 0,
            //     ];
            // }
    
            if ($last_paid) {
                $lastPaidAmt[] = [
                    'pulli_id' => $last_paid->ref_id,
                    'credit' => $last_paid->amount,
                ];
            } else {
                $lastPaidAmt[] = [
                    'pulli_id' => $id,
                    'credit' => 0,
                ];
            }
        }
        
    
        // Add last paid
        foreach ($users as $item) { 
            $item->lastpaid = 0;
            foreach ($lastPaidAmt as $record) {
                if ($record['pulli_id'] == $item->pulliid) {
                    $item->lastpaid = $record['credit'] ?? 0;
                    break;
                }
            }
        }

        // Add total paid
        // foreach ($users as $item) { 
        //     $item->total_amt = 0;
        //     foreach ($TotalPaidAmt as $record) {
        //         if ($record['pulli_id'] == $item->pulliid) {
        //             $item->total_amt = $record['total_credit'] ?? 0;
        //             break;
        //         }
        //     }
        // }

        // //Add last paid
        // $data->getCollection()->transform(function ($item) use ($lastPaidAmt) {
        //     $item->lastpaid = 0;
    
        //     foreach ($lastPaidAmt as $record) {
        //         if ($record['pulli_id'] == $item->pulliid) {
        //             $item->lastpaid = $record['credit'] ?? 0;
        //             break;
        //         }
        //     }
    
        //     return $item;
        // });
    
        // // Add total paid
        // $data->getCollection()->transform(function ($item) use ($TotalPaidAmt) {
        //     $item->total_amt = 0;
    
        //     foreach ($TotalPaidAmt as $record) {
        //         if ($record['pulli_id'] == $item->pulliid) {
        //             $item->total_amt = $record['total_credit'] ?? 0;
        //             break;
        //         }
        //     }
    
        //     return $item;
        // });
    // dd($users);
        $data=$users;
        return view('office.pullivari', compact('data', 'perPage'));
    }
    
}
