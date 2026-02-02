@extends('office.layout.layout')
@section('title', 'Edit PM Member')

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
              <h6 class="text-white text-capitalize ps-3">Edit Piranthamagal Members</h6>
            </div>
          </div>
        <div class="p-4"> 

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
    <form action="{{url ('updatepmmember/'.$data->id)}}" method="POST" id="pmeditform" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="input-group input-group-static mb-4">
                <label for="pulliid">PM Id*</label>
                <input type="text" class="form-control" id="pmid" name="pmid" value="{{$data ->pmid }}" required>
            </div>
        </div>
                
        <div class="col-md-6">
            <div class="input-group input-group-static mb-4">
                <label for="Name"> Name*</label>
                <input type="text" class="form-control" id="name" name="name"  value="{{$data ->name }}" required>
            </div> 
        </div>

        <div class="col-md-6">
            <div class="input-group input-group-static mb-4">
                <label for="spousename">Spouse Name</label>
                <input type="text" class="form-control" id="spousename" name="spousename" value="{{$data ->spousename }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="input-group input-group-static mb-4">
                <label for="whatsappnumber">Whatsapp Number*</label>
                <input type="number" class="form-control" id="whatsappnumber" name="whatsappnumber" value="{{$data ->whatsappnumber}}" required>
            </div> 
        </div>

        <div class="col-md-6">
            <div class="input-group input-group-static mb-4">
                <label for="spousenumber">Spouse Number</label>
                <input type="number" class="form-control" id="spousenumber" name="spousenumber" value="{{$data ->spousenumber}}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group input-group-static mb-4">
                <label for="familynickname">Family Nick Name</label>
                <input type="text" class="form-control" id="familynickname" name="familynickname" value="{{$data ->familynickname}}"  required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="input-group input-group-static mb-4">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="{{$data ->address}} "  required>
            </div>    
        </div>

        <div class="col-md-6">
            <div class="input-group input-group-static mb-4">
                <label for="remark">Remarks</label>
                <input type="text" class="form-control" id="remark" name="remark" value="{{$data ->remark}} " required>
            </div>   
        </div>

        <div class="col-md-6">
            <div class="input-group input-group-static mb-4">
                <label for="reference">Reference of Pulli (Family Name)</label>
                <input type="text" class="form-control" id="reference" name="reference" value="{{$data ->reference}} " required>
            </div>   
        </div>
        <div class="col-md-6">
            <div class="input-group input-group-static mb-4">
                <label for="native">Native*</label>
                <input type="text" class="form-control" id="native" name="native" value="{{$data ->native}}" required>
            </div>
        </div>
        <div class="but" style="display:flex; flex-direction:row; gap:30%">
            
            <button type="submit" id="updatebtn" class="btn bg-gradient-success">Update</button>
            <button type="button" onclick="history.back()" id="cancelbtn" class="btn bg-gradient-primary">Cancel X </button></div>
        </div>
    </form>
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

<script>
    $(document).ready(function() {
    
    $('#pmeditform').on('submit', function(e){
        swal({
                    title:"", 
                    text:"Loading...",
                    icon: "https://www.boasnotas.com/img/loading2.gif",
                    buttons: false,      
                    closeOnClickOutside: false,
                    timer: 3000,
                    icon: "success"
                });
        $("#updatebtn").hide();
        }); 
    });
</script>
</body>





