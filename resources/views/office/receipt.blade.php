<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receipt</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    
    <style>
        .status_show {
            position: relative;
            height: 100%;
            width: 100%;
            z-index: 100000;
            top: 0;
            left: 0; 
            transition: transform 0.3s ease-out;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .receipt {
            min-width: 600px;
            width: 600px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            position: relative;
        }
        .receipt img {
            width: 100%;
            height: auto;
            display: block;
            border: 2px solid #000;
        }
        .overlay-content {
            position: absolute;
            /* display: flex;
            justify-content: center;
            align-content: center; */
            top: 30px;
            left: 10%;
            width: 80%;
        }
        .title {
            text-align: center;
            flex: 1;
        }
        .dateid{
            display: flex;
            /* margin-top: 20px; */
            justify-content: space-between;
            align-items: flex-end;
        }
        .date {
            white-space: nowrap;
        }
        .receipt_id{
            white-space: nowrap;
        }
        .label {
            display: inline-block;
            padding-bottom:5px; 
            min-width: 100px;
        }
        .dotted {
            display: inline-block;
            border-bottom: 1px dotted #000;
            min-width: 100px;
        }
        .dotted_p{
            display: flex;
            flex-wrap: wrap;
            flex-direction: row;
            align-content: center;
            justify-content: center;
        }
        .footer {
            display: flex;
            /* margin-top: 6%; */
            justify-content: space-between;
            align-items: flex-end;
        }
        .amount-box {
            border: 1px solid #000;
            padding: 5px 15px;
            font-weight: bold;
        }
        .signature {
            text-align: right;
            font-size: 0.9em;
        }
        /* 
        .header {
            margin-top:-90px; 
            margin-bottom: 20px;
        }
        
        .number {
            text-align: center;
            font-weight: bold;
        }
        .body p {
            margin: 15px 0;

        }
        */
        @media print {
            @page {
                size: A5 landscape;
            
            }
            .status_show {
                page-break-before: always;
                page-break-inside: avoid;
            }
            .receipt {
                min-width: 0;
                width: 100%;
                
            }
            .overlay-content {
                /* margin-top: 35%; */
                font-size: 17px;
            }
        } 
        /* 
            .title{
                font-size: 20px;
            }
            .footer {
                margin-top: 40px;
            }
            .body p {
                white-space: nowrap;
                margin: 25px 0;
            }
        } */

    </style>
</head>
<body>
    @php 
        $data=$data ?? null;
        $total=$total ?? collect();
        $pulli_receipt =$pulli_receipt ?? null; 
        $donation_data=$donation_data ?? null;
        $other_data=$other_data ?? null 
    @endphp

{{-- yellam receipt --}}
    @if ($total->isNotEmpty())
    {{-- <h1>INFO:: print the page in landscape A5 and set even pages only</h1> --}}
    @foreach ( $total as $items )
    <div class="status_show">
        <div class="receipt">
            <img src="{{asset('images/bg.avif')}}" alt="">
            <div class="overlay-content">
                
                <div class="header">
                    {{-- <div class="number">எண். {{$data->id}} || {{$data->things}}&nbsp;
                        <i class="fa fa-rupee " style="font-size:14px;color:rgb(3, 180, 26);font-weight: bold;">({{$data->value}})</i></div> --}}
                    <div class="title">
                        
                        @if ($_SERVER['HTTP_HOST'] == "singaravelar.templesmart.in")
                            <h2>சிங்காரவேலர் படைப்பு வீடு டிரஸ்ட்</h2>
                            <h3>SINGARAVELAR PADAIPPU VEDU TRUST</h3>
                            <p>மேலைச்சிவிரி - 123456. புதுக்கோட்டை மாவட்டம்.</p>
                        @elseif ($_SERVER['HTTP_HOST'] == "napvm.templesmart.in")
                            <h2>நாகம்மை ஆயா படைப்புக் குழு</h2>
                            <h3>NAGAMMAI AAYA PADAIPPU KUZHU</h3>
                            <p>மேலைச்சிவபுரி - 622 403. புதுகை மாவட்டம்.</p>
                        @endif
                    </div>
                    @php $now=explode(' ',$items->created_at) @endphp
                    <div class="dateid">
                        <div class="date">தேதி: {{$now[0]}}</div>
                        <div class="receipt_id">ரசீது எண்:
                            @php echo('YELAM-'.$items->receipt_id. '-' . str_pad(($data->yelamporul), 5, '0', STR_PAD_LEFT)) @endphp</div>
                    </div>
                    <div class="body">
                        <p><span class="label">திருமதி/திரு </span> 
                            <span class="dotted">
                                <span class="dotted_p">{{$items->ref_txt}}</span>
                            </span> 
                            <span class="label">அவர்களிடமிருந்து ரூபாய்( </span>
                            <span class="dotted">
                                <span class="dotted_p">{{$items->amount}}</span>
                            </span>
                            <span class="label">மட்டும் ) ஏலத்தொகை நன்றியுடன் பெற்றுக் கொண்டோம்.</span> 
                        </p>
                        <p><span class="label">பொருள்&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</span> 
                            <span class="dotted">
                                <span class="dotted_p">{{$data->things}}</span>
                            </span>
                        </p>
                    </div>
                    <div class="footer">
                        <div class="amount-box">₹ {{$items->amount}} 
                            <span style="font-size: 10px;color:rgba(117, 117, 117, 0.808);">/-{{$data->value}}</span>
                        </div>
                        <div class="signature">
                            @if ($_SERVER['HTTP_HOST'] == "singaravelar.templesmart.in")
                                செயலர் கையொப்பம்
                            @elseif ($_SERVER['HTTP_HOST'] == "napvm.templesmart.in")
                                நாகம்மை ஆயா படைப்புக் குழுவிற்காக
                            @endif    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    
    <script>window.print();</script>

    @elseif($data)
    <div class="status_show">
        <div class="receipt">
            <img src="{{asset('images/bg.avif')}}" alt="">
            <div class="overlay-content">
                
                <div class="header">
                    
                    {{-- <div class="number">எண். {{$data->id}} || {{$data->things}}&nbsp;
                        <i class="fa fa-rupee " style="font-size:14px;color:rgb(3, 180, 26);font-weight: bold;">({{$data->value}})</i></div> --}}
                    <div class="title">
                        @if ($_SERVER['HTTP_HOST'] == "singaravelar.templesmart.in")
                            <h2>சிங்காரவேலர் படைப்பு வீடு டிரஸ்ட்</h2>
                            <h3>SINGARAVELAR PADAIPPU VEDU TRUST</h3>
                            <p>மேலைச்சிவிரி - 123456. புதுக்கோட்டை மாவட்டம்.</p>
                        @elseif ($_SERVER['HTTP_HOST'] == "napvm.templesmart.in")
                            <h2>நாகம்மை ஆயா படைப்புக் குழு</h2>
                            <h3>NAGAMMAI AAYA PADAIPPU KUZHU</h3>
                            <p>மேலைச்சிவபுரி - 622 403. புதுகை மாவட்டம்.</p>
                        @endif
                    </div>
                    
                    @php $now=explode(' ',$data->created_at) @endphp
                    <div class="dateid">
                        <div class="date">தேதி: {{$now[0]}}</div>
                        <div class="receipt_id">ரசீது எண்:  @php echo('YES-'.$data->receipt_id. '-' . str_pad(($data->yelamporul), 5, '0', STR_PAD_LEFT)) @endphp</div>
                    </div>
                    <div class="body">
                        <p><span class="label">எடுத்தவர் பெயர் </span> 
                            <span class="dotted">
                                <span class="dotted_p">{{$data->name}}</span>
                            </span> 
                        </p>
                        <p><span class="label">பொருள்&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</span> 
                            <span class="dotted">
                                <span class="dotted_p">{{$data->things}}</span>
                            </span>
                        </p>
                        <p><span class="label">ஏலத்தொகை&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</span> 
                            <span class="dotted">
                                <span class="dotted_p">{{$data->value}}</span>
                            </span>
                        </p>
                    </div>
                    <div class="footer">
                        <div class="amount-box">₹ {{$data->value}} 
                            <span style="font-size: 10px;color:rgba(117, 117, 117, 0.808);">/-{{$data->value}}</span>
                        </div>
                        <div class="signature">
                            @if ($_SERVER['HTTP_HOST'] == "singaravelar.templesmart.in")
                                செயலர் கையொப்பம்
                            @elseif ($_SERVER['HTTP_HOST'] == "napvm.templesmart.in")
                                நாகம்மை ஆயா படைப்புக் குழுவிற்காக
                            @endif    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>window.print();</script>

    {{-- pulli receipt --}}
    @elseif ($pulli_receipt)
    {{-- <h1>INFO:: print the page in landscape A5 and set even pages only</h1> --}}
    <div class="status_show">
        <div class="receipt">
            <img src="{{asset('images/bg.avif')}}" alt="">
            <div class="overlay-content">
                
                <div class="header">
                    {{-- <div class="number">எண். {{$data->id}} || {{$data->things}}&nbsp;
                        <i class="fa fa-rupee " style="font-size:14px;color:rgb(3, 180, 26);font-weight: bold;">({{$data->value}})</i></div> --}}
                    <div class="title">
                        @if ($_SERVER['HTTP_HOST'] == "singaravelar.templesmart.in")
                            <h2>சிங்காரவேலர் படைப்பு வீடு டிரஸ்ட்</h2>
                            <h3>SINGARAVELAR PADAIPPU VEDU TRUST</h3>
                            <p>மேலைச்சிவிரி - 123456. புதுக்கோட்டை மாவட்டம்.</p>
                        @elseif ($_SERVER['HTTP_HOST'] == "napvm.templesmart.in")
                            <h2>நாகம்மை ஆயா படைப்புக் குழு</h2>
                            <h3>NAGAMMAI AAYA PADAIPPU KUZHU</h3>
                            <p>மேலைச்சிவபுரி - 622 403. புதுகை மாவட்டம்.</p>
                        @endif
                    </div>
                    @php $now=explode(' ',$pulli_receipt->created_at) @endphp
                    <div class="dateid">
                        <div class="date">தேதி: {{$now[0]}}</div>
                        <div class="receipt_id">ரசீது எண்: @php echo('PV-'.$pulli_receipt->receipt_id. '-' . str_pad(($pulli_receipt->receipt_id), 5, '0', STR_PAD_LEFT)) @endphp</div>
                    </div>
                    <div class="body">
                        <p><span class="label">திருமதி/திரு </span> 
                            <span class="dotted">
                                <span class="dotted_p">{{$pulli_receipt->ref_txt}}</span>
                            </span> 
                            <span class="label">அவர்களிடமிருந்து புள்ளிவரியாக ரூபாய்( </span>
                            <span class="dotted">
                                <span class="dotted_p">{{$pulli_receipt->amount}}</span>
                            </span>
                            <span class="label">மட்டும் ) நன்றியுடன் பெற்றுக் கொண்டோம்.</span> 
                        </p>
                        <p><span class="label">வரி ஆண்டுகள் &nbsp;:</span> 
                            <span class="dotted">
                                <span class="dotted_p">{{$pulli_receipt->pay_to_txt}}</span>
                            </span>
                        </p>
                    </div>
                    <div class="footer">
                        <div class="amount-box">₹ {{$pulli_receipt->amount}} 
                            <span style="font-size: 10px;color:rgba(117, 117, 117, 0.808);">/-</span>
                        </div>
                        <div class="signature">
                            @if ($_SERVER['HTTP_HOST'] == "singaravelar.templesmart.in")
                                செயலர் கையொப்பம்
                            @elseif ($_SERVER['HTTP_HOST'] == "napvm.templesmart.in")
                                நாகம்மை ஆயா படைப்புக் குழுவிற்காக
                            @endif    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>window.print();</script>

    {{-- donation receipt --}}
    @elseif ($donation_data)
    {{-- <h1>INFO:: print the page in landscape A5 and set even pages only</h1> --}}
    <div class="status_show">
        <div class="receipt">
            <img src="{{asset('images/bg.avif')}}" alt="">
            <div class="overlay-content">
                
                <div class="header">
                    {{-- <div class="number">எண். {{$data->id}} || {{$data->things}}&nbsp;
                        <i class="fa fa-rupee " style="font-size:14px;color:rgb(3, 180, 26);font-weight: bold;">({{$data->value}})</i></div> --}}
                    <div class="title">
                        @if ($_SERVER['HTTP_HOST'] == "singaravelar.templesmart.in")
                            <h2>சிங்காரவேலர் படைப்பு வீடு டிரஸ்ட்</h2>
                            <h3>SINGARAVELAR PADAIPPU VEDU TRUST</h3>
                            <p>மேலைச்சிவிரி - 123456. புதுக்கோட்டை மாவட்டம்.</p>
                        @elseif ($_SERVER['HTTP_HOST'] == "napvm.templesmart.in")
                            <h2>நாகம்மை ஆயா படைப்புக் குழு</h2>
                            <h3>NAGAMMAI AAYA PADAIPPU KUZHU</h3>
                            <p>மேலைச்சிவபுரி - 622 403. புதுகை மாவட்டம்.</p>
                        @endif
                    </div>
                    @php  $now=explode(' ',$donation_data->created_at) @endphp
                    <div class="dateid">
                        <div class="date">தேதி: {{$now[0]}}</div>
                        <div class="receipt_id">ரசீது எண்:@php echo('DO-'.$donation_data->receipt_id. '-' . str_pad(($donation_data->receipt_id), 5, '0', STR_PAD_LEFT))@endphp</div>
                    </div>
                    <div class="body">
                        <p>
                            <span class="label">திருமதி/திரு </span> 
                            <span class="dotted">
                                <span class="dotted_p">{{$donation_data->ref_txt}} </span>
                            </span>
                            <span class="label">அவர்களிடமிருந்து நன்கொடை ரூபாய் </span> 
                            <span class="dotted">
                                <span class="dotted_p">{{$donation_data->amount}}</span>
                            </span>
                            <span class="label">மட்டும் நன்றியுடன்பெற்றுக்கொண்டோம்.</span>
                        </p>
                        <p><span class="label">நன்கொடை வகை &nbsp;:</span> 
                            <span class="dotted">
                                <span class="dotted_p">{{$donation_data->pay_mode}}</span>
                            </span>
                        </p>
                    </div>
                    <div class="footer">
                        <div class="amount-box">₹ {{$donation_data->amount}} 
                            <span style="font-size: 10px;color:rgba(117, 117, 117, 0.808);">/-</span>
                        </div>
                        <div class="signature">
                            @if ($_SERVER['HTTP_HOST'] == "singaravelar.templesmart.in")
                                செயலர் கையொப்பம்
                            @elseif ($_SERVER['HTTP_HOST'] == "napvm.templesmart.in")
                                நாகம்மை ஆயா படைப்புக் குழுவிற்காக
                            @endif    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>window.print();</script>

    @else
    
        @include('office.404')
    
    @endif
</body>
</html>

   

