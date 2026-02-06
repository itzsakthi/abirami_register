<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Yelamentryform;
use App\Models\Yelamthings;
use Log;
use DB;
use Illuminate\Support\Str;

class registerController extends Controller
{
    public function yelamentryform(){


        $data = \DB::table('registers')->selectRaw('pulliid,name,whatsappnumber,native,reference')->orderBy('pulliid','asc')->get();
        // $data = \DB::table('registers')->orderBy('pulliid','asc')->get();
        $reference = \DB::table('registers')->selectRaw('reference')->where('reference',"!=",'')->orderBy('pulliid','asc')->get();
      
        $products = \DB::table('yelamthings')->orderBy('things','asc')->get();
        return view('office.yelamentryform',['data' => $data,'products' => $products,'reference' => $reference]);
        
    }

    

    public function whatsapp(Request $request,$id){
      if($_SERVER['HTTP_HOST']=="singaravelar.templesmart.in"){
        return redirect()->route('allmember')->with('message', 'Whatsapp Message Failed for Singaravelar');;

      }
        $data = \DB::table('registers')->where('id',$id)->first();
        if($_SERVER['HTTP_HOST']=="durgaiamman.templesmart.in"){


          $curl = curl_init();

          curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.smithworks.online/api/meta/vplandurkovil',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'token=ayfz6TRcyr7iEv4gs04ZuE2hit8fSVloAb586S2CtjyEQKv6MEkqLk88mhBbghmX&pulliid='.$data->pulliid.'&karai='.$data->karai.'&wmobileno='.$data->whatsappnumber.'&name='.$data->name.'&native='.$data->native,
            CURLOPT_HTTPHEADER => array(
              'Content-Type: application/x-www-form-urlencoded',
              'Cookie: XSRF-TOKEN=eyJpdiI6InFpUnAwcWhWNmJlSURSS2FWK0dpVUE9PSIsInZhbHVlIjoicTcwV1RjVXlIZnIwXC9lMzFKeGNaODUzWlVKZnFhazdObDZxaDQ5SW5rU21MeDNUTGpmMHlyN0ltWStZalRZSWZZanlScmFiN1VPdk9XQjk4dktobnRRPT0iLCJtYWMiOiIzYTA5ZmU2OGIwMjE0YWY2YWI4MjRlZDVhOTk4Mzg1Mzk1M2U5ZTczYTFmMWNjZTA2YmJiMmY0MGFkMTI5YTUxIn0%3D; laravel_session=eyJpdiI6Ik4zc2VyU1F0a0UzQnVqMnFwXC9LY3V3PT0iLCJ2YWx1ZSI6IldXc3RrdVFoazN4QjRQZWlcL21OaTNDcGxIR1Joakk4UTl4REZWTWtuXC81MGZWUG9YdkF4d0NuMnlndWE0Ynd2NDZENGtkXC84VndnOUlZcjduT1R4bHJBPT0iLCJtYWMiOiI5ZDdkZjE2OTFlZjcyNDFjOTZmNmIyM2FhZjQwZWYxNmI2YWFiZjVhMmEzZTlhZjhjMzBiMjFmMTRjYzUwZmVjIn0%3D'
            ),
          ));

          $response = curl_exec($curl);

