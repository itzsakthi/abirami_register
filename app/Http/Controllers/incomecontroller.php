<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use Log;

class incomecontroller extends Controller
{
  public function income_entry(Request $request){
  
    $data = \DB::table('registers')->selectRaw('pulliid,name,whatsappnumber,address,native,reference')->orderBy('pulliid','asc')->get();
    $vari_amt = DB::table('pullivari_master')->get();
    return view('office.income_entry',compact('data','vari_amt'));
  }
  
  public function pulliidSearch(Request $request){
  
    $input_value = $request->vari_search;
    $others = $request->others;

    if($others == 'yes') {
      $othersPresent = true;
    } else {
      $othersPresent = false;
    }

    $data = \DB::table('registers')
    ->select('pulliid', 'name', 'whatsappnumber', 'address', 'native', 'reference')
    ->where(function ($q) use ($input_value) {
        $q->where('registers.pulliid', 'LIKE', '%' . $input_value . '%')
          ->orWhere('registers.whatsappnumber', 'LIKE', '%' . $input_value . '%')
          ->orWhere('registers.name', 'LIKE', '%' . $input_value . '%')
          ->orWhere('registers.familynickname', 'LIKE', '%' . $input_value . '%');
    })
    ->orderBy('pulliid', 'asc')
    ->get();

    return response()->json(['data'=>$data, 'othersPresent' => $othersPresent]);
  }

  

  public function paymentstatus(Request $request){
    
    $now=(explode(' ',now()));
    $curr_year=(string)($now[0]);
    // Log::info('current year = ' .$curr_year);

    $id=$request->id;
    
    //total user paid
    // $total_paid = DB::table('expenditure_enquiry')
    // ->where('pulli_id', $id)
    // ->where('type', 'PULLIVARI')
    // ->where('payment_status', 'paid')
    // ->select('pulli_id', DB::raw('SUM(credit) as total_credit'))
    // ->groupBy('pulli_id')
    // ->first();

    $paid_years = DB::table('account_statement')
    ->where('ref_id', $id)
    ->where('tot', 'PULLIVARI')
    ->where('pay_mode', 'paid')
    ->select('pay_to_txt')
    ->get();
    $get_paid_years="";
    if($paid_years){
      foreach ($paid_years as $x){
        $get_paid_years= "$get_paid_years $x->pay_to_txt";
      }
    }
    $check_paid_years = explode(' ',$get_paid_years);
    // Log::info('jsdjsndjsnd hello');
    // Log::info($get_paid_years);
    // Log::info($check_paid_years);
    Log::info($id);
    
    $Amt=[];
    $paidAmt = [];
    $vari_amt = DB::table('pullivari_master')->get();
    $user=\DB::table('registers')->select('created_at')->where('pulliid',$id)->first();
    
    if(!empty($user)){
      $created_year = explode(' ',$user->created_at);
      $user_year = $created_year[0];
      Log::info($user_year);
      foreach ($vari_amt as $data){
            
        if($data->by_annual !==null && $data->annual !==null  ){
          
          $check_year=$data->annual;
          $check_year1=$data->by_annual;
          if($user_year<=$check_year || $user_year<=$check_year1 ){
            if (in_array($check_year1,$check_paid_years ) || in_array($check_year,$check_paid_years )) {
              Log::info( "Found!");
              $paidAmt []=[
                'amt'=>$data->annual_amt,
                'year'=>($check_year." to ".$check_year1),
              ];
            } else {
                $Amt []=[
                  'amt'=>$data->annual_amt,
                  'year'=>($check_year." to ".$check_year1),
                ];
              }
          }
        }
      }
    }
    return response()->json(['user'=>$user,'amt'=>$Amt,'paidAmt'=>$paidAmt]);
  }

