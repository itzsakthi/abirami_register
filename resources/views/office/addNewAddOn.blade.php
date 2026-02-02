@extends('office.layout.layout')
@section('title', 'Member Registration')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<style>
    .amman {
        display: none;
    }
</style>
<div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card my-4">
          <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
              <h6 class="text-white text-capitalize ps-3">Pulli Members Registration</h6>
              
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
        @endif

        @if(session('error'))
            <div id="error-message" class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        <div class="container">

     <form action="registerstore" method="POST" id="registerform" enctype="multipart/form-data">

    @csrf
        <div class="row">
    <div class="col-md-6">
                <div class="input-group input-group-static mb-4">
                    <label for="pulliid">Pulli Id <span style="color: red">*</span> </label>
                    <input type="text" class="form-control" id="pulliid" name="pulliid" value="{{old('pulliid')}}" required>
                </div>
            </div>
            
            <div class="col-md-6">
            <div class="input-group input-group-static mb-4">
            <label for="Name"> Name <span style="color: red">*</span> </label>
        <input type="text" class="form-control" id="name" name="name"  value="{{old('name')}}" required>
      
            </div> </div>

            <div class="col-md-6 amman">
    <span id="productNameError" class="error" style="display:none; color: #ff0000; font-size: 13px;"></span>
    <div class="input-group input-group-static mb-4">
        <label for="fathername">Father Name</label><br>
        <input type="text" class="form-control" id="fathername" name="fathername" value="0" >
    </div>
    </div>
    <div class="col-md-6">
    <div class="input-group input-group-static mb-4">
        <label for="spousename">Spouse Name</label>
        
        <input type="text" class="form-control" id="" name="spousename" value="{{old('spousename')}}" >
    </div></div>
    <div class="col-md-6 amman">
    <div class="input-group input-group-static mb-4">
        <label for="phonenumber">Phone Number</label>
        <input type="number" class="form-control" id="" name="phonenumber" value="0" >
    </div></div>
    <div class="col-md-6">
    <div class="input-group input-group-static mb-4">
        <label for="whatsappnumber">Whatsapp Number <span style="color: red">*</span> </label>
        <input type="number" class="form-control" id="" name="whatsappnumber" value="{{old('whatsappnumber')}}" required>
    </div> </div>
    <div class="col-md-6">
    <div class="input-group input-group-static mb-4">
        <label for="spousenumber">Spouse Number</label>
        <input type="number" class="form-control" id="" name="spousenumber" value="{{old('spousenumber')}}" >
    </div></div>
    <div class="col-md-6">
    <div class="input-group input-group-static mb-4">
        <label for="familynickname">Family Name</label>
        <input type="text" class="form-control" id="" name="familynickname" value="{{old('familynickname')}}"  >
    </div></div>
    <div class="col-md-6 amman" >
    <div class="input-group input-group-static mb-4">
        <label for="email">Email</label>
        <input type="text" class="form-control" id="" name="email" value="#"  >
    </div></div>
    <div class="col-md-6">
    <div class="input-group input-group-static mb-4">
        <label for="address">Address <span style="color: red">*</span> </label>
        <input type="text" class="form-control" id="" name="address" value="{{old('address')}} " required>
    </div>    </div>

    <div class="col-md-6">
    <div class="input-group input-group-static mb-4">
        <label for="karai">Karai</label>
        <input type="text" class="form-control" id="" name="karai" value="{{old('karai')}}"  >
    </div></div>
    <div class="col-md-6">
    <div class="input-group input-group-static mb-4">
        <label for="reference">Reference</label>
        <input type="text" class="form-control" id="" name="reference" value="{{old('reference')}} "  >
    </div>    </div>
    <div class="col-md-6">
    <div class="input-group input-group-static mb-4">
        <label for="native">Native <span style="color: red">*</span> </label>
        <input type="text" class="form-control" id="" name="native" value="{{old('native')}}" required>
    </div></div>
<div class="but" style="display:flex; flex-direction:row; gap:30%">
    
    <button type="submit" id="submitbtn" class="btn bg-gradient-success">Submit</button>
    <button type="button" onclick="history.back()" id="cancelbtn" class="btn bg-gradient-primary">Cancel X </button></div>
</div>
</form>

</div>              

          </div>

</div>
   
    



                

                   
  
    <!-- @include('office.layout.footer') -->
  </div>
  
 



@stop

<script src="assets/js/new.js"></script>

<!-- ====== ionicons ======= -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



<<script>
$(document).ready(function () {

    let submitted = false;

    $('#registerform').on('submit', function (e) {

        if (submitted) {
            e.preventDefault();
            return false;
        }

        submitted = true;

        swal({
            text: "Processing...",
            icon: "success",
            buttons: false,
            closeOnClickOutside: false
        });

        $('#submitbtn').prop('disabled', true).text('Please wait...');
    });

});
</script>


</body>