          curl_close($curl);

        } else {

          if ($_SERVER['HTTP_HOST'] == "napvm.templesmart.in") {

            $data = DB::table('registers')
            ->where('id', $id) 
            ->first();
    
              $params = implode(',', [
                    'Nagammai KOVIL',
                    'Nagammai KOVIL',
                    'info@nagammai.com',
                    '9876543210',
                    $data->name ?? ' ',
                    $data->karai ?? ' ',
                    $data->fathername ?? ' ',
                    $data->spousename ?? ' ',
                    $data->whatsappnumber ?? ' ',
                    $data->spousenumber ?? ' ',
                    $data->email ?? ' ',
                    $data->address ?? ' ',
                    $data->native ?? ' '
                ]);
                $whatsapp_template = 'soniya_0710';

                \Log::info('Params once loaded: ' . $params);


                $url = "http://bhashsms.com/api/sendmsg.php?" . http_build_query([
                    'user'     => 'SonaiyaBWA',
                    'pass'     =>  123456,
                    'sender'   => 'BUZWAP',
                    'phone'    => $data->whatsappnumber, 
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
         
  
        }
        
    return redirect()->route('allmember')->with('message', 'Whatsapp Message Successfully');
  }

  public function yellamvalidate(Request $request){
    
    $rules = [
      'yelamtype' => 'required|in:inhouse,external',
  
      'yelamporul' => 'required|string',
      'value' => 'required|numeric',
      'pulliid' => 'required|string',
      'name' => 'required|string',
      'whatsappno' => 'required|digits:10',
      'native' => 'required|string',
      'bookid' => 'required|string',

      'nameguest' => 'required_if:yelamtype,external|nullable|string',
      'whatsappnoguest' => 'required_if:yelamtype,external|nullable|digits:10',
      'nativeguest' => 'required_if:yelamtype,external|nullable|string',
    ];
  
    $message=[
      'yelamtype.required' => 'Kindly Choose a option',

      'yelamporul.required' => 'Kindly Choose a yellamporul',
      'value.required' => 'Kindly Enter a value',
      'value.numeric' => 'value Should be in numeric',

      'pulliid.required' => 'Kindly search using Pulli Id or Mobile No',
      'name.required' => 'Kindly Enter your Name',
      'whatsappno.required' => 'Kindly Enter your Mobile no',
      'native.required' => 'Kindly Enter your Native place',


      'bookid.required' => 'Kindly Enter the Manual Book no',
      
      'nameguest.required_if' => 'Kindly Enter Guest name',
      'whatsappnoguest.required_if' => 'Kindly Enter the Guest Mobile no',
      'whatsappnoguest.digits' => 'Kindly Enter numeric no upto 10',

      'nativeguest.required_if' => 'Kindly Enter Guest native place',
      
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

  public function yelamentrystore(Request $request){

    $yelamtype = $request->input('yelamtype');

    $yelamporul = $request->input('yelamporul');
    $value = $request->input('value');
    $pulliid = $request->input('pulliid');
    $name = $request->input('name');
    $whatsappno = $request->input('whatsappno');
    $native = $request->input('native');
    $bookid = $request->input('bookid');
    $remark = $request->input('remark');

    $nameguest = $request->input('nameguest');
    $whatsappnoguest = $request->input('whatsappnoguest');
    $nativeguest = $request->input('nativeguest');
    $reference = $request->input('reference');
    $payment = $request->input('payment');

    $increment_value=DB::table('yelamentryforms')->orderby('id','desc')->value('receipt_id');
    $rcpt=(int)$increment_value ?? 00;
    $receiptid = ($rcpt+1);
    
    $memember = DB::table('yelamentryforms')->insert([
        'yelamtype' => $yelamtype,
        'pulliid' => $pulliid,
        'yelamporul' => $yelamporul,
        'value' => $value,
        'name' => $name,
        'whatsappno' => $whatsappno,
        'native' => $native,
        'nameguest' => $nameguest,
        'whatsappnoguest' => $whatsappnoguest,
        'bookid' => $bookid,
        'receipt_id'=>$receiptid,
        'reference' => $reference,
        'remark' => $remark,
        'nativeguest' => $nativeguest,
        'payment' => $payment,
        'created_at' => now(),
        'updated_at' => now(),

    ]);
    
    $products = \DB::table('yelamthings')->where('id',$yelamporul)->first();

  
  if($_SERVER['HTTP_HOST']=="durgaiamman.templesmart.in"){
    if($whatsappnoguest!=''){
    
    
      $curl = curl_init();
  
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.smithworks.online/api/meta/vplandurelam',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'token=ayfz6TRcyr7iEv4gs04ZuE2hit8fSVloAb586S2CtjyEQKv6MEkqLk88mhBbghmX&product='.$products->things.'&template=dur_yelam_guest&name='.$nameguest.'&amount='.$value.'&pullidetails='.$pulliid.'&ref='.$name.'&mobileno='.$whatsappnoguest.'&to='.$whatsappnoguest.'&native='.$nativeguest.'&email=NA&add=NA&native='.$nativeguest.'&payment='.$payment.'&book='.$bookid,
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/x-www-form-urlencoded'
        ),
      ));
      
      $response = curl_exec($curl);
      
      curl_close($curl);
      // next
      
          $curl = curl_init();
      
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.smithworks.online/api/meta/vplandurelam',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'token=ayfz6TRcyr7iEv4gs04ZuE2hit8fSVloAb586S2CtjyEQKv6MEkqLk88mhBbghmX&product='.$products->things.'&template=dur_yelam_guest_new&name='.$nameguest.'&amount='.$value.'&pullidetails='.$pulliid.'&ref='.$name.'&mobileno='.$whatsappnoguest.'&to='.$whatsappno.'&native&native='.$nativeguest.'&email=NA&add=NA&native='.$nativeguest.'&payment='.$payment.'&book='.$bookid,
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/x-www-form-urlencoded'
        ),
      ));
      
      $response = curl_exec($curl);
      
      curl_close($curl);
    } 
    else {
          
        $karai = 'NA';
        $book = $bookid;

        $registerDataTemp = \DB::table('registers')->where('pulliid',$pulliid)->first();
        if($registerDataTemp!='' && isset($registerDataTemp->karai)){
          $karai =$registerDataTemp->karai;
        }
          $curl = curl_init();
      
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.smithworks.online/api/meta/vplandurelam',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'token=ayfz6TRcyr7iEv4gs04ZuE2hit8fSVloAb586S2CtjyEQKv6MEkqLk88mhBbghmX&product='.$products->things.'&template=dur_yelam_pulli_new&name='.$name.'&amount='.$value.'&pullidetails='.$pulliid.'&ref=1&mobileno='.$whatsappno.'&native='.$native.'&email=NA&add=NA&native='.$native.'&payment='.$payment.'&karai='.$karai.'&book='.$book,
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/x-www-form-urlencoded'
        ),
      ));
      
      $response = curl_exec($curl);
      
      curl_close($curl);
    }
    return response()->json(['status' => true, 'message' => 'Yelam Register Successfully']);
  
    } else if ($_SERVER['HTTP_HOST'] == "napvm.templesmart.in") {

      $project_name = 'Nagammai Temple';
      $month_year = now()->format('F Y');
      $yelam_thing = $products->things;
                                
      $karai = 'NA';
      $registerDataTemp = \DB::table('registers')->where('pulliid', $pulliid)->first();
      if ($registerDataTemp != '' && isset($registerDataTemp->karai)) {
        $karai = $registerDataTemp->karai;
      }

      if ($whatsappnoguest != '') {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'http://bhashsms.com/api/sendmsg.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>
                'user=SonaiyaBWA' .
                '&pass=123456' .
                '&sender=BUZWAP' .
                '&phone=' . $whatsappno .
                '&text=sonaiya_elam_moral_info' .
                '&priority=wa' .
                '&stype=normal' .
                '&Params=' . urlencode(
                    $project_name . ',' .
                    $month_year . ',' .
                    $yelam_thing . ',' .
                    $nameguest . ',' .
                    $value . ',' .
                    $whatsappnoguest . ',' .
                    $nativeguest . ',' .
                    $name . ',' .
                    $pulliid
                ),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            \Log::error('BhashSMS cURL error', [
                'error' => curl_error($curl)
            ]);
        } else {
            \Log::info('BhashSMS response no 1', [
                'response' => $response
            ]);
        }
        curl_close($curl);


        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'http://bhashsms.com/api/sendmsg.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>
                'user=SonaiyaBWA' .
                '&pass=123456' .
                '&sender=BUZWAP' .
                '&phone=' . $whatsappnoguest .
                '&text=sonaiya_elam_moral_info' .
                '&priority=wa' .
                '&stype=normal' .
                '&Params=' . urlencode(
                    $project_name . ',' .
                    $month_year . ',' .
                    $yelam_thing . ',' .
                    $nameguest . ',' .
                    $value . ',' .
                    $whatsappnoguest . ',' .
                    $nativeguest . ',' .
                    $name . ',' .
                    $pulliid
                ),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            \Log::error('BhashSMS cURL error', [
                'error' => curl_error($curl)
            ]);
        } else {
            \Log::info('BhashSMS response no 2', [
                'response' => $response
            ]);
        }

        curl_close($curl);
      } else {
         
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'http://bhashsms.com/api/sendmsg.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>
                'user=SonaiyaBWA' .
                '&pass=123456' .
                '&sender=BUZWAP' .
                '&phone=' . $whatsappno .
                '&text=sonaiya_elam_info' . 
                '&priority=wa' .
                '&stype=normal' .               
                '&Params=' . urlencode(
                    $project_name . ',' .
                    $month_year . ',' .
                    $yelam_thing . ',' .
                    $name . ',' .
                    $karai . ',' .
                    $value . ',' .
                    $pulliid . ',' .
                    $whatsappno . ',' .
                    $native
                ),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            \Log::error('BhashSMS cURL error', [
                'error' => curl_error($curl)
            ]);
        } else {
            \Log::info('BhashSMS response internal ', [
                'response' => $response
            ]);
        }
        curl_close($curl);


      }
  
  } else if($_SERVER['HTTP_HOST']!=="singaravelar.templesmart.in") {
    // return response()->json(['status' => true, 'message' => 'Member Register Successfully']);
    

    if($whatsappnoguest!=''){
    
    
      $curl = curl_init();
  
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.smithworks.online/api/meta/vplanelam',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'token=ayfz6TRcyr7iEv4gs04ZuE2hit8fSVloAb586S2CtjyEQKv6MEkqLk88mhBbghmX&product='.$products->things.'&template=elam_external&name='.$nameguest.'&amount='.$value.'&pullidetails='.$pulliid.'&ref='.$name.'&mobileno='.$whatsappnoguest.'&to='.$whatsappnoguest.'&native='.$nativeguest.'&email=NA&add=NA&native='.$nativeguest.'&payment='.$payment,
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/x-www-form-urlencoded'
        ),
      ));
      
      $response = curl_exec($curl);
      
      curl_close($curl);
      // next
      
          $curl = curl_init();
      
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.smithworks.online/api/meta/vplanelam',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'token=ayfz6TRcyr7iEv4gs04ZuE2hit8fSVloAb586S2CtjyEQKv6MEkqLk88mhBbghmX&product='.$products->things.'&template=elam_external&name='.$nameguest.'&amount='.$value.'&pullidetails='.$pulliid.'&ref='.$name.'&mobileno='.$whatsappnoguest.'&to='.$whatsappno.'&native&native='.$nativeguest.'&email=NA&add=NA&native='.$nativeguest.'&payment='.$payment,
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/x-www-form-urlencoded'
        ),
      ));
      
      $response = curl_exec($curl);
      
      curl_close($curl);
    } 
    else {
          
      
      $curl = curl_init();
    
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.smithworks.online/api/meta/vplanelam',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'token=ayfz6TRcyr7iEv4gs04ZuE2hit8fSVloAb586S2CtjyEQKv6MEkqLk88mhBbghmX&product='.$products->things.'&template=elam&name='.$name.'&amount='.$value.'&pullidetails='.$pulliid.'&ref=1&mobileno='.$whatsappno.'&native='.$native.'&email=NA&add=NA&native='.$native.'&payment='.$payment,
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/x-www-form-urlencoded'
        ),
      ));
          
      $response = curl_exec($curl);
        
      curl_close($curl);
    }
    
  }

    $id=DB::table('yelamentryforms')->selectRaw('yelamentryforms.*,yelamthings.things')
        ->leftJoin('yelamthings','yelamthings.id','yelamentryforms.yelamporul')->orderby('yelamentryforms.id','desc')->first();
          return response()->json(['status' => true,'id'=>$id,'message' => 'Yelam Register Successfully']);

            }
    
    
            public function Things(){
                $data = Yelamthings::orderBy('id','desc');


                return view('office.yelamthings', ['data'=>$data]);
            }

            public function yelamstore(Request $request){

                $rules=[
                    'things' => 'required|unique:yelamthings',
                ];
        
                $message=[
                    
                    'things.required' => 'Kindly Enter yelamtype',
                   
                ];
        
                $validator = Validator::make($request->all(),$rules,$message);
        if($validator->fails()){
        \Log::error($validator->errors()->all());
        return redirect()->back()->withErrors($validator)->withInput();
        }         
                    $things = $request->input('things');        
                    $memember = Yelamthings::create([
                        'things' => $things,
                        
                    ]);
        
                    return redirect()->route('yelamthings')->with('message', 'Yelam Register Successfully');;
                }

          public  function whatsappmessage(Request $request,$id){
            if($_SERVER['HTTP_HOST']=="singaravelar.templesmart.in"){
              return redirect()->back()->with('message', 'Whatsapp Message Failed for Singaravelar');;

            }

            $data = \DB::table('yelamentryforms')->where('id',$id)->first();

            $yelamtype =$data->yelamtype;

            $pulliid = $data->pulliid;
            $yelamporul = $data->yelamporul;
            $value = $data->value;
            $name = $data->name;
            $whatsappno = $data->whatsappno;
            $native = $data->native;
            $nameguest = $data->nameguest;
            $bookid = $data->bookid;
            $whatsappnoguest = $data->whatsappnoguest;
            $nativeguest = $data->nativeguest;
            $payment = $data->payment;

            $products = \DB::table('yelamthings')->where('id',$yelamporul)->first();
  

            if($whatsappnoguest!=''){
            
            
              $curl = curl_init();
          
              curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.smithworks.online/api/meta/vplandurelam',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'token=ayfz6TRcyr7iEv4gs04ZuE2hit8fSVloAb586S2CtjyEQKv6MEkqLk88mhBbghmX&product='.$products->things.'&template=dur_yelam_guest&name='.$nameguest.'&amount='.$value.'&pullidetails='.$pulliid.'&ref='.$name.'&mobileno='.$whatsappnoguest.'&to='.$whatsappnoguest.'&native='.$nativeguest.'&email=NA&add=NA&native='.$nativeguest.'&payment='.$payment.'&book='.$bookid,
                CURLOPT_HTTPHEADER => array(
                  'Content-Type: application/x-www-form-urlencoded'
                ),
              ));
              
              $response = curl_exec($curl);
              
              curl_close($curl);
              // next
              
                  $curl = curl_init();
              
              curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.smithworks.online/api/meta/vplandurelam',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'token=ayfz6TRcyr7iEv4gs04ZuE2hit8fSVloAb586S2CtjyEQKv6MEkqLk88mhBbghmX&product='.$products->things.'&template=dur_yelam_guest_new&name='.$nameguest.'&amount='.$value.'&pullidetails='.$pulliid.'&ref='.$name.'&mobileno='.$whatsappnoguest.'&to='.$whatsappno.'&native&native='.$nativeguest.'&email=NA&add=NA&native='.$nativeguest.'&payment='.$payment.'&book='.$bookid,
                CURLOPT_HTTPHEADER => array(
                  'Content-Type: application/x-www-form-urlencoded'
                ),
              ));
              
              $response = curl_exec($curl);
              
              curl_close($curl);
            } 
            else {
                  
                $karai = 'NA';
                $book = $bookid;

                $registerDataTemp = \DB::table('registers')->where('pulliid',$pulliid)->first();
                if($registerDataTemp!='' && isset($registerDataTemp->karai)){
                  $karai =$registerDataTemp->karai;
                }
                  $curl = curl_init();
              
              curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.smithworks.online/api/meta/vplandurelam',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'token=ayfz6TRcyr7iEv4gs04ZuE2hit8fSVloAb586S2CtjyEQKv6MEkqLk88mhBbghmX&product='.$products->things.'&template=dur_yelam_pulli_new&name='.$name.'&amount='.$value.'&pullidetails='.$pulliid.'&ref=1&mobileno='.$whatsappno.'&native='.$native.'&email=NA&add=NA&native='.$native.'&payment='.$payment.'&karai='.$karai.'&book='.$book,
                CURLOPT_HTTPHEADER => array(
                  'Content-Type: application/x-www-form-urlencoded'
                ),
              ));
              
              $response = curl_exec($curl);
              
              curl_close($curl);
            }
  




            
            
            return redirect()->route('yelamlist')->with('message', 'Whatsapp Message Successfully');;
          }
          
                  public function delete1($id){
                    DB::delete("delete from yelamentryforms where id=?",[$id]);
                    return redirect()->route('yelamlist')->with('message', 'Yelam Deleted Successfully');
                  }

                  public function delete($id)
                  {
                     $entry = Yelamentryform::find($id);
                      if ($entry) {
                      $entry->delete();
                       return redirect()->route('yelamlist')->with('message', 'Yelam Deleted Successfully');
                       } else {
                        return redirect()->route('yelamlist')->with('error', 'Yelam Not Found');
                      }
                 }
            }

