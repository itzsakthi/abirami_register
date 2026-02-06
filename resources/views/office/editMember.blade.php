@extends('office.layout.layout')
@section('title', 'Edit Member')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
              <h6 class="text-white text-capitalize ps-3">Edit Member</h6>
              
            </div>
          </div>
       

            <div class="p-4">
                     
                      
    <div class="">

        @if ($errors->any())
            <div id="errorBox" style="
                background:#fdecea;
                border:1px solid #f5c2c7;
                color:#842029;
                padding:12px 16px;
                border-radius:6px;
                margin-bottom:20px;
            ">
                <strong>Please fix the following errors:</strong>
                <ul style="margin:8px 0 0 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


  

    <form action="{{url ('updatemember/'.$data->id)}}" method="POST" id="registerform" enctype="multipart/form-data">

    @csrf
    <div class="input-group input-group-static mb-4">
        <label for="pulliid"> Pulli Id <span style="color: red">*</span> </label>
        <input type="text" class="form-control" id="pulliid" name="pulliid"  value="  {{$data->pulliid }}" readonly required>
      
    </div>
    <div class="input-group input-group-static mb-4">
        <label for="name"> Name <span style="color: red">*</span> </label>
        <input type="text" class="form-control" id="name" name="name"  value="{{$data ->name }}" required>
      
    </div>
    <!-- <span id="productNameError" class="error" style="display:none; color: #ff0000; font-size: 13px;"></span> -->
    <div class="amman">
 <div class="input-group input-group-static mb-4" >
        <label for="fathername">Father Name</label><br>
        <input type="text" class="form-control" id="fathername" name="fathername" value="0" >
    </div></div>

    <div class="input-group input-group-static mb-4">
        <label for="spousename">Spouse Name</label>
        <br>
        <input type="text" class="form-control" id="" name="spousename" value="{{$data->spousename }}" >
    </div>
    <div class="amman">
    <div class="input-group input-group-static mb-4">
        <label for="phonenumber">Phone Number</label>
        <br>
        <input type="number" class="form-control" id="" name="phonenumber" value="{{$data->phonenumber }}" >
    </div>
</div>

    <div class="input-group input-group-static mb-4">
        <label for="whatsappnumber">Whatsapp Number <span style="color: red">*</span> </label>
        <br>
        <input type="number" class="form-control" id="" name="whatsappnumber" value="{{$data->whatsappnumber}}" required>
    </div>
    <div class="input-group input-group-static mb-4">
        <label for="spousenumber">Spouse Number</label>
        <br>
        <input type="number" class="form-control" id="" name="spousenumber" value="{{$data-> spousenumber }}" >
    </div>
    <div class="input-group input-group-static mb-4">
        <label for="familynickname">Family Name</label>
        <input type="text" class="form-control" id="" name="familynickname" value="{{$data-> familynickname }}"  >
    </div>


    <div class="amman">
    <div class="input-group input-group-static mb-4">
        <label for="email">email</label>
        <br>
        <input type="text" class="form-control" id="" name="email" value="0" >
    </div>
</div>

    <div class="input-group input-group-static mb-4">
        <label for="address">Address <span style="color: red">*</span> </label>
        <input type="text" class="form-control" id="" name="address" value="{{$data->address }} "  required>
    </div>
    <div class="input-group input-group-static mb-4">
        <label for="address">karai</label>
        <input type="text" class="form-control" id="" name="karai" value="{{$data->karai }} "  >
    </div>
    <div class="input-group input-group-static mb-4">
        <label for="address">Reference</label>
        <input type="text" class="form-control" id="" name="reference" value="{{$data->reference }} "  >
    </div>

    <div class="input-group input-group-static mb-4">
        <label for="native">Native <span style="color: red">*</span> </label>
        <br>
        <input type="text" class="form-control" id="" name="native" value="{{$data-> native}}" required>
    </div>
<div class="but" style="display:flex; flex-direction:row; gap:30%">

    <button type="submit" id="submitbtn" class="btn bg-gradient-success">Submit</button>
    <button type="button" onclick="history.back()" id="cancelbtn" class="btn bg-gradient-primary">Cancel X </button></div>
</div>
</form>

</div>              

          </div>
        </div>
      </div>
    </div>

  </div>

          </div>
        </div>
      </div>
    </div>
   
    



                

                   
  
  </div>
  
 
<script>
    setTimeout(function () {
        const box = document.getElementById('errorBox');
        if (box) {
            box.style.transition = 'opacity 0.5s ease';
            box.style.opacity = '0';
            setTimeout(() => box.remove(), 500);
        }
    }, 5000); // 5 seconds
</script>



<script>
document.getElementById('registerform').addEventListener('submit', function(e) {
    e.preventDefault(); 

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

            // Disable submit button
            document.getElementById('submitbtn').disabled = true;
            document.getElementById('submitbtn').innerText = "Submitting...";

            Swal.fire({
                title: "Please Wait...",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit form after 300ms
            setTimeout(() => {
                e.target.submit();
            }, 300);
        }
    });
});
</script>



@stop
