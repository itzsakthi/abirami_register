<style>
    .user_profile_data{
        display: flex;
        border: 1px solid black;
        border-radius: 10px;
        padding:10px;
        justify-content:space-around;
        align-items:center;
    }


    .custom-table {
    width: 100%;
    border-collapse: collapse;
    font-family: sans-serif;
    }

    .custom-table th,
    .custom-table td {
    border: 1px solid #d1d5db; 
    padding: 12px 16px;
    text-align: left;
    vertical-align: middle;
    }

    .custom-table thead {
    background-color: #f3f4f6;
    }

    .custom-table tbody tr:nth-child(even) {
    background-color: #f9fafb; 
    }

    .custom-table th {
    font-weight: 600;
    color: #374151;
    }

</style>


<div style="display: none;">

    <div style="margin-left: 3%; margin-right: 3%" id="wholepage">

        <br><br>
        <div style="font-size: 24px; font-weight: bold; color: black;">User Profile : </div>

        <div class="user_profile_data" style="width: 98%">
            <div>
                <div id="detailed-title"></div>
                <div id="description"></div>
            </div>
            <div>
                <div>Overall Outstanding</div>
                <div>pullivari & yellam</div>
                <div id="pulliamt2"></div>
            </div>
            <div>
                <div>Referral Outstanding</div>
                <div>Yellam</div>
                <div id="yellamamt2"></div>
            </div>
        </div>


        <br><br>



            <table class="custom-table" id="" style="width:98%">                
                    <thead>
                        <tr>
                            <td colspan="6" style="border: none; padding: 10px 5px; font-size: 24px; font-weight: bold; color: black; background-color: white;">
                                Pullivari :
                            </td>
                        </tr>
                        <tr>
                            <!-- <h3>Pullivari : </h3> -->
                           
                            <th >SNO</th>
                            <th >Year</th>
                            <th >Amount</th>
                            <th >Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        @php $sno=1;@endphp
                        @foreach($finalData as $item)
                            <tr>
                        
                                
                                <td>  {{ $sno }} </td>
                                <td>  {{ $item['year'] }} </td>
                                <td>  {{ $item['amt'] }} </td>
                                <td>  {{ $item['status'] }} </td>
                                
                                
                            
                            </tr>
                        @php  $sno++;@endphp
                                
                        @endforeach
                    </tbody>
                    
            </table>
        <br><br>



        <table class="custom-table" id="" style="width:98%">
            <thead>
                <tr>
                    <td colspan="6" style="border: none; padding: 10px 5px; font-size: 24px; font-weight: bold; color: black; background-color: white;">
                        Yellam :
                    </td>
                </tr>
                <tr>                    
                    <th>SNO</th>
                    <th>Yellam product</th>
                    <th>AMOUNT</th>
                    <th>Pending</th>
                    <th>Paid</th>            
                    <th>Status</th>            
                </tr>
            </thead>
            <tbody>
                @php  $sno=1; @endphp 
                @foreach($data1 as $room)
                @php $y_amt=$room->value - $room->paidtotal ; @endphp
                @if(!$room->nameguest)
                        
                        <tr>
                            <td>  {{ $sno	 }} </td>
                            <td>  {{ $room->things }} </td>
                            <td>  {{ $room->value }} </td>
                            <td>  @php echo($room->value - $room->paidtotal) @endphp </td>
                            <td>  {{ $room->paidtotal }} </td>
                            @if ($y_amt != 0)
                            <td>  Not paid </td>
                            @else
                            <td>  Paid </td>
                            @endif

                        
                        </tr>
                    @php  $sno++;@endphp
                @else
            
                @endif
                
                @endforeach
            </tbody>
        </table>


        <br><br>


        <table class="custom-table" id="" style="width:98%">
            <thead>
                <tr>
                    <td colspan="6" style="border: none; padding: 10px 5px; font-size: 24px; font-weight: bold; color: black; background-color: white;">
                        Yellam Referral :
                    </td>
                </tr>
                <tr>
                    <th class="">SNO</th>
                    <th class="">Yellam product</th>
                    <th class="">AMOUNT</th>
                    <th class="">Pending</th>
                    <th class="">Paid</th>
                    <th class="">Status</th>
                
                </tr>
            </thead>
            <tbody>
                @php $sno=1; @endphp
                @foreach($data1 as $room)
                @if($room->nameguest)
                @php $y_amt=$room->value - $room->paidtotal ; @endphp
                    <tr>
                        <td>  {{ $sno	 }} </td>
                        <td>  {{ $room->things }} </td>
                        <td>  {{ $room->value }} </td>
                        <td>  @php echo($room->value - $room->paidtotal) @endphp </td>
                        <td>  {{ $room->paidtotal }} </td>
                        @if ($y_amt != 0)
                            <td>  Not paid </td>
                        @else
                            <td>  Paid </td>
                        @endif
                        
                    </tr>
                    @php  $sno++;@endphp
                    
                @endif
                    
                @endforeach
            </tbody>
        </table>

        <br><br>


        <table class="custom-table" id="" style="width:98%">
            <thead>
            <tr>
                <td colspan="6" style="border: none; padding: 10px 5px; font-size: 24px; font-weight: bold; color: black; background-color: white;">
                    Donation Referral :
                </td>
            </tr>
            <tr>
                <th class="">SNO</th>
                <th class="">Donation Name</th>
                <th class="">Donation Type</th>
                <th class="">AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            @php $ids1 = $data->firstItem();$sno=1; @endphp
                @foreach($data as $room)
                <tr>
                    <td>
                            {{ $sno}} 
                    </td>
                    </td>
                    <td>  {{ $room->ref_txt }} </td>
                    <td>  {{ $room->pay_mode }} </td>
                    <td>  {{ $room->amount }} 
                    </td>
                    
                </tr>
                @php     $ids1++; $sno++;@endphp
                    
                @endforeach
            
        </tbody>
        </table>
    </div>

</div>
 


<script>
    const text = document.getElementById('detailed-title');
    text.innerHTML={!! json_encode($iddata->name) !!};
    
    const details = document.getElementById('description');
    const ss={!! json_encode($iddata->pulliid) !!}+`<br>`+{!! json_encode($iddata->whatsappnumber) !!}+`<br>`+{!! json_encode($iddata->address) !!};
    details.innerHTML=ss;
    
    
    var pendingAmount = @json($pending_pulliamt_yellamamt);
    const pulli2 = document.getElementById('pulliamt2');
    pulli2.innerHTML = ( '₹' + (new Intl.NumberFormat("en-IN").format(pendingAmount)));
    
    var ryAmount = @json($referral_yellamamt);
    const yellam2 = document.getElementById('yellamamt2');
    yellam2.innerHTML = ( '₹' + (new Intl.NumberFormat("en-IN").format(ryAmount)));
</script>




  <!-- <script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('saveAsPdf').addEventListener('click', () => {
        

        const page = document.getElementById('wholepage');

        const pulliid = {!! json_encode($iddata->pulliid) !!};

        const filename = `Report_${pulliid}.pdf`;
        html2pdf().from(page).save(filename);
        });
    });
  </script> -->

