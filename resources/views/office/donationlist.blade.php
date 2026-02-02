@extends('office.layout.layout')
@section('title', 'Income List')

@section('content')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.12.13/xlsx.full.min.js"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
        .text-xs.font-weight-bold.mb-0 {
            /* Example hover effect */ 
            padding: 5px; /* Padding for spacing */
            display: inline-block; /* Ensures the cursor affects the entire area */
        }

        .text-xs.font-weight-bold.mb-0:hover {
            background-color: #f0f0f0; /* Light gray background */
            border-radius: 4px; /* Rounded corners */
            cursor: pointer; /* Change cursor to pointer (hand) on hover */
        }

        input:focus {
            outline: none;
        }

        /* Default underline */
        .form-control {
            border: none;
            border-bottom: 1px solid #ccc;  /* light gray underline */
            border-radius: 0;
            box-shadow: none;
        }

        /* Focused underline darker pink */
        .form-control:focus {
            border-color: #c2185b;
            box-shadow: 0 1px 0 0 #c2185b;
        }

    </style>


    <div class="button-row " style= "text-align: end; margin-right:30px;">
        <a style="display: none;" id="incomeEntryBtn" class="btn bg-gradient-success"  href="#" ><i class="material-icons opacity-10">payments</i> Income Entry</a>
        <button type="button" id="saveExcel" class="btn btn-success" >Export All</button>           
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 style="font-weight:bold;" class="text-white text-capitalize ps-3">Income List</h6>
                            @if (session('message'))
                                <h6 id="session-message" class="text-white text-capitalize ps-3">{{ session('message') }}</h6>
                            @endif
                        </div>
                    </div>
                    <div class="p-4" >
                        <div class="d-flex justify-content-start">     
                            <form class="relative w-full max-w-5xl mx-auto mb-4 px-2 py-3 bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-xl border border-gray-200 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 backdrop-blur-sm">
                                <div class="relative z-10">
                                    <div class="flex items-center gap-4">
                                        <label for="income_type" class="ml-8 text-lg font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent dark:from-blue-400 dark:to-purple-400 uppercase tracking-wide whitespace-nowrap">
                                            Select Income Type
                                        </label> 
                                        <div class="relative group flex-1">
                                            <select id="income_type"
                                                class="appearance-none block w-full min-w-[400px] px-4 py-2 bg-gradient-to-r from-gray-100 to-white border-2 border-gray-300 rounded-xl shadow-inner focus:outline-none focus:ring-4 focus:ring-blue-500/30 focus:border-blue-500 text-sm text-gray-700 dark:bg-gradient-to-r dark:from-gray-800 dark:to-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-400/30 dark:focus:border-blue-400 transition-all duration-300 hover:border-blue-400 hover:shadow-lg font-medium pr-12 cursor-pointer group-hover:bg-gradient-to-r group-hover:from-blue-50 group-hover:to-purple-50">
                                                <option value="0" selected class="bg-white text-gray-900 font-medium py-2">ALL</option>
                                                <option value="1" class="bg-white text-gray-900 font-medium py-2">PULLIVARI</option>
                                                <option value="2" class="bg-white text-gray-900 font-medium py-2">DONATION</option>
                                                <option value="4" class="bg-white text-gray-900 font-medium py-2">YELLAM</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                                <svg class="w-5 h-5 text-blue-500 transform transition-transform duration-300 group-hover:rotate-180 group-hover:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                            <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-blue-400/20 to-purple-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute -top-1 -left-1 -right-1 h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-t-2xl opacity-0 hover:opacity-100 transition-opacity duration-300"></div>
                            </form>
                        </div>

                        <div  id='types' class="input-group input-group-dynamic mb-4">

                            <div class="input-group input-group-outline col-6">
                                <label class="form-label">Search here...</label>
                                <input id="search-input" type="text" class="form-control" value="{{ request('search') }}">
                            </div>
                        
                            <div class="table-responsive" style="width: 100%;">
                                <table class="table mb-0" style="width: 100%; text-align: center;" id="mytable">
                                    <thead>
                                        <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">SNO</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Pulli Id</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">NAME</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">MOBILE NO</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">TYPE</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Pay TYPE</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">AMOUNT</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">DESCRIPTION</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Print</th>
                                        </tr>
                                    </thead>
                                    <tbody>
        
                                        @php $ids = $data->firstItem(); @endphp
                                            @forelse($data as $room)
                                                <tr>
                                                    <td>
                                                        <p class="s_no_class text-xs  font-weight-bold mb-0"> {{ $ids }}</p>
                                                    </td>
                                                    @if($room->ref_id=='NA')
                                                    <td>
                                                        <p class="text-xs  font-weight-bold mb-0">Other</p>
                                                    </td>
                                                    @else
                                                    <td>
                                                        <p class="text-xs  font-weight-bold mb-0"> {{ $room->ref_id }}</p>
                                                    </td>
                                                    @endif
                                                    <td>
                                                        <p class="text-xs  font-weight-bold mb-0">{{ $room->ref_txt }}</p>
                                                    </td>
                                                    @if($room->ref_id !='NA' )
                                                    <td>
                                                        <p class="text-xs  font-weight-bold mb-0"> {{ $room->whatsappnumber }}</p>
                                                    </td>
                                                    @else
                                                    @php $no=explode("|||",$room->pay_to_txt)@endphp
                                                    <td>
                                                        <p class="text-xs  font-weight-bold mb-0"> {{ $no[0] }}</p>
                                                    </td>
                                                    @endif
                                                    <td class="income_type_class">
                                                        <p class="text-xs  font-weight-bold mb-0">
                                                            @if ($room->tot  != 'YELAM')
                                                                {{ $room->tot }}
                                                            @else
                                                                @if ($room->yelamtype == 'inhouse')
                                                                    {{ $room->tot }} (Inhouse)
                                                                @elseif ($room->yelamtype == 'external')
                                                                   {{ $room->tot }} (External)  
                                                                @endif
                                                            @endif
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs  font-weight-bold mb-0"> {{ $room->pay_mode }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs  font-weight-bold mb-0"> {{ $room->amount }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs  font-weight-bold mb-0"> {{ $room->remarks }}</p> 
                                                    </td>
                                                    @if($room->tot =='DONATION')
                                                    <td>  
                                                        <a href="{{ url('receipt/'.$room->id. '?donation={success}') }}" target="_blank">
                                                            <i class="fa fa-print text-success"></i> 
                                                        </a>
                                                    </td>
                                                    @elseif($room->tot =='PULLIVARI')
                                                    <td>  
                                                        <a href="{{ url('receipt/' . $room->ref_id . '?paytotxt=' . $room->pay_to_txt) }}" target="_blank">
                                                            <i class="fa fa-print text-success"></i> 
                                                        </a>
                                                    </td>
                                                    @else
                                                    <td>  
                                                        <a href="{{url('receipt/'.$room->yelam_id)}}" target="_blank">
                                                            <i class="fa fa-print text-success"></i> 
                                                        </a>
                                                       
                                                    </td>
                                                    @endif
                                                    
                        
                                                    @php  $ids++; @endphp
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center text-bold text-danger py-4">
                                                            No matching records found.
                                                        </td>
                                                    </tr>
                                                @endforelse 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="row" id="types2">
                            <div class="col-md-12 d-flex align-items-center justify-content-between">
                                <div  style= "margin-left:30px;margin-top:-10px">
                                    <form>
                                        <div class="input-group input-group-outline" >
                                            <div class="items-per-page d-flex align-items-center">
                                                <select id="pagination" class="form-control small-select" >
                    
                                                    <option value="" >Per Page</option>            
                                                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                                                    <option value="150" {{ $perPage == 150 ? 'selected' : '' }}>150</option>
                                                    <option value="200" {{ $perPage == 200 ? 'selected' : '' }}>200</option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div  style= "margin-right:40px;">
                                    {{ $data->appends(['items' => $perPage])->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
  
    </div>
    
    <div class="container-fluid py-4" id='yellam' style="display:none;margin-top:-130px;">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="p-4">
                        <div class="input-group input-group-dynamic mb-4">
                            <div class="input-group input-group-outline col-6">
                                <label class="form-label">Search here...</label>
                                <input id="yellam_search" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="card-header p-0 position-relative mt-4 mx-3 z-index-2">
                            <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 style="font-weight:bold;" class="text-white text-capitalize ps-3">Inhouse</h6>
                            </div>
                        </div>
                        <div class="table-responsive" style="width: 100%;">
                            <table class="table mb-0" style="width: 100%; text-align: center;" id="productTable">
                                <thead>
                                    <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">SNO</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Pulli Id</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Name</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Product</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">AMOUNT</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Paid</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Pending</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Pay</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Print</th>
                                </thead>
                                <tbody>
                                    {{-- @php $ids = 1; @endphp
                                                    
                                    @forelse($yellam_paid_inhouse as $room)
                                        <tr>
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $ids }}</p></td>
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $room->pulliid }}</p></td>
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $room->name }}</p></td>
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $room->yelamporul }}</p></td>
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $room->value }}</p></td>
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $room->paidtotal }}</p></td> 
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $room->value - $room->paidtotal }}</p></td> 
                                           
                                        
                                        </tr>
                                    @php $ids++; @endphp
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-danger py-4">
                                                No matching records found.
                                            </td>
                                        </tr>
                                    @endforelse --}}
                                    <tr>
                                        <td colspan="9" class="text-center text-danger py-4">
                                            Search for entries.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-header p-0 position-relative mt-4 mx-3 z-index-2 ">
                            <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 style="font-weight:bold;" class="text-white text-capitalize ps-3">External</h6>
                            </div>
                        </div>
                        <div class="table-responsive" style="width: 100%;">
                            <table class="table mb-0" style="width: 100%; text-align: center;" id="referralproductTable">
                                <thead>
                                    <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">SNO</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Pulli Id</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Guest Name</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Product</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">AMOUNT</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Paid</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Pending</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Pay</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Print</th>
                                   
                                </thead>
                                <tbody>
                                    {{-- @php $ids = 1; @endphp
                                                    
                                    @forelse($yellam_paid_external as $room)
                                        <tr>
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $ids }}</p></td>
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $room->pulliid }}</p></td>
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $room->name }}</p></td>
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $room->yelamporul }}</p></td>
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $room->value }}</p></td>
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $room->paidtotal }}</p></td> 
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $room->value - $room->paidtotal }}</p></td> 

                                        </tr>
                                    @php $ids++; @endphp
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4">
                                                No data found.
                                            </td>
                                        </tr>
                                    @endforelse --}}
                                    <tr>
                                        <td colspan="9" class="text-center text-danger py-4">
                                            Search for entries.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    
                    </div>
                </div>
            </div>
        </div>
            
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Paid Status</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="savepaymentform" action="{{ url('savepayment') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="form-group">
                                <label for="reference">Yelam Id</label>
                                
                                <input type="text" class="form-control" id="id" name="id"  readonly>
                            </div>
                            <div class="form-group">
                                <label for="reference">Name:</label>
                                
                                <input type="text" class="form-control" id="name" name="name" readonly>
                            </div>
                            <div class="form-group">
                                <label for="yelamporul">Yelamporul:</label>
                                
                                <input type="text" class="form-control" id="yelamporul" name="yelamporul"  readonly>
                            </div>
                            <div class="form-group">
                                <label for="reference">Receipt No:</label>
                                
                                <input type="text" class="form-control" id="reference" name="reference" required>
                            </div>
                            <div class="form-group">
                                <label for="yelamporul">Amount:</label>
                                
                                <input type="number" class="form-control" id="amount" name="amount"  required>
                            </div>
                            <div id="pending_years_container" class="mb-4 py-1">
                            </div>   
                            <div id="paid_years_container" class="mb-4 py-1">
                            </div> 
                            <div class="modal-footer">
                                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                                <button id="updatePr" type="submit" class="btn bg-gradient-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>

        document.addEventListener("DOMContentLoaded", function () {

            const incomeType = document.getElementById("income_type");

            const income_type = @json(session('income_type'));
            


             if(income_type == 'yellam_paid') {
                incomeType.value = "4";

                setTimeout(() => {
                    incomeType.dispatchEvent(new Event("change"));
                }, 0);

             }



            let yellam = @json(session('yellam'));
            let pulliid = @json(session('pulliid'));
            console.log(yellam,pulliid);
            const incomeEntryBtn = document.getElementById("incomeEntryBtn");


            let incomeTypeVal = document.getElementById("income_type").value;
            // console.log(incomeTypeVal);
            incomeEntryBtn.href = `/income_entry?inc_type=${incomeTypeVal}`;

            incomeType.addEventListener("change", function() {
                const incomeTypeVal = this.value;
                // console.log(incomeTypeVal);         
                incomeEntryBtn.href = `/income_entry?inc_type=${incomeTypeVal}`;
            })

            

            y_search = document.getElementById('yellam_search');
            y_search.value = pulliid; 

            if(yellam != null && pulliid != null) {
                incomeType.value = "4";
                incomeType.dispatchEvent(new Event("change"));

                     y_search.focus();

                     const evt = new KeyboardEvent("keypress", {
                        bubbles: true,
                        cancelable: true,
                        key: "Enter",
                        code: "Enter",
                        keyCode: 13,
                        which: 13
                    });

                    setTimeout(() => {
                        y_search.dispatchEvent(evt);
                    }, 100);

             }

            $(".modal").on("hidden.bs.modal", function(){
                $(".modal-body1").html("");
            });
            $('#exampleModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var id = button.data('id');
                var name = button.data('name');
                var reference = button.data('reference');
                var yelamporul = button.data('yelamporul');
                var yelamtype = button.data('yelamtype');
                var amount = button.data('amount');

                // Update modal inputs with the fetched data
                var modal = $(this);
                modal.find('#id').val(id);
                modal.find('#name').val(name);
                modal.find('#reference').val(reference);
                modal.find('#yelamporul').val(yelamporul);
                modal.find('#yelamtype').val(yelamtype);
                var host = "{{ $_SERVER['HTTP_HOST'] }}";
                console.log("Welcome! to "+host);
                $.ajax({
                    url: '/yelamlist',
                    method: 'get',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
        
                        // console.log(response);
                        const container = $('#pending_years_container');
                        const container1 = $('#paid_years_container');
                        container.empty();
                        container1.empty();
        
                        let total = amount;
                        container.append('<h4 class="form-check-label">Total Credited :</h4><br>');
                        container1.append('<h4 class="form-check-label">Yellam porul Total :</h4><br>');
                        const html = `
                                <div class="input-group input-group-static mb-2">
                                    <span class="d-block px-3 py-1 rounded" style="background-color: #ffe6f0; color: #cc0066; font-weight: bold;">
                                        Overall value  ⇒ ₹${total}
                                    </span>
                                </div>`;
                            
                        container1.append(html);
        
                        if (response.data) {
                            const totalCredit = response.data.total_credit || 0;
                            const html = `
                                <div class="input-group input-group-static mb-2">
                                    <span class="d-block px-3 py-1 rounded" style="background-color: #ffe6f0; color: #cc0066; font-weight: bold;">
                                        Total paid ⇒ ₹${totalCredit}
                                    </span>
                                    
                                </div>`;
                            container.append(html);
                            console.log(totalCredit);
                            total -= parseFloat(totalCredit);
                            
                        } else {
                            const html = `
                                <div class="input-group input-group-static mb-2">
                                    <p>No amount paid</p>
                                </div>`;
                            
                            container.append(html);
                            
                        }
                        modal.find('#amount').attr('max', total);
                        modal.find('#amount').val(total);
                    },
                    error: function (xhr) {
                        console.log("Error occurred: ", xhr.responseText);
                    }
                });
            });
            const searchInput = document.getElementById("yellam_search");

            if (searchInput) {

                searchInput.addEventListener("keypress", function (event) {
                    if (event.key === "Enter") {
                        const inputValue = searchInput.value;
                        console.log("Searching for:", inputValue);
                        $.ajax({
                            url: '/incomelist',
                            method: 'get',
                            data: {
                                id: inputValue,
                                
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                console.log("Response received:", response);
                                // Build the product table rows
                                let tbody = $("#productTable tbody");
                                let tbody1 = $("#referralproductTable tbody");
                                const baseUrl = "{{ url('receipt') }}/";
                                tbody.empty();
                                tbody1.empty();
                                var referraltotal=0;
                                var pullitotal=0;
                                var yellamtotal=0;
                                if (response.yellam_product && response.yellam_product.length > 0) {
                                    let ids = 1;
                                    let ids1=1;
                                    response.yellam_product.forEach(function(room) {
                                        
                                        if (!room.nameguest ) {

                                            let row = `
                                                <tr>
                                                <td> <p class="text-xs font-weight-bold mb-0">
                                                ${ids}</p></td>
                                                <td><p class="text-xs font-weight-bold mb-0">${room.pulliid}</p></td>
                                                <td><p class="text-xs font-weight-bold mb-0">${room.name}</p></td>
                                                <td><p class="text-xs font-weight-bold mb-0">${room.things ?? ''}</p></td>
                                                <td><p class="text-xs font-weight-bold mb-0">${room.value}</p></td>
                                                <td><p class="text-xs font-weight-bold mb-0">${room.paidtotal}</p></td>
                                                <td><p class="text-xs font-weight-bold mb-0">${room.value - room.paidtotal}</p></td>
                                                <td>
                                            `;
                                            if ((room.value - room.paidtotal !=0 )|| room.payment === 'Not Paid') {
                                                row += `
                                                    <p class="text-xs font-weight-bold mb-0" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                        data-id="${room.id}" data-name="${room.name}" data-yelamporul="${room.things}"
                                                        data-yelamtype="${room.yelamtype}" data-amount="${room.value}">
                                                        <i class="fa fa-rupee text-warning">Change Paid Status</i>
                                                    </p>
                                                `;
                                            } else{
                                                row += `
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        <i class="fa fa-check-circle text-success"></i>${room.payment}
                                                    </p>
                                                `;
                                            }

                                            row += `</td>
                                            <td><a href="${baseUrl}${room.id}" target="_blank">
                                                    <i class="fa fa-print text-success"></i> 
                                                </a>
                                            </td>
                                            </tr>`;
                                            
                                            ids++;
                                            tbody.append(row);
                                        }else{

                                            let row1 = `
                                                <tr>
                                                <td> <p class="text-xs font-weight-bold mb-0">
                                                ${ids1}</p></td>
                                                <td><p class="text-xs font-weight-bold mb-0">${room.pulliid}</p></td>
                                                <td><p class="text-xs font-weight-bold mb-0">${room.nameguest}</p></td>
                                                <td><p class="text-xs font-weight-bold mb-0">${room.things ?? ''}</p></td>
                                                <td><p class="text-xs font-weight-bold mb-0">${room.value}</p></td>   
                                                <td><p class="text-xs font-weight-bold mb-0">${room.paidtotal}</p></td>
                                                <td><p class="text-xs font-weight-bold mb-0">${room.value - room.paidtotal}</p></td>

                                                
                                                <td>
                                            `;
                                            if ((room.value - room.paidtotal !=0 )|| room.payment === 'Not Paid') {
                                                row1 += `
                                                    <p class="text-xs font-weight-bold mb-0" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                        data-id="${room.id}" data-name="${room.name}" data-yelamporul="${room.things}"
                                                        data-yelamtype="${room.yelamtype}" data-amount="${room.value}">
                                                        <i class="fa fa-rupee text-warning">Change Paid Status</i>
                                                    </p>
                                                `;
                                            } else {
                                                row1 += `
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        <i class="fa fa-check-circle text-success"></i>${room.payment}
                                                    </p>
                                                `;
                                            }

                                            row1 += `</td>
                                            <td><a href="${baseUrl}${room.id}" target="_blank">
                                                    <i class="fa fa-print text-success"></i> 
                                                </a>
                                            </td>
                                            </tr>`;
                                            ids1++;
                                            tbody1.append(row1);
                                        }
                                    });
                                } else {
                                    tbody.append('<tr><td colspan="9">No List found</td></tr>');
                                    tbody1.append('<tr><td colspan="9">No List found</td></tr>');
                                }
                                if (tbody.children("tr").length === 0) {
                                        tbody.append('<tr><td colspan="9">No List found</td></tr>');
                                    }
                                if (tbody1.children("tr").length === 0) {
                                    tbody1.append('<tr><td colspan="9">No List found</td></tr>');
                                }
                                
                            },
                            error: function(xhr) {
                                console.log("Error occurred: ", xhr.responseText);
                            }
                        });
                    }
                });
            } else {
                console.error("Search input not found!");
            }


        });

        $(document).ready(function(){
            // const productTable = $("#productTable tbody");
            // const yelinternalrows = productTable.find("tr");

            const urlParams = new URLSearchParams(window.location.search);
            const type = urlParams.get('type');
            if (type === 'yellam' && pulliid != null) {   
                $('#income_type').val('4').trigger('change');
            } 
            
            document.getElementById('pagination').onchange = function() {
                const url = new URL(window.location.href);
                url.searchParams.delete('items'); 
                url.searchParams.delete('page');
                url.searchParams.set('items', this.value);
                window.location.href = url.toString();
            };

            $("#saveAsExcel").click(function(){
                var workbook = XLSX.utils.book_new();
                var table = document.getElementById("mytable");
                var worksheet = XLSX.utils.table_to_sheet(table);
                var columnWidths = [
                    {wch: 10},
                    {wch: 60}, 
                    {wch: 40}, 
                ];
                var rowHeights = [
                    {hpx: 30}, 
                    {hpx: 30}, 
                ];
                worksheet['!cols'] = columnWidths;
                worksheet['!rows'] = rowHeights;
                workbook.SheetNames.push("VisitorList");
                workbook.Sheets["VisitorList"] = worksheet;
                exportExcelFile(workbook);
            });

            $("#saveExcel").click(function(){
                $.ajax({
                    url: 'incomelist', 
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'get',
                    success: function (response) {
                        console.log(response);
                        var incomeType = document.getElementById("income_type").value;
                        console.log("Selected incomeType: " + incomeType);

                        let filteredData = [];

                        if (incomeType == 1) {
                            filteredData = response.data
                                .filter(item => item.type === "PULLIVARI")
                                .map((item, index) => ({
                                    'S.No': index + 1,
                                    'Pulli ID': item.pulli_id,
                                    'Name': item.pullivari_name,
                                    'Mobile NO': item.mobile_no,
                                    'Type': item.type,
                                    'Amount': item.credit,
                                    'Description': item.description
                                }));
                        } else if (incomeType == 2) {
                            filteredData = response.data
                                .filter(item => item.type === "DONATION")
                                .map((item, index) => ({
                                    'S.No': index + 1,
                                    'Pulli ID': item.pulli_id,
                                    'Name': item.donation_name,
                                    'Mobile NO': item.mobile_no,
                                    'Type': item.type,
                                    'Amount': item.credit,
                                    'Type': item.donation_type,
                                    'Description': item.description
                                }));
                        } else if (incomeType == 3) {
                            filteredData = response.data
                                .filter(item => item.type === "OTHERS")
                                .map((item, index) => ({
                                    'S.No': index + 1,
                                    'Pulli ID': item.pulli_id,
                                    'Name': item.other_name,
                                    'Mobile NO': item.mobile_no,
                                    'Type': item.type,
                                    'Amount': item.credit,
                                    'Description': item.description
                                }));
                        } else {
                            filteredData = response.data.map((item, index) => ({
                                'S.No': index + 1,
                                'Pulli ID': item.pulli_id,
                                'Name': item.donation_name || item.pullivari_name || item.other_name || '',
                                'Mobile NO': item.mobile_no,
                                'Type': item.type,
                                'Donation Type': item.donation_type,
                                'Amount': item.credit,
                                'Description': item.description
                            }));
                        }

                        const ws = XLSX.utils.json_to_sheet(filteredData);
                        const wb = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(wb, ws, "list");
                        // Generate file and download
                        XLSX.writeFile(wb, "list.xlsx");   
                        
                    },
                    // error: function(xhr, status, error) {
                    //     console.error(xhr.responseText);
                    //     console.error("Status:", status);
                    //     console.error("Error:", error);
                    //     alert("An error occurred while fetching data from the database.");
                    // }
                });
            });
            //  <!-- For Filtering -->

            document.getElementById("income_type").addEventListener('change', (event) => {
                var selectedValue = event.target.value;
                            
                let pulli_count = 0;
                let donation_count = 0;
                let yellam_count = 0;
                 

                if(selectedValue) {
                    const rows = document.querySelectorAll('#mytable tbody tr');

                    let serialNo = 1;
                    rows.forEach(function(row) {

                        const typeCell = row.querySelector('.income_type_class');
                        const snoCell = row.querySelector('.s_no_class');

                        if (typeCell) {
                            const typeValue = typeCell.textContent.trim();

                             if (typeValue == 'PULLIVARI') {
                                pulli_count++;
                             } else if(typeValue == 'DONATION') {
                                donation_count++;
                             } 

                             
                             if (
                                selectedValue == 0 || 
                                (selectedValue == 1 && typeValue === "PULLIVARI") ||
                                (selectedValue == 2 && typeValue === "DONATION") || 
                                (selectedValue == 4 && (typeValue === "YELAM (Inhouse)" || typeValue === "YELAM (External)"))
                            ) {                               

                                    $('#types').show();
                                    $('#types2').show();
                                    $('#yellam').hide();
                                row.style.display = '';

                                  if (snoCell) {
                                    snoCell.textContent = serialNo++;
                                  }
                            } 
                            
                            else {
                                $('#types').show();
                                $('#types2').show();
                                $('#yellam').hide();
                                
                                row.style.display = 'none';
                            }
                            
                        } else {
                            row.style.display = 'none';
                        }
                    });

                };
            });
        })



        function exportExcelFile(workbook) {
            return XLSX.writeFile(workbook, "list.xlsx");
        }


        
        function deletemember(id) {
            Swal.fire({
                title: 'Are you sure you want to Delete this Yellam?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Yellam Deleted!',
                        text: 'The Yellam has been successfully Deleted.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    setTimeout(function() {
                        window.location.href = 'deletemember/' + id;
                    }, 2000);
                } else {
                }
            });
        }


    
        
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function () {

             const s_msg = document.getElementById("session-message");
                if (s_msg) {
                    setTimeout(() => {
                        s_msg.style.display = "none";
                    }, 3000); 
                }

            const incomeType = document.getElementById("income_type");
            const searchInput = document.getElementById("search-input");

            incomeType.addEventListener("change", (event) => {
                searchInput.value = '';
                const selectedValue = event.target.value;
            });

            setTimeout(() => {
                const urlParams = new URLSearchParams(window.location.search);
                const inc_type = urlParams.get("inc_type");

                if (inc_type === "pullivari") {
                    incomeType.value = "1";
                    incomeType.dispatchEvent(new Event("change"));
                } else if (inc_type === "donation") {
                    incomeType.value = "2";
                    incomeType.dispatchEvent(new Event("change"));
                } else if (inc_type === "others") {
                    incomeType.value = "3";
                    incomeType.dispatchEvent(new Event("change"));
                } else if (inc_type === "yellam") {
                    incomeType.value = "4";
                    incomeType.dispatchEvent(new Event("change"));
                }
            }, 0);
        });

    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("search-input");

            if (searchInput) {
                console.log("Search input found!");

                searchInput.addEventListener("keypress", function (event) {
                    if (event.key === "Enter") {
                        const inputValue = searchInput.value;
                        console.log("Searching for:", inputValue);

                        const url = new URL(window.location.href);
                        url.searchParams.set('search', inputValue);
                        window.location.href = url.toString();
                    }
                });
            } else {
                console.error("Search input not found!");
            }
        });

   
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("yellam_search");

            if (searchInput) {
                searchInput.addEventListener("input", function () {
                    const query = this.value.toLowerCase();

                    // Function to filter any table
                    function filterTable(tableId) {
                        const rows = document.querySelectorAll(`#${tableId} tbody tr`);
                        rows.forEach(row => {
                            const text = row.textContent.toLowerCase();
                            row.style.display = text.includes(query) ? "" : "none";
                        });
                    }

                    // Filter both tables
                    filterTable("productTable");
                    filterTable("referralproductTable");
                });
            }
        });
    </script>



@stop