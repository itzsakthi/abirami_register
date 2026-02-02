<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Yelamentryform;
use App\Models\Yelamthings;
use Log;
use DB;
use Illuminate\Support\Str;
use Auth;

class expensecontroller extends Controller
{
    public function ExpenditureEntry(){

        $auth_name = Auth::user()->name;

        $data = \DB::table('registers')->selectRaw('pulliid,name,whatsappnumber,native,reference')->orderBy('pulliid','asc')->get();

        $expenditure = DB::table('expenditure_master')->get();
        return view('office.expenditure',compact('data','expenditure','auth_name'));
      
    }

    public function enquiryvalidate(Request $request){
      $rules = [
        'user_type' => 'required|in:master,entry',
    
        'name' => 'required_if:user_type,master|nullable|string',
        'DESCRIPTION' => 'required_if:user_type,master|nullable|string',
    
        'expense_name' => 'required_if:user_type,entry|nullable|string',
        'value' => 'required_if:user_type,entry|nullable|numeric',
        // 'pay_to' => 'required_if:user_type,entry|nullable|string',
        'Pay_name' => 'required_if:user_type,entry|nullable|string',
        'pay_mode' => 'required_if:user_type,entry|nullable|string',
        'remark' => 'required_if:user_type,entry|nullable|string',
        // 'authorized_by' => 'required_if:user_type,entry|nullable|string',
      ];
    
      $message=[
        'name.required_if' => 'Kindly Enter EXPENSES Name',
        'user_type.required_if' => 'Kindly Choose a option',
        'DESCRIPTION.required_if' => 'Kindly Enter the DESCRIPTION',
        'expense_name.required_if' => 'Kindly Choose Expense Name',
        'value.required_if' => 'Kindly Enter a value',
        'value.integer' => 'Kindly Enter number value',
        // 'pay_to.required_if' => 'Kindly Enter Pay to field',
        'Pay_name.required_if' => 'Kindly Enter Pay name field',
        'pay_mode.required_if' => 'Kindly Enter Pay mode',
        'remark.required_if' => 'Kindly Enter Remark',
        // 'authorized_by.required_if' => 'Kindly Enter Authorized by field',
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

    public function enquirystore(Request $request){
      if ($request->user_type === 'master') {
        DB::table('expenditure_master')->insert([
            'expenses_name' => $request->name, 
            'description' => $request->DESCRIPTION,
            'created_at' => now(),
        ]);
        Log::info("success");
        return response()->json(['status' => true, 'message' => 'Submmitted successfully.']);
      } 
      else if ($request->user_type === 'entry') {
        $name = DB::table('expenditure_master')->where('id', $request->expense_name)->value('expenses_name');
        $user = auth()->user();
        $Auth = $user->name;
        Log::info($name);

       
        DB::table('account_statement')
            ->insert([
                'type'=>'EXPENSE',
                'tot' =>'EXPENSE COST',
                'ref_id' => $request->pay_to, 
                'ref_txt'=> $name,
                'pay_to_txt' => $request->Pay_name,
                'amount' => $request->value,
                'pay_mode' => $request->pay_mode,
                'remarks' => $request->remark,
                'type'=>"EXPENSE",
                'receipt_id'=>"0",
                'created_at' => now()
            ]);
            Log::info("success");
            return response()->json(['status' => true, 'message' => 'Submmitted successfully.']);
          
        } 
        else {
          return response()->json(['status' => false, 'message' => 'Expense entry not found.']);
        }   
    } 

    public function expenditurelist(Request $request){
        $perPage = $request->items ?? 50;
        $data = \DB::table('account_statement')->where('type', 'EXPENSE')
        ->orderBy('id','desc');
        $data1 = \DB::table('expenditure_master')->orderBy('id','desc')->paginate($perPage);
        if ($request->ajax()) {
            $data = $data->get();
            return response()->json(['data' => $data,'data1'=>$data1->items()]);
        }
        $data = $data->paginate($perPage);
        return view('office.expenditurelist',compact('data','data1','perPage'));

    }

    public function delete_enquiry(Request $request, $id){
        
        \DB::table('expenditure_enquiry')
            ->where('id', $id)
            ->update([
                'del_flag'=>1
            ]);

        return redirect()->back()->with('success', 'Deleted successfully.');
    
    }
}
