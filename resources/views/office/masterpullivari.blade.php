@extends('office.layout.layout')
@section('title', 'Pullivari Configuration')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<!-- Flatpicker CDN-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/flatpickr-plugin-yearSelect.js"></script>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
</style>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3"> Pullivari Configuration</h6>
                    
                        </div>
                    </div>
            

                    <div class="p-4">
                        <div class="">

                            <div style="color:red">
                                @if($errors->any())
                                {{ implode(',', $errors->all(':message')) }}
                                @endif
                            </div>
                            @if(session('success'))
                                <div id="success-message" class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                </div>

                                <script>
                                    setTimeout (function() {
                                        $('#success-message').fadeOut('slow');
                                    }, 3000);
                                </script>
                            @endif

                            @if(session('error'))
                                <div id="error-message" class="alert alert-danger" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif 
                            <form  method="POST" id="masterform" enctype="multipart/form-data">
                                @csrf
                                <!-- <div class="row">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" value="annual" checked name="user_type" id="customRadio1">
                                        <label class="custom-control-label" for="customRadio1">Annual</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="bi_annual" name="user_type" id="customRadio2">
                                        <label class="custom-control-label" for="customRadio2">Bi Annual</label>
                                    </div>
                                </div> -->
                                
                                <div class="row">
                                
                                    <div class="annual">
                                        <div class="row">
                                            <p><strong>Annual Year</strong></p>
                                
                                            <div class="col-md-6">
                                                <label for="annual_date" class="form-label">From date*</label>
                                    
                                                <div class="input-group input-group-static mb-4">
                                                    <label for="annual_date" class="input-group-text" style="cursor: pointer;">
                                                    <i class="fa fa-calendar"></i>
                                                    </label>
                                                    <input id="annual_date" name="annual_date" type="text" class="form-control" placeholder="Select Date">
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <label for="bi_annual" class="form-label">To Date*</label>
                                                <div class="input-group input-group-static mb-4">
                                                    <label for="annual_date" class="input-group-text" style="cursor: pointer;">
                                                    <i class="fa fa-calendar"></i>
                                                    </label>    
                                                    <input id="bi_annual" name="bi_annual" type="text" class="form-control" placeholder="Select Date">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group input-group-static mb-4">
                                                    <label style="margin-bottom: 9px;"  for="value1">Amount*</label>
                                                    <input id="value1" name="value1"  type="number" class="form-control" placeholder="Type in the amt" >
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    
                                    <div class="col-12 text-center mt-3">
                                        <button type="submit" id="submitbtn" class="btn bg-gradient-success">Submit</button>
                                    </div>

                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">List</h6>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="input-group input-group-dynamic mb-4">
                            <div class="input-group input-group-outline col-6">
                                <label class="form-label">Search here...</label>
                                <input id="search" type="text" class="form-control">
                            </div>
                        
                            <div class="table-responsive" style="width: 100%;">
                                <table class="table align-items-center mb-0" style="width: 100%; text-align: center;" id="mytable">
                                    <thead>
                                        <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">SNO</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">year</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Amount</th>
                                        
                                        </tr>
                                    </thead>
                                    <tbody>
        
                                        @php $ids = $data->firstItem(); @endphp
                                            @foreach($data as $entries)
                                                <tr>
                                                    <td>
                                                        <p class="text-xs  font-weight-bold mb-0"> {{ $ids }}</p></td> 
                                                    
                                                    <td>
                                                        <p class="text-xs  font-weight-bold mb-0"> {{ $entries->annual }} to 
                                                        {{$entries->by_annual}}</p></td> 
                                                    <td>
                                                        <p class="text-xs  font-weight-bold mb-0">{{ $entries->annual_amt }}</p>
                                                    </td>
                                                @php     $ids++; @endphp
                                            @endforeach
                                            <p id="noData" style="display: none; color: red; text-align: center;">No matching records found</p>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {!! $data->links() !!}
                    </div>
                </div>
            </div>
        </div>
  
        <!-- @include('office.layout.footer') -->
    </div>

<!-- Flatpicker Script-->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        $(document).ready(function() {

            // var $rows = $('#mytable tr');
            $('#search').keyup(function () {
                var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

                var $rows = $('#mytable tbody tr'); // only body rows
                var visibleCount = 0;

                $rows.each(function () {
                    var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                    if (text.indexOf(val) > -1) {
                        $(this).show();
                        visibleCount++;
                    } else {
                        $(this).hide();
                    }
                });

                // Remove previous "no data" row if exists
                $('#mytable tbody .no-data').remove();

                if (visibleCount === 0) {
                    $('#mytable tbody').append(`
                        <tr class="no-data">
                            <td colspan="3" class="text-center text-danger">No matching records found</td>
                        </tr>
                    `);
                }
            });


            const thisYear = new Date().getFullYear();
            flatpickr("#bi_annual", {
                minDate: `${thisYear+1}-01-01`
            });
            //for annual
            flatpickr("#annual_date", {
            maxDate: `${thisYear + 1}-12-31`, 
                onChange: function (selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        const selectedYear = selectedDates[0].getFullYear() ;
                        const selectedMonth = selectedDates[0].getMonth() + 2;
                        
                        console.log(selectedYear);
                        console.log(selectedMonth);
                        
                        const minDate = `${selectedYear}-${selectedMonth}-01`;

                            $('#bi_annual').val('')
                            bi_annual = flatpickr("#bi_annual", {
                                // maxDate: `${selectedYear}-12-31`,
                                minDate: minDate,
                            });
                        
                    }
                }
            });
           
            
            $('#annual_date').on('input', function() {
                let annual_date = $('#annual_date').val();
                let bi_annual = $('#bi_annual').val()
                if (annual_date !== '' && bi_annual !== ''  ) {
                    rangecheck(annual_date , bi_annual)
                }
            });
            $('#bi_annual').on('input', function() {
                let bi_annual = $('#bi_annual').val();
                let annual_date = $('#annual_date').val()
                if (bi_annual !== '' && annual_date !== '') {
                    rangecheck(annual_date , bi_annual)
                }
            });

            document.getElementById('masterform').addEventListener('submit', function(event) {
                event.preventDefault(); 

                const form = document.getElementById('masterform');
                const formData = new FormData(form);
                fetch(`{{ route('masterpullivariValidate')}}`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === true) {
                        Swal.fire({
                            title: "Submit",
                            text: "Confirm submission of Master pullivari info?",
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
                                fetch(`{{ route('masterpullivariStore')}}`, {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === true) {
                                        window.location.href='masterpullivari'; 
                                    } else {
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
        function rangecheck(date1,date2) {
            $.ajax({
                url: '/annual_year',
                method: 'POST',
                data: {
                    date1: date1,
                    date2:date2,
                    
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // console.log(response);
                    if (response.exists === true) {
                        
                        Swal.fire({
                            title: "Record exists",
                            text: "Record exists for the year.",
                            icon: "info",
                            timer: 2500,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timerProgressBar: true,
                            showClass: {
                                popup: 'animate__animated animate__fadeInUp animate__faster'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__fadeOutDown animate__faster'
                            }
                        });
                        // $('#annual_date').val(response.data.annual);
                        // $('#value1').val(response.data.annual_amt);
                        // $('#bi_annual').val(response.data.by_annual);
                       
                        $('#submitbtn').hide()
                    }
                    else{
                        $('#submitbtn').show()
                    }
                },
                error: function(xhr) {
                    console.log("Error occurred: ", xhr.responseText);
                }
            });
                
        }
        
    </script>


@endsection