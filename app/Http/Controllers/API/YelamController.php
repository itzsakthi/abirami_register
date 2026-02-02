<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;
use DB;
use Carbon\Carbon;

class YelamController extends Controller
{
    public function getAllreport(Request $request)
    {
        $products = \DB::table('yelamthings')->orderBy('id','desc')->get();
        $pullidata = \DB::table('registers')->orderBy('id','desc')->get();
      
        $expensedata1 = \DB::table('expenditure_master')->orderBy('id','desc')->get();
        $expensedata2 = \DB::table('account_statement')
        ->where('type', 'EXPENSE')
        ->orderBy('id','desc')->get();

        $master_pulli_data = \DB::table('pullivari_master')->orderBy('id','desc')->get();

        // dd($master_pulli_data);
        return view('office.pagereport',compact('products','pullidata', 'expensedata1', 'expensedata2', 'master_pulli_data'));

    }


    public function reportdata(Request $request)
    {
        $yelamporul = $request->yelamporul;
        $pulliid = $request->pulliid;
        $native = $request->native;
        $nameguest = $request->nameguest;
        $nativeguest = $request->nativeguest;
        $whatsappnoguest = $request->whatsappnoguest;

        if (!empty($yelamporul)){
            $pullidata = \DB::table('yelamentryforms')
            ->selectRaw('yelamentryforms.*,yelamthings.things')
        ->leftJoin('yelamthings','yelamthings.id','yelamentryforms.yelamporul')
            ->where('yelamporul', '=', $yelamporul)
        ->orderBy('id','desc')
            ->get();          
        } 
        
        if (!empty($pulliid)){
            $pullidata = \DB::table('yelamentryforms')
            ->selectRaw('yelamentryforms.*,yelamthings.things')
        ->leftJoin('yelamthings','yelamthings.id','yelamentryforms.yelamporul')
            ->where('yelamentryforms.pulliid', '=', $pulliid)
            ->get()
            ->toArray();
        }

        if (!empty($native)){
            $pullidata = \DB::table('yelamentryforms')
            ->selectRaw('yelamentryforms.*,yelamthings.things')
        ->leftJoin('yelamthings','yelamthings.id','yelamentryforms.yelamporul')
            ->where('yelamentryforms.native', '=', $native)
            ->get();
            // ->toArray();
        }
    
        if (!empty($nameguest)){
        $pullidata = \DB::table('yelamentryforms')
        ->selectRaw('yelamentryforms.*,yelamthings.things')
        ->leftJoin('yelamthings','yelamthings.id','yelamentryforms.yelamporul')
            ->where('yelamentryforms.nameguest', '=', $nameguest)
        ->get();
        }
        if (!empty($nativeguest)){
        $pullidata = \DB::table('yelamentryforms')
        ->selectRaw('yelamentryforms.*,yelamthings.things')
        ->leftJoin('yelamthings','yelamthings.id','yelamentryforms.yelamporul')
            ->where('yelamentryforms.nativeguest', '=', $nativeguest)
        ->get();
        }
        
        if (!empty($whatsappnoguest)){
            $pullidata = \DB::table('yelamentryforms')
            ->selectRaw('yelamentryforms.*,yelamthings.things')
        ->leftJoin('yelamthings','yelamthings.id','yelamentryforms.yelamporul')
            ->where('yelamentryforms.whatsappnoguest', '=', $whatsappnoguest)
            ->get();
        }
        
        return response()->json(['data'=>$pullidata]);
    } 