  public function income_validate(Request $request){
    $rules = [
      'itype' => 'required|in:PULLIVARI,DONATION,OTHERS',
  
      'vari_id' => 'required_if:itype,PULLIVARI|nullable|string',
      'vari_name' => 'required_if:itype,PULLIVARI|nullable|string',
      'vari_no' => 'required_if:itype,PULLIVARI|nullable|digits:10',
      'vari_address' => 'required_if:itype,PULLIVARI|nullable|string',
      'vari_value' => 'required_if:itype,PULLIVARI|nullable|numeric',
      'vari_DESCRIPTION' => 'required_if:itype,PULLIVARI|nullable|string',
  
      'pulli_id' => 'required_if:itype,DONATION|nullable|string',
      'name' => 'required_if:itype,DONATION|nullable|string',
      'no' => 'required_if:itype,DONATION|nullable|digits:10',
      'address' => 'required_if:itype,DONATION|nullable|string',
      'dtype' => 'required_if:itype,DONATION|nullable|string',
      'value' => 'required_if:itype,DONATION|nullable|numeric',
      'DESCRIPTION' => 'required_if:itype,DONATION|nullable|string',
  
      'other_name' => 'required_if:itype,OTHERS|nullable|string',
      'other_no' => 'required_if:itype,OTHERS|nullable|digits:10',
      'other_address' => 'required_if:itype,OTHERS|nullable|string',
      'other_value' => 'required_if:itype,OTHERS|nullable|numeric',
      'other_DESCRIPTION' => 'required_if:itype,OTHERS|nullable|string',
    ];
  
    $message = [
        'itype.required' => 'Kindly Choose a option',
    
        'vari_id.required_if' => 'Kindly Choose a option',
        'vari_name.required_if' => 'Kindly Enter Name field',
        'vari_no.required_if' => 'Kindly Enter Mobile no field',
        'vari_address.required_if' => 'Kindly Enter Address field',
        'vari_value.required_if' => 'Kindly Enter a Amount',
        'vari_DESCRIPTION.required_if' => 'Kindly Enter the DESCRIPTION',
    
        'pulli_id.required_if' => 'Kindly Choose a option',
        'name.required_if' => 'Kindly Enter Name field',
        'no.required_if' => 'Kindly Enter Mobile no field',
        'address.required_if' => 'Kindly Enter Address field',
        'dtype.required_if' => 'Kindly Choose a option',
        'value.required_if' => 'Kindly Enter a Amount',
        'DESCRIPTION.required_if' => 'Kindly Enter the DESCRIPTION',
    
        'other_name.required_if' => 'Kindly Enter Name field',
        'other_no.required_if' => 'Kindly Enter Mobile no field',
        'other_address.required_if' => 'Kindly Enter Address field',
        'other_value.required_if' => 'Kindly Enter a Amount',
        'other_DESCRIPTION.required_if' => 'Kindly Enter the DESCRIPTION',
    ];    

    $validator = Validator::make($request->all(),$rules,$message);
    if($validator->fails()){
      \Log::error($validator->errors()->all());
      return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
    }
    else{
      return response()->json(['status' => TRUE]);
    }
    
  }

