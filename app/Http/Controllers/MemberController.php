<?php

namespace App\Http\Controllers;
use App\Models\Register;
use App\Models\PMRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use App\Http\Controllers\incomecontroller;
use App\Models\Yelamentryform;
use App\Models\Yelamthings;
use App\Models\PaymentHistory;
use DB;
use Log;

class MemberController extends Controller
{
    public function registerform(){
        return view('office.addNewAddOn');

    }
    
    public function dashboard(){

        $totalthings = yelamentryform::count();
        $totalmembers = Register::count();
        $totalpmmembers = Pmregister::count();
        $totalinhouse = yelamentryform::sum('value');
        $totalexternal = yelamentryform::orderBy('id','desc')->first();
        if($totalexternal!=''){
            $totalexternal = $totalexternal->value;
        } else {
            $totalexternal = 0;
        }
        $totalincome = DB::table('account_statement')->where('type','INCOME')->sum('amount') ?? 0;
        $totalexpenses = DB::table('account_statement')->where('type','EXPENSE')->sum('amount') ?? 0;

        return view('office.dashboard',compact('totalincome','totalexpenses','totalthings','totalmembers','totalinhouse','totalexternal','totalpmmembers'));
    }

    public function allmember(Request $request)
    {
        $perPage = $request->items ?? 50; // Number of items per page, default to 50 if not provided
        $searchTerm = $request->get('search');
        $data = Register::orderBy('id', 'desc');
        
        // Apply search filter
        if ($searchTerm) {
            $data->where('pulliid', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('name', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('native', 'LIKE', '%' . $searchTerm . '%')
            // ->orWhere('email', 'LIKE', '%' . $searchTerm . '%')
            // ->orWhere('fathername', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('whatsappnumber', 'LIKE', '%' . $searchTerm . '%');
        }

        $data = $data->paginate($perPage);
        return view('office.visitorsList', ['data' => $data,'items' => $perPage]);
    }
    
    public function yelamlist(Request $request){
        $perPage = $request->items ?? 50; 
        $searchTerm = $request->get('search');
        if($request->ajax()){
            
            $data = \DB::table('account_statement')
            ->where('pay_to_txt',$request->id)
            ->where('tot','YELAM')
            ->select( DB::raw('SUM(amount) as total_credit'))
            ->first();
            return response()->json(['data'=>$data]);
        }
        $data = \DB::table('yelamentryforms')->selectRaw('yelamentryforms.*,yelamthings.things')
        ->leftJoin('yelamthings','yelamthings.id','yelamentryforms.yelamporul')
        ->orderBy('id','desc');

        // Apply search filter
        if ($searchTerm) {
            if (str_contains((strtolower(trim($searchTerm))), 'yes-')) {
                $parts = explode('-', $searchTerm);
                $data = \DB::table('yelamentryforms')->selectRaw('yelamentryforms.*,yelamthings.things')
                    ->Where('yelamentryforms.receipt_id', 'LIKE', $parts[1])
                    ->leftJoin('yelamthings','yelamthings.id','yelamentryforms.yelamporul')
                    ->leftJoin('registers','yelamentryforms.pulliid','registers.pulliid')
                    ->paginate() ;
                    foreach($data as $i){

                        $i->paidtotal = (int) \DB::table('account_statement')
                            ->where('pay_to_txt', $i->id)
                            ->where('tot', 'YELAM')
                            ->sum('amount');
                    }
                    return view('office.yelamlist',['data'=>$data,'perPage'=>$perPage]);
                    // $data=$yellam;
                // return response()->json(['yellam_product'=>$yellam_product]);
            }
            else{

                $data->where('pulliid', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('native', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('whatsappnoguest', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('reference', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('whatsappno', 'LIKE', '%' . $searchTerm . '%');
            }
        }
        $data=$data->take(1000)->paginate($perPage);

        return view('office.yelamlist',['data'=>$data,'perPage'=>$perPage]);

    }
    


    public function userprofile_page(Request $request){
        // dd("RDGF");
        return view('office.userprofile_page');
    }



    public function userprofile(Request $request,$id){

        $data = \DB::table('account_statement')
        ->where('ref_id', $id)->where('tot','DONATION')
        ->orderBy('id','desc');
        // if ($request->ajax()) {
        //     $data = $data->get();
        //     return response()->json(['data' => $data]);
        // }
        $data = $data->paginate(50);

        $incomeController = new IncomeController();
        $paymentsdata = $incomeController->paymentstatus($request, $id);
        $array = $paymentsdata->getData(true);
        // dd($array);

        $iddata=\DB::table('registers')->where('pulliid', $id)->first();
        $iddataArray = (array) $iddata;
        
        $finalData = [];
        
        if (!empty($array['paidAmt'])) {
            foreach ($array['paidAmt'] as $paid) {
                $finalData[] = array_merge($iddataArray, [
                    'status'=>'paid',
                    'amt' => $paid['amt'],
                    'year' => $paid['year'],
                ]);
            }
        } 
        if (!empty($array['amt'])) {
            foreach ($array['amt'] as $paid) {
                $finalData[] = array_merge($iddataArray, [
                    'status'=>'not paid',
                    'amt' => $paid['amt'],
                    'year' => $paid['year'],
                ]);
            }
        }

        $pending_pullivari = 0;
        
        foreach ($finalData as $row) {
            if ($row['status'] === 'not paid') {
                $pending_pullivari += $row['amt'];
            }
        }
        // dd($pending_pullivari);
        
        // dd($finalData);

        
        // $iddata1= \DB::table('account_statement')
        // ->select(\DB::raw("
        //     ref_id,ref_txt,
        //     CONCAT(
        //        FLOOR(EXTRACT(YEAR FROM created_at) / 2) * 2,
        //         '-',
        //         FLOOR(EXTRACT(YEAR FROM created_at) / 2) * 2 + 1
        //     ) AS year_group,
        //     sum(amount) AS total
        // "))
        // ->where('ref_id', $id)
        // ->where('tot', 'PULLIVARI')
        // ->groupBy('year_group')
        // ->groupBy('ref_id')
        // ->groupBy('ref_txt')
        // ->orderBy('year_group', 'DESC')
        // ->paginate(50);

        // dd($iddata1);
        

        $data1 = \DB::table('yelamentryforms')->selectRaw('yelamentryforms.*,yelamthings.things')
            ->leftJoin('yelamthings','yelamthings.id','yelamentryforms.yelamporul')
            ->where('yelamentryforms.pulliid', $id)
            ->orderBy('created_at','desc')->get();
        foreach ($data1 as $item) { 
            $item->paidtotal = 0;
             // Get sum of credit
            $total = \DB::table('account_statement')
            ->where('pay_to_txt',$item->id)
            ->where('tot','YELAM')
            ->sum('amount');
            $total=(int)$total;
            if ($total) {   
                $item->paidtotal = $total;    
            }

        }
        // dd($data1);

         $pending_yellam = 0;          // non-referral
        $referral_yellamamt = 0;      // referral

        foreach ($data1 as $item) {
            $pending = $item->value - $item->paidtotal;

            if ($pending <= 0) {
                continue;
            }

            if ($item->nameguest) {
                // referral yellam
                $referral_yellamamt += $pending;
            } else {
                // normal yellam
                $pending_yellam += $pending;
            }
        }

        // total yellam (both)
        $total_pending_yellam = $pending_yellam + $referral_yellamamt;

        // optional overall pending (pullivari + yellam)
        $pending_pulliamt_yellamamt = $pending_pullivari + $pending_yellam;


            if ($request->ajax()) {
                return view(
                    'office.partials.userprofile_content',
                    compact(
                        'iddata','data','finalData','data1',
                        'pending_pullivari','pending_yellam',
                        'referral_yellamamt','total_pending_yellam',
                        'pending_pulliamt_yellamamt'
                    )
                );
            }

        
        return view('office.userprofile',compact('iddata','data','finalData','data1', 'pending_pullivari', 'pending_yellam', 'referral_yellamamt', 'total_pending_yellam', 'pending_pulliamt_yellamamt'));
        
        // return view('office.userprofile',compact('iddata','data','finalData','data1'));
        // return response()->json(['data'=>$data]);
    }
    public function registerstore(Request $request){

        $rules=[
            'pulliid' => 'required|unique:registers,pulliid',
            'name' => 'required',
            'whatsappnumber' => 'required',
            'native' => 'required',
            'address'=> 'required',
            // 'fathername' => 'required',
            // 'spousename' => 'required',
            // 'phonenumber' => 'required|',
            // 'spousenumber' => 'required|digits:10',
            // 'familynickname' => 'required',
            // 'email' => 'required',
            // 'reference'=> 'required', 
        ];

        $message=[
            'pulliid.required' => 'Kindly Enter pulliid',
            'name.required' => 'Kindly Enter Name',
            'whatsappnumber.required' => 'Kindly Enter Whatsapp Number',
            'native.required' => 'Kindly Enter native',
            'address.required' => 'Kindly Enter address',
            // 'fathername.required' => 'Kindly Enter FatherName',
            // 'spousename.required' => 'Kindly Enter SpouseName',
            // 'phonenumber.required' => 'Kindly Enter Phone Number',
            // 'spousenumber.required' => 'Kindly Enter spousenumber',
            // 'familynickname. required' => 'kindly Enter familynickname',
            // 'email.required' => 'Kindly Enter email',
            // 'reference.required' => 'Kindly Enter Reference',

        ];

        // $request->validate([
        //     'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // ]);

        $validator = Validator::make($request->all(),$rules,$message);

        // dd($validator);
     
if($validator->fails()){
\Log::error($validator->errors()->all());
return redirect()->back()->withErrors($validator)->withInput();
}         



// $imageName = time().'.jpeg';  

// $request->image->move(public_path('/images/profile/'), $imageName);

            $pulliid = $request->input('pulliid');
            $name = $request->input('name');
            $fathername = $request->input('fathername');
            $spousename = $request->input('spousename');
            $phonenumber = $request->input('phonenumber');
            $whatsappnumber = $request->input('whatsappnumber');
            $spousenumber = $request->input('spousenumber');
            $familynickname = $request->input('familynickname');
            $email = $request->input('email');
            $address = $request->input('address');
            if ($_SERVER['HTTP_HOST'] == "durgaiamman.templesmart.in") { 
                $karai = $request->input('karai');
            } else {
                $karai = '-';
            }

            $reference = $request->input('reference');
            $native = $request->input('native');


            $memember = Register::create([
                'pulliid' => $pulliid,
                'name' => $name,
                'fathername' => $fathername,
                'spousename' => $spousename,
                'phonenumber' => $phonenumber,
                'whatsappnumber' => $whatsappnumber,
                'spousenumber' => $spousenumber,
                'familynickname' => $familynickname,
                'email' => $email,
                'address' => $address,
                'karai' => $karai,
                'reference' => $reference,
                'native' => $native,
            ]);
            
        if ($_SERVER['HTTP_HOST'] == "napvm.templesmart.in") {
             
            $params = implode(',', [
                'Nagammai KOVIL',
                'Nagammai KOVIL',
                'info@nagammai.com',
                '9876543210',
                $name,
                $karai,
                $fathername,
                $spousename,
                $whatsappnumber,
                $spousenumber,
                $email,
                $address,
                $native
            ]);
            $whatsapp_template = 'soniya_0710';


            $url = "http://bhashsms.com/api/sendmsg.php?" . http_build_query([
                'user'     => 'SonaiyaBWA',
                'pass'     =>  123456,
                'sender'   => 'BUZWAP',
                'phone'    => $whatsappnumber, 
                'text'     => $whatsapp_template,
                'priority' => 'wa',
                'stype'    => 'normal',
                'Params'   => $params
            ]);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);

            if ($response === false) {
                \Log::error('BhashSMS cURL error', [
                    'error' => curl_error($ch)
                ]);
            } else {
                \Log::info('BhashSMS WhatsApp response (cURL) new : ', [
                    'response' => $response
                ]);
            }

            curl_close($ch);
        }


// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://api.smithworks.online/api/meta/vplankovil',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'POST',
//   CURLOPT_POSTFIELDS => 'token=ayfz6TRcyr7iEv4gs04ZuE2hit8fSVloAb586S2CtjyEQKv6MEkqLk88mhBbghmX&date=2024-05-15&name='.$name.'&fname='.$fathername.'&sname='.$spousename.'&mobileno='.$phonenumber.'&wmobileno='.$whatsappnumber.'&smobileno='.$spousenumber.'&email='.$email.'&add='.$address.'&native='.$native,
//   CURLOPT_HTTPHEADER => array(
//     'Content-Type: application/x-www-form-urlencoded',
//     'Cookie: XSRF-TOKEN=eyJpdiI6InFpUnAwcWhWNmJlSURSS2FWK0dpVUE9PSIsInZhbHVlIjoicTcwV1RjVXlIZnIwXC9lMzFKeGNaODUzWlVKZnFhazdObDZxaDQ5SW5rU21MeDNUTGpmMHlyN0ltWStZalRZSWZZanlScmFiN1VPdk9XQjk4dktobnRRPT0iLCJtYWMiOiIzYTA5ZmU2OGIwMjE0YWY2YWI4MjRlZDVhOTk4Mzg1Mzk1M2U5ZTczYTFmMWNjZTA2YmJiMmY0MGFkMTI5YTUxIn0%3D; laravel_session=eyJpdiI6Ik4zc2VyU1F0a0UzQnVqMnFwXC9LY3V3PT0iLCJ2YWx1ZSI6IldXc3RrdVFoazN4QjRQZWlcL21OaTNDcGxIR1Joakk4UTl4REZWTWtuXC81MGZWUG9YdkF4d0NuMnlndWE0Ynd2NDZENGtkXC84VndnOUlZcjduT1R4bHJBPT0iLCJtYWMiOiI5ZDdkZjE2OTFlZjcyNDFjOTZmNmIyM2FhZjQwZWYxNmI2YWFiZjVhMmEzZTlhZjhjMzBiMjFmMTRjYzUwZmVjIn0%3D'
//   ),
// ));

// $response = curl_exec($curl);

// curl_close($curl);


     return redirect()->route('allmember')->with('message', 'Yellam Registered Successfully');;



   
    }
    public function yelamthinglist(Request $request){

        
            $perPage = $request->items ?? 50; // Number of items per page, default to 50 if not provided
            $data = Yelamthings::orderBy('id', 'desc')->paginate($perPage);
            
            return view('office.yelamthinglist', ['data' => $data,'items' => $perPage]);
        

    } 

    public function slug(Request $request,$slug){
       
        $data = member::where('slug',$slug)->first();
        if($data==''){
            echo 'Profile Not found';
            exit();
        }
      return view('office.profile_aima',['data'=>$data]);
    

}

   public function editmember(Request $request,$id){
    
    $data = Register::find($id);
    return view('office.editMember',compact('data'));
   }



   public function updatemember(Request $request,$id){

    
    $rules=[
        
        'pulliid' => 'required|unique:registers,pulliid,' . $id,
        'name' => 'required',
        'whatsappnumber' => 'required',
        'address'=> 'required', 
        'native' => 'required',
        // 'fathername' => 'required',
        // 'spousename' => 'required',
        // 'phonenumber' => 'required',
        // 'spousenumber' => 'required|digits:10',
        // 'familynickname' => 'required',
        // 'email' => 'required',
        // 'karai'=> 'required',
        // 'reference'=> 'required', 
        


        

    ];

        $message=[
            'pulliid.required' => 'Kindly Enter pulliid',
            'name.required' => 'Kindly Enter Name',
            'whatsappnumber.required' => 'Kindly Enter Whatsapp Number',
            'address.required' => 'Kindly Enter address',
            'native.required' => 'Kindly Enter native',
            // 'fathername.required' => 'Kindly Enter FatherName',
            // 'spousename.required' => 'Kindly Enter SpouseName',
            // 'phonenumber.required' => 'Kindly Enter Phone Number',
            // 'spousenumber.required' => 'Kindly Enter spousenumber',
            // 'familynickname. required' => 'kindly Enter familynickname',
            // 'email.required' => 'Kindly Enter email',
            // 'karai.required' => 'Kindly Enter karai',
            // 'reference.required' => 'Kindly Enter Reference',

    ];

   

    $validator = Validator::make($request->all(),$rules,$message);

 
if($validator->fails()){
\Log::error($validator->errors()->all());
return redirect()->back()->withErrors($validator)->withInput();
}         



// $imageName = time().'.jpeg';  

// $request->image->move(public_path('/images/profile/'), $imageName);
$member = Register::find($id);


// if($request->image != ''){        
//     $path = public_path().'/images/profile/';

 
//     //code for remove old file
//     if($member->image != ''  && $member->image != null){
//          $file_old = $path.$member->image;
//          unlink($file_old);
//     }

//     //upload new file
//     $file = $request->image;
//     $filename = $file->getClientOriginalName();
//     $file->move($path, $filename);

// }




 
$pulliid = $request->input('pulliid');
$name = $request->input('name');
$fathername = $request->input('fathername');
$spousename = $request->input('spousename');
$phonenumber = $request->input('phonenumber');
$whatsappnumber = $request->input('whatsappnumber');
$spousenumber = $request->input('spousenumber');
$familynickname = $request->input('familynickname');
$email = $request->input('email');
$address = $request->input('address');
if ($_SERVER['HTTP_HOST'] == "durgaiamman.templesmart.in") { 
    $karai = $request->input('karai');
} else {
    $karai = '-';
}
$reference = $request->input('reference');
$native = $request->input('native');
        // $image = $imageName;

        

        $member->update([
            'pulliid' => $pulliid,
            'name' => $name,
            'fathername' => $fathername,
            'spousename' => $spousename,
            'phonenumber' => $phonenumber,
            'whatsappnumber' => $whatsappnumber,
            'spousenumber' => $spousenumber,
            'familynickname' => $familynickname,
            'email' => $email,
            'address' => $address,
            'karai' => $karai,
            'reference' => $reference,
            'native' => $native,

        ]);
        
 return redirect()->route('allmember')->with('message', 'Member Register Successfully');;



   }

   public function savepayment1(Request $request)
   {
       $data = \DB::table('yelamentryforms')->where('id', $request->id)->first();
   
       if (!$data) {
           return response()->json(['error' => 'Payment not found'], 404);
       }
   
       $updateData = [
           'pulliid' => $request->pulliid,
           'name' => $request->name,
           'reference' => $request->reference,
           'yelamporul' => $request->yelamporul,
           'yelamtype' => $request->yelamtype,
       ];
   
       // Perform the update
       \DB::table('yelamentryforms')->where('id', $request->id)->update($updateData);
   
       return back()->with('success','Payment Upload successfully');
   }


public function popup_receipt(Request $request ,$id){
    $selected_yellam_amts = json_decode($request->query('ys'), true);
    if ($selected_yellam_amts) {
        $finalResults = [];
        foreach ($selected_yellam_amts as $x=>$y){
            $data = \DB::table('yelamentryforms')->selectRaw('yelamentryforms.*,yelamthings.things')
                ->where('yelamentryforms.id', $x)
                ->leftJoin('yelamthings','yelamthings.id','yelamentryforms.yelamporul')
                ->first();
            $totals = \DB::table('account_statement')
                ->where('pay_to_txt',$x)
                ->where('amount',$y)
                ->where('tot','YELAM')
                ->get(); 
            if ($data) {
                $data->totals = $totals;
                $finalResults[] = $data;
            }
        }  
        return response()->json([
            'status' => true,
            'selectedAmts' => $finalResults,
            'message' => 'Payment updated successfully'
        ]);
    }
}
public function onlyyellamentryreceipt(Request $request ,$id ){
    $data = \DB::table('yelamentryforms')->selectRaw('yelamentryforms.*,yelamthings.things')
        ->where('yelamentryforms.id', $id)
        ->leftJoin('yelamthings','yelamthings.id','yelamentryforms.yelamporul')
        ->first();
        return view("office.receipt",compact('data'));
}

public function receipt(Request $request ,$id ) {

    $paytotxt=$request->query('paytotxt');
    $donation=$request->query('donation');
    $other=$request->query('other');
    //pullivari receipt
    if($paytotxt){
        // dd("SA");
        // $paytotxt=explode(' ',$paytotxt);
        $pulli_receipt=DB::table('account_statement')
        ->where('account_statement.ref_id', $id)
        ->Where('account_statement.pay_to_txt', 'LIKE', '%' . $paytotxt . '%')
        ->orderby('id','desc')->first();
        // dd($id,$paytotxt,$pulli_receipt);
        return view("office.receipt",compact('pulli_receipt',));
    }
    //donation receipt
    elseif($donation =='{success}'){
        $donation_data=DB::table('account_statement')
        ->where('account_statement.id', $id)
        ->orderby('id','desc')->first();
        // dd($id,$donation_data);
        return view("office.receipt",compact('donation_data',));
    }
    //yellam receipt
    else{
        $data = \DB::table('yelamentryforms')->selectRaw('yelamentryforms.*,yelamthings.things')
        ->where('yelamentryforms.id', $id)
        ->leftJoin('yelamthings','yelamthings.id','yelamentryforms.yelamporul')
        ->first();
        $total = \DB::table('account_statement')
        ->where('pay_to_txt',$id)
        ->where('tot','YELAM')
        ->orderby('id','desc')
        ->get(); 
        // dd($data,$total);
        return view("office.receipt",compact('data','total'));
    }
}

public function crosscheck($id,$amount){
    $total = \DB::table('account_statement')
        ->where('pay_to_txt',$id)
        ->where('tot','YELAM')
        ->sum('amount'); 
    // Log::info($total);
    // Log::info('amount = '.$amount);

    $data = \DB::table('yelamentryforms')
        ->where('id', $id)
        ->first();
    // Log::info((array)$data);
    $remaining=($data->value) - ($total);
    return response()->json(['balance'=>$remaining]);

}
public function savepayment(Request $request)
{
    if ($request->input('selected_yellam_amts')){
        $selectedAmts = $request->input('selected_yellam_amts');
        // Log::info((array)($selectedAmts));
        // return response()->json(['status'=>true,'selectedAmts'=>$selectedAmts,'success'=>'Payment updated successfully',
        //     'message' => 'Payment updated successfully']);
        
        foreach ($selectedAmts as $x=>$y){
            $remaining=$this->crosscheck($x,$y);
            $rem = $remaining->getData();
            // Log::info((array)$rem); 
            if(($rem->balance!=0)){
                $this->afterPayment($x,$y,'0'); 
            }
        }  
        return response()->json(['status'=>true,'selectedAmts'=>$selectedAmts,'success'=>'Payment updated successfully',
        'message' => 'Payment updated successfully']);
    }
    else{
        $remaining = $this->crosscheck($request->id,$request->amount,);
        $rem = $remaining->getData(); 
       
        if(($rem->balance!=0)){
            $result=$this->afterPayment($request->id,$request->amount,$request->reference);
            $res = $result->getData(); 
            if($res->success){
                // log::info($res->success);
                $yellam='yellam';
                $pulliid=$request->pulli_vari_id;

                $data=DB::table('account_statement')->selectRaw('account_statement.*,yelamentryforms.yelamporul,yelamthings.things')
            
                ->leftJoin('yelamentryforms','yelamentryforms.id','account_statement.pay_to_txt')
                ->leftJoin('yelamthings','yelamthings.id','yelamentryforms.yelamporul')
                ->orderby('account_statement.id','desc')->first();

                    
                
                return response()-> json(['status'=>true,'data'=>$data]);
                
             
            }
        }
            
        return back()->with('error','Try again')->with('message','Try again');
    }

 
}
public function afterPayment($id,$amount,$reference){
    //  $updateData = [
    //     'id' => $request->id,
    //     'name' => $request->name,
    //     'reference' => $request->reference,
    //     'yelamporul' => $request->yelamporul,
    //     'yelamtype' => $request->yelamtype,
    // ];
 
    //  \DB::table('yelamentryforms')->where('id', $request->id)->update($updateData);
 
    $paymentHistory = new PaymentHistory();
    $paymentHistory->amount = $amount;   
    $paymentHistory->yelam_id = $id; 
    $paymentHistory->save();
 
    
    
    // if($_SERVER['HTTP_HOST']!=="singaravelar.templesmart.in"){
        // Get sum of credit
        $total = \DB::table('account_statement')
            ->where('pay_to_txt',$id)
            ->where('tot','YELAM')
            ->sum('amount');
         // Get id value
        $data = \DB::table('yelamentryforms')->selectRaw('yelamentryforms.*,yelamthings.things')
            ->where('yelamentryforms.id', $id)
            ->leftJoin('yelamthings','yelamthings.id','yelamentryforms.yelamporul')
            ->first();
     
        $remaining=$data->value-($amount + $total);
        //  Log::info('remaning == ' .$remaining);
        //receipt generation
        $increment_value=DB::table('account_statement')->where('tot','YELAM')->orderby('id','desc')->value('receipt_id');
        $rcpt=(int)($increment_value) ?? 00;
        $receiptid = ($rcpt+1);

        $insert = DB::table('account_statement')->insert([
            'ref_id' => $data->pulliid,
            'ref_txt' => $data->name,
            'pay_to_txt' => $data->id,
            'amount' => $amount,
            'tot' => 'YELAM',
            'type' => 'INCOME',
            'pay_mode' => $reference ?? '',
            'remarks' => '--',
            'receipt_id'=>$receiptid,
            'created_at' => now(),
        ]);
        if($remaining==0){
            DB::table('yelamentryforms')
                ->where('id', $data->id)
                ->update(['payment' => 'paid']);
        }

    // }else{    
    //     $entryForm = Yelamentryform::find($id);
    //     $entryForm->payment = 'paid';
    //     $entryForm->reference = $request->reference;   
    //     $entryForm->save();
    // }
    
    $yelamporul = $data->yelamporul;
    // Log::info($yelamporul);
    $name = $data->name;
    $amount = $amount;
    $mobile = $data->whatsappno;
    $receipt = $reference;
    if($_SERVER['HTTP_HOST']=="singaravelar.templesmart.in") {

        return response()->json(['id' => $id .' - '.$receipt.' - '.$mobile .' - '.$data->whatsappnoguest,
            'success' => 'Payment updated successfully'.'--'.$amount.'--'.$yelamporul,
            'message' => 'Payment updated successfully']);
    }
    else{
        $curl = curl_init();
     
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.smithworks.online/api/meta/vplandurelampaid',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'token=ayfz6TRcyr7iEv4gs04ZuE2hit8fSVloAb586S2CtjyEQKv6MEkqLk88mhBbghmX&product='.$yelamporul.'&template=dur_paid&name='.$name.'&amount='.$amount.'&wmobileno='.$mobile.'&receipt='.$receipt,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        
        $response = curl_exec($curl);
    
        if($data->whatsappnoguest!=''){
           $mobile = $entryForm->whatsappnoguest;
        $curl = curl_init();
     
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.smithworks.online/api/meta/vplandurelampaid',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'token=ayfz6TRcyr7iEv4gs04ZuE2hit8fSVloAb586S2CtjyEQKv6MEkqLk88mhBbghmX&product='.$yelamporul.'&template=dur_paid&name='.$name.'&amount='.$amount.'&wmobileno='.$mobile.'&receipt='.$receipt,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        
        $response = curl_exec($curl);
        }
        
        curl_close($curl);
    
        return response()->json([
           'id' => $id,
           'success' => 'Payment updated successfully',
           'message' => 'Payment updated successfully'
       ]);
    }
    
}


 
}