    public function yellamsearch(Request $request) {


        $data = \DB::table('yelamentryforms')
          ->selectRaw('yelamentryforms.*,yelamthings.things')
          ->leftJoin('yelamthings','yelamthings.id','yelamentryforms.yelamporul')
          ->orderBy('yelamentryforms.id','desc');

        if($request->y_yelamporul){
            $searchTerm=$request->y_yelamporul;
            $data->where(function ($q) use ($searchTerm) {
                $q->Where('yelamentryforms.yelamporul', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        if($request->y_pulliid){
            $searchTerm=$request->y_pulliid;
            $data->where(function ($q) use ($searchTerm) {
                $q->Where('yelamentryforms.pulliid', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        if($request->y_native){
            $searchTerm=$request->y_native;
            $data->where(function ($q) use ($searchTerm) {
                $q->Where('yelamentryforms.native', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        if($request->y_nameguest){
            $searchTerm=$request->y_nameguest;
            $data->where(function ($q) use ($searchTerm) {
                $q->Where('yelamentryforms.nameguest', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        if($request->y_whatsappnoguest){
            $searchTerm=$request->y_whatsappnoguest;
            $data->where(function ($q) use ($searchTerm) {
                $q->Where('yelamentryforms.whatsappnoguest', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        if($request->y_nativeguest){
            $searchTerm=$request->y_nativeguest;
            $data->where(function ($q) use ($searchTerm) {
                $q->Where('yelamentryforms.nativeguest', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        if($request->yellam_paid_unpaid){
            $searchTerm=$request->yellam_paid_unpaid;
            $data->where(function ($q) use ($searchTerm) {
                $q->Where('yelamentryforms.payment', $searchTerm);
            });
        } 
      
        
        $data=$data->get();
        $array=[];

        $currDate = now()->toDateString();
        foreach($data as $items){
            $user_crt_at = Carbon::parse($items->created_at)->toDateString(); 
            
            if($request->yellam_from && $request->yellam_to){
                if($user_crt_at>=$request->yellam_from && $user_crt_at<=$request->yellam_to){
                    $array[] = $items;
                }
            } else if ($request->yellam_from) {
                if($user_crt_at>=$request->yellam_from && $user_crt_at<=$currDate){
                    $array[] = $items;
                }
            }
            else{
                $array[] = $items;
            }
        }


        return response()->json(['data'=> $array]);
    }

    public function export(Request $request){
        $data = \DB::table('yelamentryforms')->get();
        $data = \DB::table('yelamentryforms')
        ->selectRaw('yelamentryforms.*,yelamthings.things')
        ->leftJoin('yelamthings','yelamthings.id','yelamentryforms.yelamporul')
        ->orderBy('id','desc')
        ->get();   
        return response()->json(['data'=>$data]);
    
    }
    public function pullisearch(Request $request){ 

        $data = \DB::table('registers')
        ->select([
            'registers.whatsappnumber',
            'registers.spousenumber',
            'registers.pulliid',
            'registers.native',
            'registers.name',
            'registers.created_at as user_crt',
        ])
        ->orderBy('registers.id','desc');

        if($request->pulli_ids){
            $searchTerm=$request->pulli_ids;
            $data->where(function ($q) use ($searchTerm) {
                $q->Where('registers.pulliid', $searchTerm );
            });
        }

        if($request->pulli_native){
            $searchTerm=$request->pulli_native;
            $data->where(function ($q) use ($searchTerm) {
                $q->Where('registers.native', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        if($request->pulli_name){
            $searchTerm=$request->pulli_name;
            $data->where(function ($q) use ($searchTerm) {
                $q->Where('registers.name', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        if($request->mob_no){
            $searchTerm=$request->mob_no;
            $data->where(function ($q) use ($searchTerm) {
                $q->Where('registers.whatsappnumber', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('registers.spousenumber', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        
        $vari_amt = DB::table('pullivari_master');  //master entries of tax and its years
      
        if($request->master_pulli_year){
            $searchTerm=$request->master_pulli_year;
            $vari_amt = $vari_amt->where(function($q) use ($searchTerm) {
                $q->whereDate('annual', '=', $searchTerm);
            });
        }
        
        $vari_amt =$vari_amt->get();
        $data=$data->get();
        
        //initialize of paid amts status for each users
        $Amt=[];
        // $paidAmt = [];

        foreach($data as $ids){
            $now=(explode(' ',now()));
            $curr_year=(string)($now[0]);   //getting current year in str like (2025-06-11)

            $paid_years = DB::table('account_statement')   //getting paid years details in search filtered data ids
                ->where('ref_id', $ids->pulliid)
                ->where('tot', 'PULLIVARI')
                ->where('pay_mode', 'paid')
                ->select('pay_to_txt', 'created_at as pulli_paid_created')
                ->get();            

            $get_paid_years="";           //combining each years in a str 
            if($paid_years){
                foreach ($paid_years as $x){
                    $get_paid_years= "$get_paid_years $x->pay_to_txt";
                }
            }
            
            $check_paid_years = explode(' ',$get_paid_years);   //splited str stored in arry for year verification like [2025-6-1,2026-5-6,2024-11-2]
            // Log::info($check_paid_years);
            

            $user=$ids->user_crt;

            //getting only user created time like 2025-6-7 leaving time of entry by using explode
            $created_year = explode(' ',$user);
            $user_year = $created_year[0];
            // Log::info($user_year);
            
            

        foreach ($vari_amt as $v_data){
                
                if($v_data->by_annual !==null && $v_data->annual !==null  ){
                
                    $check_year=$v_data->annual;      //from date  in pullivari master
                    $check_year1=$v_data->by_annual;  //end date
                    if($user_year<=$check_year || $user_year<=$check_year1 ){
                        if (in_array($check_year1,$check_paid_years ) || in_array($check_year,$check_paid_years )) {
                        // Log::info( "Found!");             //logging paid status in amt array
                        foreach ($paid_years as $x){
                            $Amt []=[
                                'pulliid'=>$ids->pulliid,
                                'native'=>$ids->native,
                                'spousenumber'=>$ids->spousenumber,
                                'whatsappnumber'=>$ids->whatsappnumber,
                                'name'=>$ids->name,
                                'amt'=>$v_data->annual_amt,
                                'year'=>($check_year." to ".$check_year1),
                                'status'=>'paid',
                                'crt'=> ($x->pulli_paid_created)
                            ];
                        }
                        } else {
                            $Amt []=[
                            'pulliid'=>$ids->pulliid,
                            'native'=>$ids->native,
                            'spousenumber'=>$ids->spousenumber,
                            'whatsappnumber'=>$ids->whatsappnumber,
                            'name'=>$ids->name,
                            'amt'=>$v_data->annual_amt,
                            'year'=>($check_year." to ".$check_year1),
                            'status'=>'Not paid'
                            ];
                        }
                    }
                }
            }
        }
        // dd($request->all());

        if($request->pulli_paid_unpaid){
            $Amt = array_filter($Amt, function($item) use ($request) {
                return isset($item['status']) && $item['status'] === $request->pulli_paid_unpaid;
            });
        }


        $currDate = now()->toDateString();

        if($request->pullivari_from) {
            $Amt = array_filter($Amt, function ($item) use ($request, $currDate) {
                $user_crt_at = \Carbon\Carbon::parse($item['crt'])->toDateString();
                if ($request->pullivari_from && $request->pullivari_to) {
                    return $user_crt_at >= $request->pullivari_from && $user_crt_at <= $request->pullivari_to;
                } elseif ($request->pullivari_from) {
                    return $user_crt_at >= $request->pullivari_from && $user_crt_at <= $currDate;
                } else {
                    return true;
                }
            });
        }

        $Amt = array_values($Amt);

        // dd($request->all(),$data,$get_paid_years,$vari_amt,$paidAmt,$Amt);
        return response()->json(['amt'=>$Amt]);
        
    }


    public function donsearch(Request $request){
        $data = \DB::table('account_statement')
        ->select([
            'registers.whatsappnumber',
            'registers.spousenumber',
            'registers.pulliid',
            'registers.native',
            'registers.name',
            'account_statement.*',
        ])
        ->where('account_statement.tot','DONATION')
        ->leftjoin('registers','account_statement.ref_id','=','registers.pulliid')
        ->orderBy('account_statement.id','desc');
        if($request->don_pulli_ids){
            $searchTerm=$request->don_pulli_ids;
            $data->where(function ($q) use ($searchTerm) {
                $q->Where('account_statement.ref_id', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        if($request->don__native){
            if($request->don_pulli_ids=='NA'){
                $searchTerm=$request->don__native;
                $data->where(function ($q) use ($searchTerm) {
                    $q->Where('account_statement.pay_to_txt','LIKE', '%|||' . $searchTerm .'%');
                });
            }
            else{
                $searchTerm=$request->don__native;
                $data->where(function ($q) use ($searchTerm) {
                    $q->Where('registers.native', 'LIKE', '%' . $searchTerm . '%');
                });
            }
        }


        if($request->don_name){
            $searchTerm=$request->don_name;
            $data->where(function ($q) use ($searchTerm) {
                $q->Where('account_statement.ref_txt', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        if($request->don_type_ids){
            $searchTerm=$request->don_type_ids;
            $data->where(function ($q) use ($searchTerm) {
                $q->Where('account_statement.pay_mode', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        if($request->don_mob_no){
            if($request->don_pulli_ids=='NA'){
                $searchTerm=$request->don_mob_no;
                $data->where(function ($q) use ($searchTerm) {
                    $q->Where('account_statement.pay_to_txt', 'LIKE', $searchTerm . '|||%');
                });
            }
            else{
                $searchTerm=$request->don_mob_no;
                $data->where(function ($q) use ($searchTerm) {
                    $q->Where('registers.whatsappnumber', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('registers.spousenumber', 'LIKE', '%' . $searchTerm . '%');
                });
            }
        }

        $data=$data->get();
        $array=[];

        $currDate = now()->toDateString();

        foreach($data as $items){
            $user_crt_at = Carbon::parse($items->created_at)->toDateString(); 

            if($request->don_from && $request->don_to){
                if($user_crt_at>=$request->don_from && $user_crt_at<=$request->don_to){
                    $array[] = $items;
                }
            } elseif ($request->don_from) {
                if ($user_crt_at >= $request->don_from && $user_crt_at <= $currDate) {
                    $array[] = $items;
                }
            } else{
                $array[] = $items;
            }            
        }

        
        // dd($request->all(),$array);
        return response()->json(['amt'=>$array]);
        
    }

    public function expensesearch(Request $request) {

        // dd($request->all());

        $data = \DB::table('account_statement')
        ->where('type','EXPENSE')
        ->orderBy('id', 'desc');       

        if(isset($request->exp_name)) {
            $searchTerm=$request->exp_name;
            $data->where(function ($q) use ($searchTerm) {
                $q->Where('account_statement.ref_txt', $searchTerm );
            });
        }  

        if(isset($request->exp_pay_to)) {
            $searchTerm=$request->exp_pay_to;
            $data->where(function ($q) use ($searchTerm) {
                $q->Where('account_statement.pay_to_txt', $searchTerm );
            });
        }  

        if(isset($request->exp_pay_mode)) {
            $searchTerm=$request->exp_pay_mode;
            $data->where(function ($q) use ($searchTerm) {
                $q->Where('account_statement.pay_mode', $searchTerm );
            });
        }  
      

        $data = $data->get();
        $array = [];

        $currDate = now()->toDateString();
        foreach($data as $items) {
            $user_crt_at = Carbon::parse($items->created_at)->toDateString(); 

            if($request->exp_from && $request->exp_to){
                if($user_crt_at>=$request->exp_from && $user_crt_at<=$request->exp_to){
                    $array[] = $items;
                }
            } else if($request->exp_from) {
                if($user_crt_at>=$request->exp_from && $user_crt_at<=$currDate){
                    $array[] = $items;
                }
            } else{
                $array[] = $items;
            }
        }
        
        return response()->json(['amt'=>$array]);
        
    }
}