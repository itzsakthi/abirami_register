@extends('office.layout.layout')
@section('title', 'All Booths')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
      

<div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card my-4">
          <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
              <h6 class="text-white text-capitalize ps-3">Yelam Things</h6>
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
    <form action="yelamstore" method="POST" id="yelamthimgs" enctype="multipart/form-data">

@csrf
<input type="hidden" name="yelamthimgs" id="yelamthimgs" value="">

                    <div class="input-group input-group-dynamic mb-4">
                      
                          <!-- <div class="table-responsive">
    <table class="table align-items-center mb-0">

    <thead>
        <tr>
        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Things</th>
        <br>
        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Tamil/English</th>

        </tr>
    </thead>
    <tbody>
      
        @php $ids =1 @endphp
                        @foreach($data as $room)
                            <tr>
                            <td>
                            <p class="text-xs text-secondary mb-0"> {{ $ids }}</p></td>
                            <td> <p class="text-xs  font-weight-bold mb-0"> {{ $room->things }}</p>
                            </td>
                                <td> <p class="text-xs  font-weight-bold mb-0"> {{ $room->description }}</p>
                            </td>
                            </tr>
                            @php     $ids++; @endphp
                        @endforeach
    </tbody>
</table> -->
<div class="input-group input-group-static mb-4">
        <label for="things"> Things </label>
        <input type="text" class="form-control" id="things" name="things" class="tamil/english" value="{{old('things')}}"placeholder=" Type Tamil / English" required>
      
    </div>
    <center>
    <button type="submit" id="submitbtn" class="btn bg-gradient-success">Submit</button>
</center>
    <!-- <div class="input-group input-group-static mb-4">
        <label for="description"> Tamil / English</label>
        <input type="text" class="form-control" id="description" name="description"  value="{{old('description')}}" required>
      
    </div> -->
</div>    
<div>
                    </div>
                      </div>
                     
          </div>
        </div>
      </div>
    </div>
  
    <!-- @include('office.layout.footer') -->
  </div>
  
 



@stop