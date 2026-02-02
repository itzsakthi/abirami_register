@extends('office.layout.layout')
@section('title', 'Income Entry')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .popupreceipt {
            position: fixed;
            background-color: #2b2a2ad2;
            height: 100%;
            width: 100%;
            z-index: 100000;
            top: 0;
            left: 0;
            transition: transform 0.3s ease-out;
            display: flex;
            overflow-y: scroll;    
            flex-direction: column;   
            align-items: center;
            justify-content: center;
            gap: 500px;
        }
    </style>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3"> Income Entry</h6>
                        
                        </div>
                    </div>
            

                    <div class="p-4">
                        <div class="">
                            
                            <form method="POST" id="income" enctype="multipart/form-data">
@csrf

                                <div class="input-group input-group-static mb-4">
                                        <label for="itype">Select type of Income </label>
                                        <select class="form-control" id="itype" name="itype">
                                        <option value="">Select a option</option>
                                        <option value="PULLIVARI">PULLIVARI</option>
                                        <option value="DONATION">DONATION</option>
                                        <option value="YELLAM">YELLAM</option>
                                        </select>
                                </div>

                                <div class="pulli hidden" style="display:none">

                                    <div class="input-group input-group-static mb-4 py-1 " id="pulliid_search_container" >
                                        <label for="variId_mobile_search">Search by Mobile No/ Pulli Id</label>
                                        <input type="text" class="form-control" id="variId_mobile_search" name="variId_mobile_search" required> <br>
                                    </div>

                                  


                                        <table class="table mb-0" style="display: none;  width: 100%; text-align: center;" id="pullvari_search_table">
                                            <thead>
                                                <tr>
                                                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Choose</th>
                                                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">SNO</th>
                                                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Pulli Id</th>
                                                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Name</th>
                                                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Mobile NO</th>
                                                <tr>                                            
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>

                                    
                                    <div class="input-group input-group-static mb-4 py-1 pvfield" style="display:none;" >
                                        <label for="vari_id">Pulli Id</label>
                                        <input type="text" class="form-control" id="vari_id" name="vari_id" > <br>
                                    </div>
                                    <div class="input-group input-group-static mb-4 py-1 nvfield" style="display:none;" >
                                        <label for="vari_name">NAME</label>
                                        <input type="text" class="form-control" id="vari_name" name="vari_name" > <br>
                                    </div>
                                    <div class="input-group input-group-static mb-4 py-1 mvfield" style="display:none;" >
                                        <label for="vari_no">Mobile.no</label>
                                        <input type="number" class="form-control" id="vari_no" name="vari_no" > <br>
                                    </div>
                                    <div class="input-group input-group-static mb-4 py-1 avfield" style="display:none;" >
                                        <label for="vari_address">Address</label>
                                        <input type="text" class="form-control" id="vari_address" name="vari_address" > <br>
                                    </div>
                                    <div id="amt-checkbox-container" class="mb-4 py-1"></div>   
                                    <div class="input-group input-group-static mb-4 py-1">
                                        <label for="vari_value">Value</label>
                                        <input type="number" class="form-control" id="vari_value" name="vari_value"> <br>                 
                                    </div>
                                    <div  class="input-group input-group-static mb-4 py-1">
                                        <label for="vari_DESCRIPTION">DESCRIPTION</label>
                                        <input type="text" class="form-control" id="vari_DESCRIPTION" name="vari_DESCRIPTION"> <br>
                                    </div>
                                    <input type="hidden" class="redirect" name="redirect">
                                    <div class="buttn_vari" style="display:flex; flex-direction:row; gap:10%;">
                                        <button type="submit" id="submit_vari" class="btn bg-gradient-success">Submit</button>
                                        <button type="button" onclick="history.back()" id="submitbtn_vari" class="btn bg-gradient-primary">Cancel X </button>
                                    </div>
                                </div>

                                <div class="donate hidden" style="display:none">

                                    <div class="input-group input-group-static mb-4 py-1 "  >
                                        <label for="pulliId_mobile_search">Search by Mobile No/ Pulli Id or Other's entry</label>
                                        <input list="items" type="text" class="form-control" id="pulliId_mobile_search" name="pulliId_mobile_search" > <br>
                                        <datalist id="items">
                                            <option value="Others" data-name="Others"></option>
                                        </datalist>
                                    </div>

                                    <table class="table mb-0" style="display: none;  width: 100%; text-align: center;" id="donation_search_table">
                                        <thead>
                                            <tr>
                                              <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Choose</th>
                                              <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">SNO</th>
                                              <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Pulli Id</th>
                                              <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Name</th>
                                              <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Mobile NO</th>
                                            <tr>                                            
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                  
                                    <div class="input-group input-group-static mb-4 py-1 pfield" style="display:none;" >
                                        <label for="pulli_id">Pulli Id</label>
                                        <input type="text" class="form-control" id="pulli_id" name="pulli_id" > <br>
                                    </div>
                                    <div class="input-group input-group-static mb-4 py-1 nfield" style="display:none;" >
                                        <label for="name">NAME</label>
                                        <input type="text" class="form-control" id="name" name="name" > <br>
                                    </div>
                                    <div class="input-group input-group-static mb-4 py-1 mfield" style="display:none;" >
                                        <label for="no">Mobile.no</label>
                                        <input type="number" class="form-control" id="no" name="no" > <br>
                                    </div>
                                    <div class="input-group input-group-static mb-4 py-1 afield" style="display:none;" >
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control" id="address" name="address" > <br>
                                    </div>
                                    <div class="input-group input-group-static mb-4">
                                        <label for="dtype">TYPE OF DONATION</label>
                                        <select class="form-control" id="dtype" name="dtype">
                                        <option value="">Select a option</option>
                                        <option value="MONEY">MONEY</option>
                                        <option value="ASSET MOVABLE">ASSET MOVABLE</option>
                                        <option value="ASSET IMMOVABLE">ASSET IMMOVABLE</option>
                                        </select>
                                    </div>
                                    <div class="input-group input-group-static mb-4">
                                        <label for="value" id="valueLabel">Value</label>
                                        <input type="number" class="form-control" id="value" name="value"> <br>                 
                                    </div>
                                    <div  class="input-group input-group-static mb-4 py-1">
                                        <label for="DESCRIPTION">DESCRIPTION</label>
                                        <input type="text" class="form-control" id="DESCRIPTION" name="DESCRIPTION"> <br>
                                    </div>
                                    <div class="buttn_donate" style="display:flex; flex-direction:row; gap:10%">
                                        <button type="submit" id="submit_donate" class="btn bg-gradient-success">Submit</button>
                                        <button type="button" onclick="history.back()" id="submitbtn_donate" class="btn bg-gradient-primary">Cancel X </button>
                                    </div>
                                </div>
                               
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- yellam search -->
    <div class="container-fluid py-4" id='yellam' style="display: none; margin-top:-130px;">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="p-4">
                        <div class="input-group input-group-dynamic mb-4">
                            <div class="input-group input-group-outline col-6">
                                <label class="form-label">Search by Mobile No, Pulliid, YesNo</label>
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
                    <form id="savepaymentform" method="POST" action="javascript:void(0);">
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
    <div class="popupreceipt" style="display: none"></div>

                
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $(".donate").hide();
        $(".pulli").hide();
        $("#yellam").hide();
        $('#itype').change(function() {
            var value = $(this).val();
            if (value ==="DONATION") {
                $(".pulli").hide();
                $(".donate").show();
                $("#yellam").hide();


                $('#pullvari_search_table').css('display', 'none');
                $("#variId_mobile_search, #vari_id, #vari_name, #vari_no, #vari_value, #vari_address, #vari_DESCRIPTION").removeAttr("required").val('');
                $("#pulliId_mobile_search, #pulli_id, #name, #no, #address, #dtype, #value, #DESCRIPTION").attr("required", true);
          
            } 
            else if (value ==="PULLIVARI") {
                $(".donate").hide();
                $(".pulli").show();
                $("#yellam").hide();

                $('#donation_search_table').css('display', 'none');
                $("#variId_mobile_search, #vari_id, #vari_name, #vari_no, #vari_value, #vari_address, #vari_DESCRIPTION").attr("required", true);
                $("#pulliId_mobile_search, #pulli_id, #name, #no, #address, #dtype, #value, #DESCRIPTION").removeAttr("required").val('');
            }
            else if (value ==="YELLAM") {
                $(".donate").hide();
                $(".pulli").hide();
                $("#yellam").show();

                $('#donation_search_table').css('display', 'none');
                $('#pullvari_search_table').css('display', 'none');

                $("#variId_mobile_search, #vari_id, #vari_name, #vari_no, #vari_value, #vari_address, #vari_DESCRIPTION").removeAttr("required").val('');
                $("#pulliId_mobile_search, #pulli_id, #name, #no, #address, #dtype, #value, #DESCRIPTION").removeAttr("required").val('');

            }
            else{
                $(".donate").hide();
                $(".pulli").hide();
                $("#yellam").hide();
            }
        }); 


       
        
        $(document).on('change', '.amt-checkbox', function () {
            let total = 0;
            $('.amt-checkbox:checked').each(function () {
                total += parseFloat($(this).val());
            });
            $('#vari_value').val(total);
        });

        const urlParams = new URLSearchParams(window.location.search);
        const type = urlParams.get('type');
        const pulliid = urlParams.get('pulliid');


        const inc_type = urlParams.get('inc_type');

        if(inc_type == 1){
           $('#itype').val('PULLIVARI').trigger('change');
        } else if(inc_type == 2){
            $('#itype').val('DONATION').trigger('change');
        } else if(inc_type == 4){
            $('#itype').val('YELLAM').trigger('change');
        } 


        if (type === 'pullivari' && pulliid != null) {   


            $('#itype').val('PULLIVARI').trigger('change');
            $('#itype option').not('[value="PULLIVARI"]').remove();

            $('#vari_id').val(pulliid).trigger('change');
            $('#variId_mobile_search').attr('required', false)
            $('#pulliid_search_container').hide();

            $('#vari_id').prop('readonly', true);
                $.ajax({
                    url: 'pulliidSearch',
                    method: 'POST',
                    data: {
                        vari_search: pulliid,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response);

                        if (response.data && response.data.length > 0) {
                            const firstItem = response.data[0];
                            pullivariSeacrhFn({
                                value: firstItem.pulliid,
                                name: firstItem.name,
                                no: firstItem.whatsappnumber,
                                address: firstItem.address
                            });
                        }
                    },
                    error: function (xhr) {
                        console.error("AJAX Error:", xhr.responseText);
                    }
                });
            $('.redirect').val('pullipage');
        } 

        document.getElementById('income').addEventListener('submit', function(event) {
            event.preventDefault(); 

            const form = document.getElementById('income');
            const formData = new FormData(form);
            fetch(`{{ route('income_validate')}}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === true) {
                    Swal.fire({
                        title: "Submit",
                        text: "Are you sure you want to submit this information?",
                        icon: "info",
                        showCancelButton: true,
                        confirmButtonColor: "#00aaff",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, Submit it!"

                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                },
                                timerProgressBar: false,
                            })
                            fetch(`{{ route('income_store')}}`, {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === true) {
                                    if (data.loc === 'don') {
                                        popopen_don(data.dondata);
                                        // window.location.href='incomelist?inc_type=donation';
                                    }
                                    else {
                                        popopen(data.pulli);
                                        // window.location.href='incomelist?inc_type=pullivari';
                                    }
                                    // if (data.loc === 'pullipage') {
                                    //     window.location.href='pullivari';
                                    // }
                                    // if (data.loc === 'other') {
                                    //     window.location.href='incomelist?inc_type=others';
                                    // }
                                    // Swal.fire('Success!', data.message, 'success').then(() => {
                                    // });

                                }
                                else if (data.status === 'payyelam') {
                                    Swal.fire('INFO!', data.message, 'info').then(() => {
                                            let goto=$('#vari_id').val();
                                            window.location.href='userprofile/'+goto+'?type=showyelam';
                                    });
                                }
                                else {
                                    Swal.fire('Error!', data.message || 'Check the Form Values.', 'error');
                                }
                            })
                            .catch(error => {
                                Swal.fire('Error!', 'Something went wrong.', 'error');
                            });
                        }
                    });
                } 
                else {
                    Swal.fire('Error!', data.message || 'Check the Form Values.', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error!', 'Something went wrong.', 'error');
            });
                
            
        });
    });

    const urlParams = new URLSearchParams(window.location.search);

    const pulliid = urlParams.get('pulliid');
    const incType = urlParams.get('inc_type');

    if (pulliid && incType) {
        setTimeout(() => {

            if (incType === '1') {
                // Pullivari
                const input = document.getElementById('variId_mobile_search');
                if (input) {
                    input.value = pulliid;
                }
                handlePulliidSearchForPullivari(pulliid);
            }

            if (incType === '2') {
                // Donation
                const input = document.getElementById('pulliId_mobile_search');
                if (input) {
                    input.value = pulliid;
                }
                handlePulliidSearchForDonation(pulliid);
            }

            if (incType === '4') {
                // Yellam
                const input = document.getElementById('yellam_search');
                if (input) {
                    input.value = pulliid;
                }
                input.focus()
                handlePulliidSearchForYellam(pulliid);
            }

        }, 500);
    }

</script>

<script>
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        window.location.reload();
    }
});
</script>


<script>
    function pullivariSeacrhFn(input) {
            let value = '', name = '', no = '', address = '';

            if (input instanceof HTMLElement) {
                value = input.value || '';
                name = $(input).data('vname') || '';
                no = $(input).data('vno') || '';
                address = $(input).data('vaddress') || '';
            } 
            else if (typeof input === 'object' && input !== null) {
                value = input.value || '';
                name = input.name || '';
                no = input.no || '';
                address = input.address || '';
            } 
            else if (typeof input === 'string') {
                value = input;
            } 
            else {
                console.warn('Unsupported input type');
            }

            
            if ((value)==="NA"){
                const container = $('#amt-checkbox-container');
                container.empty(); 
                container.append(`<p>No amount data available.</p>`);
                $('#vari_value').val(0);
                $('#vari_name, #vari_address, #vari_no').val('');
                $('#vari_name, #vari_address, #vari_no, #vari_value').prop('readonly', false);
                $('.pvfield','.nvfield, .mvfield, .avfield').show();
            }
            else if (value) {

                $('#vari_id').val(value);
                
                $('#vari_name').val(name);
                $('#vari_address').val(address);
                $('#vari_no').val(no);
                $('#vari_id, vari_name, #vari_address, #vari_no, #vari_value').prop('readonly', true);
                $('.pvfield, .nvfield, .mvfield, .avfield').show();
                // $('.nvfield, .mvfield, .avfield').show();
                
                $.ajax({
                    url: '/paymentstatus',
                    method: 'POST',
                    data: {
                        id: value,
                        
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {

                        const container = $('#amt-checkbox-container');
                        container.empty(); 
                        const title='<h6 class="form-check-label">Total Tax</h6>'
                        container.append(title);
                        let total = 0;

                        if (Array.isArray(response.amt) && response.amt.length > 0) {
                            response.amt.forEach(item => {
                                const year = item.year;
                                const amt = item.amt;

                                const checkboxHTML = `
                                    <div class="input-group input-group-static mb-2">
                                        <label class="form-check-label" id="${year}"> 
                                        <input type="checkbox" id="${year}" class="amt-checkbox form-check-input me-2" style="border: 1px solid pink;" 
                                            name="selected_amts[${year}]"    
                                            data-year="${year}" 
                                            value="${amt}" 
                                            checked />
                                        ₹${amt} (${year})  </label>
                                    </div>
                                `;

                                container.append(checkboxHTML);
                                total += parseFloat(amt);
                            });

                            $('#vari_value').val(total); // Set total initially
                        } else {
                            container.append(`<p>No amount data available.</p>`);
                            $('#vari_value').val(0);
                        }
                    },
                    error: function(xhr) {
                        console.log("Error occurred: ", xhr.responseText);
                    }
                });
                
            }else {                
                const container = $('#amt-checkbox-container');
                container.empty(); 
                container.append(`<p>No amount data available.</p>`);
                $('#vari_value').val(0);
                $('#vari_name, #vari_address, #vari_no').val('');
                $('#vari_name, #vari_address, #vari_no, #vari_value').prop('readonly', true);
                $('.pvfield','.nvfield, .mvfield, .avfield').hide(); 
            }
        }
</script>


<script>

    function donationSeacrhFn(radio) {

            if (typeof radio === 'object' && radio !== null) {
                value = radio.value;
            } else {
                value = radio;
            }

            var name = $(radio).data('dname');
            var no = $(radio).data('dno');
            var address = $(radio).data('daddress');

            if ((value)=="NA"){
                $('#pulli_id').val('Others').prop('readonly', true);
                
                $('#name, #address, #no').val('');
                $('#name, #address, #no').prop('readonly', false);
                $('.pfield, .nfield, .mfield, .afield').show();
                $('#donation_search_table').hide();
            }
            else if (value) {

                $('#pulli_id').val(value).prop('readonly', 'true');

                $('#name').val(name);
                $('#address').val(address);
                $('#no').val(no);
                $('#name, #address, #no').prop('readonly', true);
                $('.pfield, .nfield, .mfield, .afield').show();
            }else {
                $('#name, #address, #no').val('');
                $('#name, #address, #no').prop('readonly', true);
                $('.pfield, .nfield, .mfield, .afield').hide(); 
            }
        };
</script>



<script>          


function handlePulliidSearchForPullivari(value) {

    ['vari_id', 'vari_name', 'vari_no', 'vari_address'].forEach(function (id) {
        document.getElementById(id).value = '';
    });

    $.ajax({
        url: '/pulliidSearch',
        method: 'POST',
        data: {
            vari_search: value,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {

            $('#pullvari_search_table').css('display', 'table');
            const tbody = $('#pullvari_search_table tbody');
            tbody.empty();

            if (response.data && response.data.length > 0) {
                let ids = 1;

                response.data.forEach(function (item) {
                    let row = `
                        <tr>
                            <td>
                                <input type="radio" name="select_vari"
                                    value="${item.pulliid}"
                                    data-vname="${item.name}"
                                    data-vno="${item.whatsappnumber}"
                                    data-vaddress="${item.address}"
                                    onclick="pullivariSeacrhFn(this)">
                            </td>
                            <td><p class="text-xs font-weight-bold mb-0">${ids}</p></td>
                            <td><p class="text-xs font-weight-bold mb-0">${item.pulliid}</p></td>
                            <td><p class="text-xs font-weight-bold mb-0">${item.name}</p></td>
                            <td><p class="text-xs font-weight-bold mb-0">${item.whatsappnumber}</p></td>
                        </tr>
                    `;
                    ids++;
                    tbody.append(row);
                });

            } else {
                tbody.append('<tr><td colspan="5">No List found</td></tr>');
            }
        },
        error: function (xhr) {
            console.log("Error occurred: ", xhr.responseText);
        }
    });
}



function handlePulliidSearchForYellam(pulliid) {
    if (!pulliid) return;

    console.log("Searching for Yellam:", pulliid);

    $.ajax({
        url: '/incomelist',
        method: 'get',
        data: {
            id: pulliid,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            console.log("Response received:", response);

            let tbody  = $("#productTable tbody");
            let tbody1 = $("#referralproductTable tbody");
            const baseUrl = "{{ url('receipt') }}/";

            tbody.empty();
            tbody1.empty();

            if (response.yellam_product && response.yellam_product.length > 0) {
                let ids = 1;
                let ids1 = 1;

                response.yellam_product.forEach(function (room) {

                    const pending = room.value - room.paidtotal;
                    const isPending = (pending !== 0) || room.payment === 'Not Paid';

                    // ---------------- NON-REFERRAL ----------------
                    if (!room.nameguest) {
                        let row = `
                            <tr>
                                <td>${ids}</td>
                                <td>${room.pulliid}</td>
                                <td>${room.name}</td>
                                <td>${room.things ?? ''}</td>
                                <td>${room.value}</td>
                                <td>${room.paidtotal}</td>
                                <td>${pending}</td>
                                <td>
                        `;

                        row += isPending
                            ? `
                                <button type="button" class="text-xs font-weight-bold mb-0 "
                                data-bs-toggle="modal"
                                data-bs-target="#exampleModal"
                                data-id="${room.id}"
                                data-name="${room.name}"
                                data-yelamporul="${room.things}"
                                data-yelamtype="${room.yelamtype}"
                                data-amount="${room.value}">
                                    <i class="fa fa-rupee text-warning"></i> Change Paid Status
                                </button>

                              `
                            : `
                                <p class="text-xs font-weight-bold mb-0 ">
                                    <i class="fa-check-circle text-success">${room.payment}</i>
                                </p>
                              `;

                        row += `
                                </td>
                                <td>
                                    <a href="${baseUrl}${room.id}" target="_blank">
                                        <i class="fa fa-print text-success"></i>
                                    </a>
                                </td>
                            </tr>
                        `;

                        tbody.append(row);
                        ids++;
                    }

                    // ---------------- REFERRAL ----------------
                    else {
                        let row1 = `
                            <tr>
                                <td>${ids1}</td>
                                <td>${room.pulliid}</td>
                                <td>${room.nameguest}</td>
                                <td>${room.things ?? ''}</td>
                                <td>${room.value}</td>
                                <td>${room.paidtotal}</td>
                                <td>${pending}</td>
                                <td>
                        `;

                        row1 += isPending
                            ? `
                                <p class="text-xs font-weight-bold mb-0"
                                   data-bs-toggle="modal"
                                   data-bs-target="#exampleModal"
                                   data-id="${room.id}"
                                   data-name="${room.name}"
                                   data-yelamporul="${room.things}"
                                   data-yelamtype="${room.yelamtype}"
                                   data-amount="${room.value}">
                                    <i class="fa-rupee text-warning">Change Paid Status</i>
                                </p>
                              `
                            : `
                                <p class="text-xs font-weight-bold mb-0">
                                    <i class="fa-check-circle text-success">${room.payment}</i>
                                </p>
                              `;

                        row1 += `
                                </td>
                                <td>
                                    <a href="${baseUrl}${room.id}" target="_blank">
                                        <i class="fa fa-print text-success"></i>
                                    </a>
                                </td>
                            </tr>
                        `;

                        tbody1.append(row1);
                        ids1++;
                    }
                });

            } else {
                tbody.append('<tr><td colspan="9">No List found</td></tr>');
                tbody1.append('<tr><td colspan="9">No List found</td></tr>');
            }

            if (!tbody.children().length) {
                tbody.append('<tr><td colspan="9">No List found</td></tr>');
            }
            if (!tbody1.children().length) {
                tbody1.append('<tr><td colspan="9">No List found</td></tr>');
            }
        },
        error: function (xhr) {
            console.error("Error occurred:", xhr.responseText);
        }
    });
}




function handlePulliidSearchForDonation(value) {
    if (!value) return;

    // Clear previous data
    ['pulli_id', 'name', 'no', 'address'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });

    let others = (value === 'Others') ? 'yes' : 'no';

    $.ajax({
        url: '/pulliidSearch',
        method: 'POST',
        data: {
            vari_search: value,
            others: others,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {

            const table = $('#donation_search_table');
            const tbody = $('#donation_search_table tbody');

            tbody.empty();

            if (response.othersPresent) {
                table.hide();
                donationSeacrhFn('NA');
                return;
            } else {
                table.show();
            }

            if (response.data && response.data.length > 0) {
                let ids = 1;

                response.data.forEach(function (item) {
                    const row = `
                        <tr>
                            <td>
                                <input type="radio" name="select_vari"
                                    value="${item.pulliid}"
                                    data-dname="${item.name}"
                                    data-dno="${item.whatsappnumber}"
                                    data-daddress="${item.address}"
                                    onclick="donationSeacrhFn(this)">
                            </td>
                            <td>${ids}</td>
                            <td>${item.pulliid}</td>
                            <td>${item.name}</td>
                            <td>${item.whatsappnumber}</td>
                        </tr>
                    `;
                    tbody.append(row);
                    ids++;
                });
            } else {
                tbody.append('<tr><td colspan="5">No List found</td></tr>');
            }
        },
        error: function (xhr) {
            console.error("Pulliid search error:", xhr.responseText);
        }
    });
}





        document.addEventListener('DOMContentLoaded', function () {

            const dtype = document.getElementById('dtype');

            dtype.addEventListener('change', function () {
                const label = document.getElementById('valueLabel');
                const selected = this.value;
                console.log(selected);
                

                if (selected == 'ASSET MOVABLE' || selected == 'ASSET IMMOVABLE') {
                    label.textContent = 'Approximate value of the Asset';
                } else {
                    label.textContent = 'Value';
                }
            });



            const input = document.getElementById('variId_mobile_search');
            if (input) 
            {
                input.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter') {
                    
                        event.preventDefault();
                        handlePulliidSearchForPullivari(event.target.value);

                    }
                });
            }
    });
</script>



<script>                                 
        document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('pulliId_mobile_search');
        if (input) {

            input.addEventListener('change', function () {
                if (input.value == 'Others') {
                    donationSeacrhFn('NA')
                }
            });

            input.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    handlePulliidSearchForDonation(this.value.trim());
                }
            });
        }
    });
</script>


<script>

     document.addEventListener('DOMContentLoaded', function() {
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
                        event.preventDefault();
                        handlePulliidSearchForYellam(searchInput.value.trim());
                    }
                });
            } else {
                console.error("Search input not found!");
            }


        });
</script>

<script>
    function popopen(id){
        console.log(id);
        if (id) {
            const popup_container = document.querySelector('.popupreceipt');
            const pop_shadow = popup_container.attachShadow({mode: 'open'});
            let receiptsHTML = '';
            const array = id;
            const now=(array.created_at).split(' ');
            const padded= String(array.receipt_id).padStart(5, '0');
            receiptsHTML += `
                <div class="status_show" style="margin-top:-5%">
                    <div class="receipt">
                        <button class="Btn">
                            <a href="{{url('receipt/${array.ref_id}?paytotxt=${array.pay_to_txt}')}}" target="_blank">
                            <div class="printer">
                                <div class="paper">
                                <svg viewBox="0 0 8 8" class="svg">
                                    <path fill="#0077FF" d="M6.28951 1.3867C6.91292 0.809799 7.00842 0 7.00842 0C7.00842 0 6.45246 0.602112 5.54326 0.602112C4.82505 0.602112 4.27655 0.596787 4.07703 0.595012L3.99644 0.594302C1.94904 0.594302 0.290039 2.25224 0.290039 4.29715C0.290039 6.34206 1.94975 8 3.99644 8C6.04312 8 7.70284 6.34206 7.70284 4.29715C7.70347 3.73662 7.57647 3.18331 7.33147 2.67916C7.08647 2.17502 6.7299 1.73327 6.2888 1.38741L6.28951 1.3867ZM3.99679 6.532C2.76133 6.532 1.75875 5.53084 1.75875 4.29609C1.75875 3.06133 2.76097 2.06018 3.99679 2.06018C4.06423 2.06014 4.13163 2.06311 4.1988 2.06905L4.2414 2.07367C4.25028 2.07438 4.26057 2.0758 4.27406 2.07651C4.81533 2.1436 5.31342 2.40616 5.67465 2.81479C6.03589 3.22342 6.23536 3.74997 6.23554 4.29538C6.23554 5.53084 5.23439 6.532 3.9975 6.532H3.99679Z"></path>
                                    <path fill="#0055BB" d="M6.756 1.82386C6.19293 2.09 5.58359 2.24445 4.96173 2.27864C4.74513 2.17453 4.51296 2.10653 4.27441 2.07734C4.4718 2.09225 5.16906 2.07947 5.90892 1.66374C6.04642 1.58672 6.1743 1.49364 6.28986 1.38647C6.45751 1.51849 6.61346 1.6647 6.756 1.8235V1.82386Z"></path>
                                </svg>
                                </div>
                                <div class="dot"></div>
                                <div class="output">
                                <div class="paper-out"></div>
                                </div>
                            </div>
                            <span class="tooltip">Print</span>
                            </a>
                        </button>
                        <img src="{{asset('images/bg.avif')}}" alt="">
                        <div class="overlay-content">
                            <div class="header">
                                <div class="title_receipt_id">
                                    @if ($_SERVER['HTTP_HOST'] == "singaravelar.templesmart.in")
                                        <h2>சிங்காரவேலர் படைப்பு வீடு டிரஸ்ட்</h2>
                                        <h3>SINGARAVELAR PADAIPPU VEDU TRUST</h3>
                                        <p>மேலைச்சிவிரி - 123456. புதுக்கோட்டை மாவட்டம்.</p>
                                    @elseif ($_SERVER['HTTP_HOST'] == "127.0.0.1:8000")
                                        <h2>நாகம்மை ஆயா படைப்புக் குழு</h2>
                                        <h3>NAGAMMAI AAYA PADAIPPU KUZHU</h3>
                                        <p>மேலைச்சிவபுரி - 622 403. புதுகை மாவட்டம்.</p>
                                    @endif
                                </div>
                                <div class="dateid">
                                    <div class="date_receipt_id">தேதி: ${now[0]}</div>
                                    <div class="receipt_id">ரசீது எண்: PV-${array.receipt_id}-${padded}</div>
                                </div>
                                <div class="body">
                                    <p><span class="label_receipt_id">திருமதி/திரு </span> 
                                        <span class="dotted">
                                            <span class="dotted_p">${array.ref_txt}</span>
                                        </span> 
                                        <span class="label_receipt_id">அவர்களிடமிருந்து புள்ளிவரியாக ரூபாய்( </span>
                                        <span class="dotted">
                                            <span class="dotted_p">${array.amount}</span>
                                        </span>
                                        <span class="label_receipt_id">மட்டும் ) நன்றியுடன் பெற்றுக் கொண்டோம்.</span> 
                                    </p>
                                    <p><span class="label_receipt_id">வரி ஆண்டுகள் &nbsp;:</span> 
                                        <span class="dotted">
                                            <span class="dotted_p">${array.pay_to_txt}</span>
                                        </span>
                                    </p>
                                </div>
                                <div class="footer">
                                    <div class="amount-box">₹ ${array.amount}
                                        <span style="font-size: 10px;color:rgba(117, 117, 117, 0.808);">/-</span></div>
                                    @if ($_SERVER['HTTP_HOST'] == "singaravelar.templesmart.in")
                                         <div class="signature">செயலர் கையொப்பம்</div>
                                    @elseif ($_SERVER['HTTP_HOST'] == "127.0.0.1:8000")
                                        <div class="signature_nagammai">நாகம்மை ஆயா படைப்புக் குழுவிற்காக</div>
                                    @endif    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            pop_shadow.innerHTML = `
                <link rel="stylesheet" href="{{asset('css/paycard.css')}}">
                <div style="position:fixed;margin-left:80%;margin-top:10%;z-index:10000000">
                    <button class="close_button">
                        <span>Close</span>
                    </button>
                </div>
                ${receiptsHTML}
            `; 
            const close_button = pop_shadow.querySelector('.close_button');
            if (close_button) {
                close_button.addEventListener('click', function() {
                    popup_container.style.display = 'none';
                    window.location.href = `/userprofile/${array.ref_id}`;

                    // window.location.reload();
                });
            }
            Swal.hideLoading();
            Swal.close();
            popup_container.style.display = 'block';
        }
        else{
            Swal.fire('oops!', 'Try again.', 'info');
        }
    }
</script>
<script>
    function popopen_don(id){
        console.log(id);
        if (id) {
            const popup_container = document.querySelector('.popupreceipt');
            const pop_shadow = popup_container.attachShadow({mode: 'open'});
            let receiptsHTML = '';
            const array = id;
            const now=(array.created_at).split(' ');
            const padded= String(array.receipt_id).padStart(5, '0');
            receiptsHTML += `
                <div class="status_show" style="margin-top:-5%">
                    <div class="receipt">
                        <button class="Btn">
                            <a href="{{url('receipt/${array.id}?donation={success}')}}" target="_blank">
                            <div class="printer">
                                <div class="paper">
                                <svg viewBox="0 0 8 8" class="svg">
                                    <path fill="#0077FF" d="M6.28951 1.3867C6.91292 0.809799 7.00842 0 7.00842 0C7.00842 0 6.45246 0.602112 5.54326 0.602112C4.82505 0.602112 4.27655 0.596787 4.07703 0.595012L3.99644 0.594302C1.94904 0.594302 0.290039 2.25224 0.290039 4.29715C0.290039 6.34206 1.94975 8 3.99644 8C6.04312 8 7.70284 6.34206 7.70284 4.29715C7.70347 3.73662 7.57647 3.18331 7.33147 2.67916C7.08647 2.17502 6.7299 1.73327 6.2888 1.38741L6.28951 1.3867ZM3.99679 6.532C2.76133 6.532 1.75875 5.53084 1.75875 4.29609C1.75875 3.06133 2.76097 2.06018 3.99679 2.06018C4.06423 2.06014 4.13163 2.06311 4.1988 2.06905L4.2414 2.07367C4.25028 2.07438 4.26057 2.0758 4.27406 2.07651C4.81533 2.1436 5.31342 2.40616 5.67465 2.81479C6.03589 3.22342 6.23536 3.74997 6.23554 4.29538C6.23554 5.53084 5.23439 6.532 3.9975 6.532H3.99679Z"></path>
                                    <path fill="#0055BB" d="M6.756 1.82386C6.19293 2.09 5.58359 2.24445 4.96173 2.27864C4.74513 2.17453 4.51296 2.10653 4.27441 2.07734C4.4718 2.09225 5.16906 2.07947 5.90892 1.66374C6.04642 1.58672 6.1743 1.49364 6.28986 1.38647C6.45751 1.51849 6.61346 1.6647 6.756 1.8235V1.82386Z"></path>
                                </svg>
                                </div>
                                <div class="dot"></div>
                                <div class="output">
                                <div class="paper-out"></div>
                                </div>
                            </div>
                            <span class="tooltip">Print</span>
                            </a>
                        </button>
                        <img src="{{asset('images/bg.avif')}}" alt="">
                        <div class="overlay-content">
                            <div class="header">
                                <div class="title_receipt_id">
                                    <h2>சிங்காரவேலர் படைப்பு வீடு டிரஸ்ட்</h2>
                                    <h3>singaravelar PADAIPPU VEDU TRUST</h3>
                                    <p>மேலைச்சிவிரி - 123456. புதுக்கோட்டை மாவட்டம்.</p>
                                </div>
                                <div class="dateid">
                                    <div class="date_receipt_id">தேதி: ${now[0]}</div>
                                    <div class="receipt_id">ரசீது எண்: DO-${array.receipt_id}-${padded}</div>
                                </div>
                                <div class="body">
                                    <p>
                                        <span class="label">திருமதி/திரு </span> 
                                        <span class="dotted">
                                            <span class="dotted_p">${array.ref_txt}</span>
                                        </span>
                                        <span class="label">அவர்களிடமிருந்து நன்கொடை ரூபாய் </span> 
                                        <span class="dotted">
                                            <span class="dotted_p">${array.amount}</span>
                                        </span>
                                        <span class="label">மட்டும் நன்றியுடன்பெற்றுக்கொண்டோம்.</span>
                                    </p>
                                    <p><span class="label">நன்கொடை வகை &nbsp;:</span> 
                                        <span class="dotted">
                                            <span class="dotted_p">${array.pay_mode}</span>
                                        </span>
                                    </p>
                                </div>
                                <div class="footer">
                                    <div class="amount-box">₹ ${array.amount} 
                                        <span style="font-size: 10px;color:rgba(117, 117, 117, 0.808);">/-</span></div>
                                    <div class="signature">செயலர் கையொப்பம்</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            pop_shadow.innerHTML = `
                <link rel="stylesheet" href="{{asset('css/paycard.css')}}">
                <div style="position:fixed;margin-left:80%;margin-top:10%;z-index:10000000">
                    <button class="close_button">
                        <span>Close</span>
                    </button>
                </div>
                ${receiptsHTML}
            `; 
            const close_button = pop_shadow.querySelector('.close_button');
            if (close_button) {
                close_button.addEventListener('click', function() {
                    popup_container.style.display = 'none';
                    window.location.href = `/userprofile/${array.ref_id}`;

                    // window.location.reload();
                });
            }
            Swal.hideLoading();
            Swal.close();
            popup_container.style.display = 'block';
        }
        else{
            Swal.fire('oops!', 'Try again.', 'info');
        }
    }
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('savepaymentform');

        form.addEventListener('submit', function (event) {
            event.preventDefault(); 
            Swal.fire({
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                timerProgressBar: false,
            })

            const formData = new FormData(form); 

            $.ajax({
                url: 'savepayment',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log("Success:", response);
            
                if (response.data) {
                    if (response.status === true) {
                        var host = "{{ $_SERVER['HTTP_HOST'] }}";
                        if (host == "singaravelar.templesmart.in") {
                            console.log("Welcome! to "+host);
                            const popup_container = document.querySelector('.popupreceipt');
                            const pop_shadow = popup_container.attachShadow({mode: 'open'});
                            let receiptsHTML = '';
                            const array = response.data;
                            const now=(array.created_at).split(' ');
                            const padded= String(array.yelamporul).padStart(5, '0');
                            receiptsHTML += `
                                <div class="status_show" style="margin-top:-5%">
                                    <div class="receipt">
                                        <button class="Btn">
                                            <a href="{{url('receipt/${array.pay_to_txt}')}}" target="_blank">
                                            <div class="printer">
                                                <div class="paper">
                                                <svg viewBox="0 0 8 8" class="svg">
                                                    <path fill="#0077FF" d="M6.28951 1.3867C6.91292 0.809799 7.00842 0 7.00842 0C7.00842 0 6.45246 0.602112 5.54326 0.602112C4.82505 0.602112 4.27655 0.596787 4.07703 0.595012L3.99644 0.594302C1.94904 0.594302 0.290039 2.25224 0.290039 4.29715C0.290039 6.34206 1.94975 8 3.99644 8C6.04312 8 7.70284 6.34206 7.70284 4.29715C7.70347 3.73662 7.57647 3.18331 7.33147 2.67916C7.08647 2.17502 6.7299 1.73327 6.2888 1.38741L6.28951 1.3867ZM3.99679 6.532C2.76133 6.532 1.75875 5.53084 1.75875 4.29609C1.75875 3.06133 2.76097 2.06018 3.99679 2.06018C4.06423 2.06014 4.13163 2.06311 4.1988 2.06905L4.2414 2.07367C4.25028 2.07438 4.26057 2.0758 4.27406 2.07651C4.81533 2.1436 5.31342 2.40616 5.67465 2.81479C6.03589 3.22342 6.23536 3.74997 6.23554 4.29538C6.23554 5.53084 5.23439 6.532 3.9975 6.532H3.99679Z"></path>
                                                    <path fill="#0055BB" d="M6.756 1.82386C6.19293 2.09 5.58359 2.24445 4.96173 2.27864C4.74513 2.17453 4.51296 2.10653 4.27441 2.07734C4.4718 2.09225 5.16906 2.07947 5.90892 1.66374C6.04642 1.58672 6.1743 1.49364 6.28986 1.38647C6.45751 1.51849 6.61346 1.6647 6.756 1.8235V1.82386Z"></path>
                                                </svg>
                                                </div>
                                                <div class="dot"></div>
                                                <div class="output">
                                                <div class="paper-out"></div>
                                                </div>
                                            </div>
                                            <span class="tooltip">Print</span>
                                            </a>
                                        </button>
                                        <img src="{{asset('images/bg.avif')}}" alt="">
                                        <div class="overlay-content">
                                            <div class="header">
                                                <div class="title_receipt_id">
                                                    <h2>சிங்காரவேலர் படைப்பு வீடு டிரஸ்ட்</h2>
                                                    <h3>singaravelar PADAIPPU VEDU TRUST</h3>
                                                    <p>மேலைச்சிவிரி - 123456. புதுக்கோட்டை மாவட்டம்.</p>
                                                </div>
                                                <div class="dateid">
                                                    <div class="date_receipt_id">தேதி: ${now[0]}</div>
                                                    <div class="receipt_id">ரசீது எண்: YELAM-${array.receipt_id}-${padded}</div>
                                                </div>
                                                <div class="body">
                                                    <p><span class="label">திருமதி/திரு </span>
                                                    <span class="dotted">
                                                            <span class="dotted_p">${array.ref_txt}</span>
                                                        </span> 
                                                        <span class="label">அவர்களிடமிருந்து ரூபாய்( </span>
                                                        <span class="dotted">
                                                            <span class="dotted_p">${array.amount}</span>
                                                        </span>
                                                        <span class="label">மட்டும் ) ஏலத்தொகை நன்றியுடன் பெற்றுக் கொண்டோம்.</span> 
                                                    </p>
                                                    <p><span class="label">பொருள்&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</span> 
                                                        <span class="dotted">
                                                            <span class="dotted_p">${array.things}</span>
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="footer">
                                                    <div class="amount-box">₹ ${array.amount} 
                                                        <span style="font-size: 10px;color:rgba(117, 117, 117, 0.808);">/-${array.amount} </span></div>
                                                    <div class="signature">செயலர் கையொப்பம்</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            pop_shadow.innerHTML = `
                                <link rel="stylesheet" href="{{asset('css/paycard.css')}}">
                                <div style="position:fixed;margin-left:80%;margin-top:10%;z-index:10000000">
                                    <button class="close_button">
                                        <span>Close</span>
                                    </button>
                                </div>
                                ${receiptsHTML}
                            `; 
                            const close_button = pop_shadow.querySelector('.close_button');
                            if (close_button) {
                                close_button.addEventListener('click', function() {
                                    popup_container.style.display = 'none';
                                    window.location.href = `/userprofile/${array.ref_id}`;

                                    // window.location.reload();
                                });
                            }
                            Swal.hideLoading();
                            Swal.close();
                            popup_container.style.display = 'block';
                        }
                        else{
                            Swal.fire('Success!', response.data.message, 'success').then(() => {
                                window.location.reload();
                            });
                        }
                    } else {
                        Swal.fire('Error!', data.message || 'Check the Form Values.', 'error');
                    }
                    
                }
                else{
                    Swal.fire('oops!', 'Try again.', 'info');
                }

            
                },
                error: function (xhr) {
                    console.error("AJAX Error:", xhr.responseText);
                }
            });
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        const urlParams = new URLSearchParams(window.location.search);

        $('#itype').on('change', function () {
            const selected = $(this).val();
            let incType = null;

            if (selected === 'PULLIVARI') {
                console.log(1);
                incType = '1';
            } else if (selected === 'DONATION') {
                console.log(2);
                incType = '2';
            } else if (selected === 'YELLAM') {
                console.log(3);
                incType = '4';
            }

            if (incType !== null) {
                urlParams.set('inc_type', incType);
                const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
                window.history.replaceState({}, '', newUrl);
            }
        });
    });
</script>
