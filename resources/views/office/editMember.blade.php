@extends('office.layout.layout')
@section('title', 'Edit Member')

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
              <h6 class="text-white text-capitalize ps-3">Edit Member</h6>
              
            </div>
          </div>
       

            <div class="p-4">
                     
                      
    <div class="">

  

    <form action="{{url ('updatemember/'.$data->id)}}" method="POST" id="registerform" enctype="multipart/form-data">

    @csrf
    <div class="input-group input-group-static mb-4">
        <label for="pulliid"> Pulli Id</label>
        <input type="text" class="form-control" id="pulliid" name="pulliid"  value="  {{$data->pulliid }}" readonly>
      
    </div>
    <div class="input-group input-group-static mb-4">
        <label for="name"> Name</label>
        <input type="text" class="form-control" id="name" name="name"  value="{{$data ->name }}" required>
      
    </div>
    <!-- <span id="productNameError" class="error" style="display:none; color: #ff0000; font-size: 13px;"></span> -->
    <div class="amman"
 <div class="input-group input-group-static mb-4" >
        <label for="fathername">Father Name</label><br>
        <input type="text" class="form-control" id="fathername" name="fathername" value="0" required>
    </div></div>

    <div class="input-group input-group-static mb-4">
        <label for="spousename">Spouse Name</label>
        <br>
        <input type="text" class="form-control" id="" name="spousename" value="{{$data->spousename }}" required>
    </div>
    <div class="amman">
    <div class="input-group input-group-static mb-4">
        <label for="phonenumber">Phone Number</label>
        <br>
        <input type="number" class="form-control" id="" name="phonenumber" value="{{$data->phonenumber }}" required>
    </div>
</div>

    <div class="input-group input-group-static mb-4">
        <label for="whatsappnumber">Whatsapp Number</label>
        <br>
        <input type="number" class="form-control" id="" name="whatsappnumber" value="{{$data->whatsappnumber}}" required>
    </div>
    <div class="input-group input-group-static mb-4">
        <label for="spousenumber">Spouse Number</label>
        <br>
        <input type="number" class="form-control" id="" name="spousenumber" value="{{$data-> spousenumber }}" required>
    </div>
    <div class="input-group input-group-static mb-4">
        <label for="familynickname">Family Name</label>
        <input type="text" class="form-control" id="" name="familynickname" value="{{$data-> familynickname }}"  required>
    </div>


    <div class="amman">
    <div class="input-group input-group-static mb-4">
        <label for="email">email</label>
        <br>
        <input type="text" class="form-control" id="" name="email" value="0" required>
    </div>
</div>

    <div class="input-group input-group-static mb-4">
        <label for="address">Address</label>
        <input type="text" class="form-control" id="" name="address" value="{{$data->address }} "  required>
    </div>
    <div class="input-group input-group-static mb-4">
        <label for="address">karai</label>
        <input type="text" class="form-control" id="" name="karai" value="{{$data->karai }} "  required>
    </div>
    <div class="input-group input-group-static mb-4">
        <label for="address">Reference</label>
        <input type="text" class="form-control" id="" name="reference" value="{{$data->reference }} "  required>
    </div>

    <div class="input-group input-group-static mb-4">
        <label for="native">Native</label>
        <br>
        <input type="text" class="form-control" id="" name="native" value="{{$data-> native}}" required>
    </div>
<div class="but" style="display:flex; flex-direction:row; gap:30%">
    <button type="submit" id="submitbtn" class="btn bg-gradient-success">Send Whatsapp</button>
    <button type="submit" id="submitbtn" class="btn bg-gradient-success">Submit</button>
    <button type="button" onclick="history.back()" id="submitbtn" class="btn bg-gradient-primary">Cancel X </button></div>
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
  
 



@stop