  public function income_store(Request $request){
   
    if ($request->vari_value ==0 && $request->itype === 'PULLIVARI'){
      return response()->json(['status' => true,'loc'=>'pulli', 'message' => 'No Payment Made.']);
    }
    
    if ($request->itype === 'PULLIVARI') {

            // $data1 = \DB::table('yelamentryforms')
            //       ->where('yelamentryforms.pulliid', $request->vari_id)
            //       ->orderBy('created_at','desc')->get();
      
            // dd($data1);
      $currentYear = now()->year;

      $data1 = \DB::table('yelamentryforms')
        ->where('yelamentryforms.pulliid', $request->vari_id)
        ->whereRaw('YEAR(created_at) != ?', [$currentYear])
        ->orderBy('created_at','desc')
        ->get();

        // dd($data1); 

        foreach ($data1 as $item) { 
            // $item->paidtotal = 0;
             // Get sum of credit
            $total = \DB::table('account_statement')
            ->where('pay_to_txt',$item->id)
            ->where('tot','YELAM')
            ->sum('amount');
            $total=(int)$total;
            if ($item->value-$total!=0 ) {   
              return response()->json(['status' => 'payyelam', 'message' => 'Pay yellam pending']);
                // $item->paidtotal = $total;    
            }
        }

      $selectedAmts = $request->input('selected_amts');
      // Log::info((array)($selectedAmts));
      $years='';
      foreach ($selectedAmts as $x=>$y){
        $years= "$years $x";
      }
      // Log::info(' '.$years);

      //pulli receipt id
      $increment_value=DB::table('account_statement')->where('tot','PULLIVARI')->orderby('id','desc')->value('receipt_id');
      $rcpt=(int)$increment_value ?? 00;
      $receiptid = ($rcpt+1);
      
      DB::table('account_statement')->insert([
          'ref_id' => $request->vari_id, 
          'ref_txt' => $request->vari_name,
          'amount' => $request->vari_value,
          'pay_mode' => 'paid',
          'pay_to_txt'=>$years,
          'type'=>'INCOME',
          'tot' => $request->itype,
          'remarks' => $request->vari_DESCRIPTION,
          'receipt_id'=>$receiptid,
          'created_at' => now(),
      ]);
      $re=$request->redirect;
      // if($request->query('type')=='getpopup'){
        // }
        // Log::info('redirect : '.$re);
      $data=DB::table('account_statement')->where('ref_id',$request->vari_id)
        ->where('pay_to_txt',$years)->orderby('id','desc')->first();
       
      return response()->json(['status' => true,'pulli'=>$data,'loc'=> $re ?? 'pulli', 'message' => 'Submmitted successfully.']);
    } 
    else if ($request->itype === 'DONATION') {
      
      //DONO receipt id
      $increment_value=DB::table('account_statement')->where('tot','DONATION')->orderby('id','desc')->value('receipt_id');
      $rcpt=(int)$increment_value ?? 00;
      $receiptid = ($rcpt+1);

      if($request->pulli_id =='NA'){
        DB::table('account_statement')->insert([
          'ref_id' => $request->pulli_id, 
          'ref_txt' => $request->name,
          'amount' => $request->value,
          'type'=>'INCOME',
          'tot' => $request->itype,
          'pay_to_txt'=>$request->no.'|||'.$request->address,
          'pay_mode' => $request->dtype,
          'remarks' => $request->DESCRIPTION,
          'receipt_id'=>$receiptid,
          'created_at' => now(),
        ]);
      }
      else{
        DB::table('account_statement')->insert([
          'ref_id' => $request->pulli_id, 
          'ref_txt' => $request->name,
          'amount' => $request->value,
          'type'=>'INCOME',
          'tot' => $request->itype,
          'pay_to_txt'=>'--',
          'pay_mode' => $request->dtype,
          'remarks' => $request->DESCRIPTION,
          'receipt_id'=>$receiptid,
          'created_at' => now(),
        ]);
      }
      Log::info("success");
      $data=DB::table('account_statement')->orderby('id','desc')->first();
      return response()->json(['status' => true,'dondata'=>$data,'loc'=>'don', 'message' => 'Submmitted successfully.']);
    } 
    else {
      return response()->json(['status' => false, 'message' => 'Error.']);
    }   
  } 
    
