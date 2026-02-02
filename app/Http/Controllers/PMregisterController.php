<?php

namespace App\Http\Controllers;
use App\Models\PMRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Yelamentryform;
use App\Models\Yelamthings;
use App\Models\PaymentHistory;
use DB;

class PMregisterController extends Controller
{
    public function pmregisterform(){
        return view('office.pmregister');
    }

    public function pmvalidate(Request $request){
        $rules=[
            'pmid' => 'required|string',
            'name' => 'required|string',
            'spousename' => 'required|string',
            'whatsappnumber' => 'required_if:whatsappnumber,nullable|digits:10|numeric',
            'spousenumber' => 'required_if:spousenumber,nullable|digits:10|numeric',
            'familynickname' => 'required|string',
            'address'=> 'required|string',
            'remark'=> 'required|string',
            'reference'=> 'required|string', 
            'native' => 'required|string',
            
        ];

        $message=[
            'pmid. required' => 'Kindly Enter pmid',
            'name. required' => 'Kindly Enter Name',
            'spousename' => 'Kindly Enter SpouseName',
            'whatsappnumber.required_if' => 'Kindly Enter Whatsapp Number',
            'spousenumber.required_if' => 'Kindly Enter spousenumber',
            'familynickname. required' => 'kindly Enter familynickname',
            'address. required' => 'Kindly Enter address',
            'remark. required' => 'Kindly Enter remark',
            'reference. required' => 'Kindly Enter Reference',
            'native. required' => 'Kindly Enter native',

        ];

        $validator = Validator::make($request->all(),$rules,$message);
      if($validator->fails()){
        \Log::error($validator->errors()->all());
        return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
      }
      else{
        return response()->json(['status' => TRUE]);;
      }
    }

    public function pmregisterstore(Request $request){
        
        $pmid = $request->input('pmid');
        $name = $request->input('name');
        $spousename = $request->input('spousename');
        $whatsappnumber = $request->input('whatsappnumber');
        $spousenumber = $request->input('spousenumber');
        $familynickname = $request->input('familynickname');
        $address = $request->input('address');
        $remark = $request->input('remark');
        $reference = $request->input('reference');
        $native = $request->input('native');
        
        $pmmemember = Pmregister::create([
            'pmid' => $pmid,    
            'name' => $name,
            'spousename' => $spousename,
            'whatsappnumber' => $whatsappnumber,
            'spousenumber' => $spousenumber,
            'familynickname' => $familynickname,
            'address' => $address,
            'remark' => $remark,
            'reference' => $reference,
            'native' => $native,
        ]);
        return response()->json(['status' => true, 'message' => 'Submmitted successfully.']);
    }

    public function pmpay(Request $request) {
        //receipt generation
        $increment_value=DB::table('account_statement')->where('tot','PM')->orderby('id','desc')->value('receipt_id');
        $rcpt=(int)($increment_value) ?? 00;
        $receiptid = ($rcpt+1);


        DB::table('account_statement')->insert([
            'ref_id' => $request->pmid, 
            'ref_txt' => $request->pmname,
            'amount' => $request->amount,
            'pay_mode' => 'paid',
            'pay_to_txt'=>$request->year,
            'type'=>'INCOME',
            'tot' => 'PM',
            'remarks' => '--',
            'receipt_id'=>$receiptid,
            'created_at' => now(),
        ]);

        $pmdata=DB::table('account_statement')->where('ref_id',$request->pmid)
        ->where('pay_to_txt',$request->year)->orderby('id','desc')->first();

        return response()->json([
            'status'  => true,
            'message' => 'Payment recorded successfully',
            'pmdata'  => $pmdata
        ]);

    }

    public function pmmemberlist(Request $request)
    {
        $perPage = $request->items ?? 50; // Number of items per page, default to 50 if not provided
        $year = now()->year; // or from request if you add year filter later

        $data = Pmregister::leftJoin('account_statement as a', function ($join) use ($year) {
                $join->on('pmregisters.pmid', '=', 'a.ref_id')
                     ->where('a.type', 'INCOME')
                     ->where('a.tot', 'PM')
                     ->whereYear('a.created_at', $year);
               })
               ->select(
                   'pmregisters.*',
                   DB::raw('CASE WHEN a.id IS NULL THEN 0 ELSE 1 END as is_paid')
               )
               ->orderBy('pmregisters.id', 'desc')
               ->paginate($perPage);

            //    dd($data);
       
        return view('office.pmmemberList', [
           'data'  => $data,
           'items' => $perPage
        ]);
    }

    public function editpmmember(Request $request,$id){
    
        $data = Pmregister::find($id);
        return view('office.editpmmember',compact('data'));
    }

    public function updatepmmember(Request $request,$id){

        $rules=[
            'pmid' => 'required',
            'name' => 'required',
            'spousename' => 'required',
            'whatsappnumber' => 'required',
            'spousenumber' => 'required|digits:10',
            'familynickname' => 'required',
            'address'=> 'required', 
            'remark'=> 'required',
            'reference'=> 'required', 
            'native' => 'required',
        ];

        $message=[
            'pmid.required' => 'Kindly Enter pmid',
            'name.required' => 'Kindly Enter Name',
            'spousename.required' => 'Kindly Enter SpouseName',
            'whatsappnumber.required' => 'Kindly Enter Whatsapp Number',
            'spousenumber.required' => 'Kindly Enter spousenumber',
            'familynickname. required' => 'kindly Enter familynickname',
            'address.required' => 'Kindly Enter address',
            'remark.required' => 'Kindly Enter remark',
            'reference.required' => 'Kindly Enter Reference',
            'native.required' => 'Kindly Enter native',
        ];

        $validator = Validator::make($request->all(),$rules,$message);
    
        if($validator->fails()){
        \Log::error($validator->errors()->all());
        return redirect()->back()->withErrors($validator)->withInput();
        } 
        $pmmember = Pmregister::find($id);

        $pmid = $request->input('pmid');
        $name = $request->input('name');
        $spousename = $request->input('spousename');
        $whatsappnumber = $request->input('whatsappnumber');
        $spousenumber = $request->input('spousenumber');
        $familynickname = $request->input('familynickname');
        $address = $request->input('address');
        $remark = $request->input('remark');
        $reference = $request->input('reference');
        $native = $request->input('native');
            

        $pmmember->update([
            'pmid' => $pmid,
            'name' => $name,
            'spousename' => $spousename,
            'whatsappnumber' => $whatsappnumber,
            'spousenumber' => $spousenumber,
            'familynickname' => $familynickname,
            'address' => $address,
            'remark' => $remark,
            'reference' => $reference,
            'native' => $native,

        ]);
            
        // return response()->json(['status' => true, 'message' => 'Submmitted successfully.']);
        return redirect()->route('pmmemberlist')->with('message', 'PM Member Edit Successfully');;
   }

    public function pmexport(Request $request)
    {
        $data = Pmregister::orderBy('id', 'desc')->get();
        return response()->json(['data'=>$data]);
    }
}