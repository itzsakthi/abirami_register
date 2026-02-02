@extends('office.layout.layout')
@section('title', 'Expenditure Entry')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3"> Expenditure Enquiry</h6>
                        
                        </div>
                    </div>
            

                    <div class="p-4">
                        <div class="">

                            
                            <form method="POST" id="expenditure" enctype="multipart/form-data">

                                @csrf
                                <input type="hidden" name="expenditure_entry" id="Expenditure_entry" value="">

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" value="master" name="user_type" id="customRadio1">
                                    <label class="custom-control-label" for="customRadio1">EXPENSE MASTER</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="entry" name="user_type" id="customRadio2">
                                    <label class="custom-control-label" for="customRadio2">EXPENSE ENTRY</label>
                                </div>


                                <div class="master hidden" style="display:none">
                                    <div class="input-group input-group-static mb-4 py-3">

                                        <label for="name">EXPENSES NAME</label>
                                        <input type="text" class="form-control" id="name" name="name"> <br>
                                    </div>

                                    <div  class="input-group input-group-static mb-4 py-3">
                                        <label for="DESCRIPTION">DESCRIPTION</label>
                                        <input type="text" class="form-control" id="DESCRIPTION" name="DESCRIPTION"> <br>
                                    </div>
                                    <div class="buttn_master" style="display:flex; flex-direction:row; gap:10%">
                                        <button type="submit" id="submit_master" class="btn bg-gradient-success">Submit</button>
                                        <button type="button" onclick="history.back()" id="submitbtn" class="btn bg-gradient-primary">Cancel X </button>
                                    </div>
                                </div>
                                <div class="entry">
                                    <div class="input-group input-group-static mb-4">
                                        <label for="expense_name">Expense Name</label>
                                        <select class="form-control" id="expense_name" name="expense_name">
                                        <option value="">Select a Option</option>
                                        @foreach($expenditure as $value)
                                            <option value="{{$value->id}}"> {{ $value->expenses_name }} </option>
                                        @endforeach 
                                        </select>
                                    </div>
                                    <div class="input-group input-group-static mb-4">
                                        <label for="Pay_name">Pay To</label>
                                        <input type="text" class="form-control" id="Pay_name" name="Pay_name"><br>
                                    </div>
                                    <!-- Previously Being was Remark -->
                                    <div class="input-group input-group-static mb-4">
                                        <label for="remark">Being</label>
                                        <input type="text" class="form-control"id="remark" name="remark" ><br>
                                    </div>
                                    <div class="input-group input-group-static mb-4">
                                        <label for="authorized_by">AUTHORISED BY</label>
                                        <input type="text" class="form-control"  id="authorized_by"  value="{{ $auth_name }}" readonly>
                                    </div>
                                    <div class="input-group input-group-static mb-4">
                                        <label for="pay_mode">PAID MODE</label>
                                        <input type="text" class="form-control" id="pay_mode" name="pay_mode"><br>
                                    </div>
                                    <div class="input-group input-group-static mb-4">
                                        <label for="value">Value</label>
                                        <input type="number" class="form-control" id="value" name="value"> <br>                 
                                    </div>
                                    <div style="display: none; class="input-group input-group-static mb-4">
                                        <label for="pay_to">Pay To</label>
                                        <select class="form-control" id="pay_to" name="pay_to">
                                        <option value="0">other</option>
                                        @foreach($data as $value)
                                            <option value="{{$value->pulliid}}"  data-name="{{$value->name}}"> {{ $value->pulliid }} </option>
                                        @endforeach 
                                        </select>
                                    </div>
                                    
                                    
                                    

                                  
                                    
                                    <div class="buttn_entry " style="display:flex; flex-direction:row; gap:10%;">
                                        <button type="submit" id="submit_enquiry" class="btn bg-gradient-success">Submit</button>
                                        <button type="button" onclick="history.back()" id="submitbtn" class="btn bg-gradient-primary">Cancel X </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const type = urlParams.get('type');

        if (type === 'master') {
            $('#customRadio1').prop('checked', true);
            $(".master").show();
            $(".entry").hide();

            $("#name, #DESCRIPTION").attr("required", true);
            $("#expense_name, #value, #pay_to, #Pay_name, #pay_mode, #remark, #authorized_by").removeAttr("required");

        } else if (type === 'entry') {
            $('#customRadio2').prop('checked', true);
            $(".master").hide();
            $(".entry").show();            

            $("#name, #DESCRIPTION").removeAttr("required");
            $("#expense_name, #value, #pay_to, #Pay_name, #pay_mode, #remark, #authorized_by").attr("required", true);

        }
        else{
            $('#customRadio2').prop('checked', true);
            $(".master").hide();
            $(".entry").show();

            $("#name, #DESCRIPTION").removeAttr("required");
            $("#expense_name, #value, #pay_to, #Pay_name, #pay_mode, #remark, #authorized_by").attr("required", true);
        }
        
        $(':radio[id=customRadio1]').change(function() {
        $(".entry").hide(); 
        $(".master").show();

            $("#name, #DESCRIPTION").attr("required", true);
            $("#expense_name, #value, #pay_to, #Pay_name, #pay_mode, #remark, #authorized_by").removeAttr("required");

        });

        $(':radio[id=customRadio2]').change(function() {
        $(".entry").show();
        $(".master").hide();

            $("#name, #DESCRIPTION").removeAttr("required");
            $("#expense_name, #value, #pay_to, #Pay_name, #pay_mode, #remark, #authorized_by").attr("required", true);
    
        });

        $('#pay_to').change(function() {
            var selectedOption = $(this).find(":selected");
            var name = selectedOption.data('name');
            var value = $(this).val();
            if ((value)==="0"){
                $('#Pay_name').val('');
                $('#Pay_name').prop('readonly', false);
            }else {
                $('#Pay_name').val(name);
                $('#Pay_name').prop('readonly', true);
            }
        });

        document.getElementById('expenditure').addEventListener('submit', function(event) {
            event.preventDefault(); 
            const selectedType = document.querySelector('input[name="user_type"]:checked').value;
            const form = document.getElementById('expenditure');
            const formData = new FormData(form);
            fetch(`{{ route('enquiryvalidate')}}`, {
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
                            fetch(`{{ route('enquirystore')}}`, {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === true) {
                                    // Swal.fire('Success!', data.message, 'success').then(() => {
                                        window.location.href = 'ExpenditureEntry?type=' + selectedType;
                                    // });
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
</script>