  public function incomelist(request $request){
    $perPage = $request->items ?? 50;
    $data = \DB::table('account_statement')
    ->select([
        'registers.whatsappnumber',
        'registers.spousenumber',
        'registers.pulliid',
        'registers.native',
        'registers.name',
        'account_statement.*',
        'yelamentryforms.yelamtype',
        'yelamentryforms.id as yelam_id',
    ])
    ->wherenot('account_statement.type','EXPENSE')
    // ->wherenot('account_statement.tot','YELAM')
    ->leftjoin('registers','account_statement.ref_id','=','registers.pulliid')
    ->leftjoin('yelamentryforms','account_statement.pay_to_txt','=','yelamentryforms.id')
    ->orderBy('account_statement.id','desc');
    
    if ($request->filled('search')) { 
      $searchTerm=$request->get('search');     
      $data->where(function ($q) use ($searchTerm) {
          $q->where('registers.name', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('registers.pulliid', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('registers.spousenumber', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('registers.whatsappnumber', 'LIKE', '%' . $searchTerm . '%');
      });
    }

    if($request->ajax()){
      $yellam_search = $request->id;
      $yellam = null;
      $yellam_product = [];


      if ($yellam_search) {
        if (str_contains((strtolower(trim($yellam_search))), 'yes-')) {
          $parts = explode('-', $yellam_search);
          $yellam = \DB::table('yelamentryforms')->selectRaw('yelamentryforms.*,yelamthings.things')
            ->Where('yelamentryforms.receipt_id', 'LIKE', $parts[1])
            ->leftJoin('yelamthings','yelamthings.id','yelamentryforms.yelamporul')
            ->leftJoin('registers','yelamentryforms.pulliid','registers.pulliid')
            ->first() ;
          $yellam->paidtotal = (int) \DB::table('account_statement')
             ->where('pay_to_txt', $yellam->id)
             ->where('tot', 'YELAM')
             ->sum('amount');
             $yellam_product=[$yellam];
          return response()->json(['yellam_product'=>$yellam_product]);
        }
        else{
          $yellam = \DB::table('yelamentryforms')
          ->select([
              'registers.whatsappnumber',
              'registers.spousenumber',
              'registers.pulliid',
              'registers.native',
              'registers.name',
              'yelamentryforms.*',
              'yelamthings.things',
          ])
          ->leftJoin('yelamthings','yelamthings.id','yelamentryforms.yelamporul')  
          ->leftJoin('registers','yelamentryforms.pulliid','registers.pulliid')
            ->Where('yelamentryforms.name', 'LIKE', '%' . $yellam_search . '%')
            ->orwhere('registers.pulliid', 'LIKE', '%' . $yellam_search . '%')
            ->orWhere('registers.email', 'LIKE', '%' . $yellam_search . '%')
            ->orWhere('registers.spousenumber', 'LIKE', '%' . $yellam_search . '%')
            ->orWhere('registers.whatsappnumber', 'LIKE', '%' . $yellam_search . '%');
          $yellam_product = $yellam->get() ;
          if($yellam_product){
            foreach($yellam_product as $item){
               $item->paidtotal = (int) \DB::table('account_statement')
                  ->where('pay_to_txt', $item->id)
                  ->where('tot', 'YELAM')
                  ->sum('amount');
            
            }
            // $yellam_product=\Db::table('yelamentryforms')->selectRaw('yelamentryforms.*,yelamthings.things')
            // ->where('pulliid',$yellam->pulliid)
            // ->leftJoin('yelamthings','yelamthings.id','yelamentryforms.yelamporul')
            // ->orderBy('id','desc')->get();
  
            
          }
        }
        return response()->json(['yellam_product'=>$yellam_product]);
      }
      else{
        $data = $data->get();
        return response()->json(['data' => $data]);
      }
    }
    
    $data = $data->paginate($perPage)->appends($request->query());

    if ($request->has('search') && $data->isEmpty() && $request->get('page', 1) > 1) {
      return redirect()->route('incomelist', [
          'search' => $request->get('search'),
          'items' => $perPage,
          'page' => 1
      ]);
    }

    // $yellam_data = \DB::table('yelamentryforms')
    //     ->selectRaw('yelamentryforms.*, yelamthings.things')
    //     ->leftJoin('yelamthings', 'yelamthings.id', '=', 'yelamentryforms.yelamporul')
    //     ->orderBy('id', 'desc')
    //     ->get();

    // foreach ($yellam_data as $item) { 
    //     $item->paidtotal = (int) \DB::table('account_statement')
    //         ->where('pay_to_txt', $item->id)
    //         ->where('tot', 'YELAM')
    //         ->sum('amount');
    // }

    // $yellam_paid = $yellam_data->filter(function ($item) {
    //     return $item->paidtotal > 0;
    // })->values();


    // $yellam_paid_inhouse = $yellam_paid->filter(function ($item) {
    //     return strtolower($item->yelamtype) === 'inhouse';
    // })->values();

    // $yellam_paid_external = $yellam_paid->filter(function ($item) {
    //     return strtolower($item->yelamtype) === 'external';
    // })->values();




    // dd($data);
    return view('office.donationlist',compact('data','perPage'));
  }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
