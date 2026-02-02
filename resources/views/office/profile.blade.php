@extends('office.layout.layout')
@section('title', 'Profile')

@section('content')

   

<div class="container-fluid py-4">



<div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('{{ asset('assets/img/f.jpeg') }}');">

        <span class="mask  bg-gradient-primary  opacity-6"></span>
      </div>
      <div class="card card-body mx-3 mx-md-4 mt-n6">
        <div class="row gx-4 mb-2">
          <div class="col-auto">
          <div class="avatar avatar-xl position-relative">
          @if(File::exists(public_path('images/' . Auth::id() . '.jpg')))
    <img src="{{ ('images/' . Auth::id() . '.jpg') }}" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
    @else
    <img src="{{asset('assets/img/small-logos/google-webdev.svg')}}" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
    @endif
    <a href="#" class="edit-icon position-absolute top-0 end-0 translate-middle p-2">
    
    </a>
</div>
<form action="{{url ('profileImage')}}" enctype="multipart/form-data" method="post">
  @csrf
<i class="fas fa-user-edit text-secondary text-sm"  data-bs-toggle="tooltip" data-bs-placement="top" title="Change Profile Picture">
<input type="file" name="file">
</i>
<input type="hidden" name="id" value="{{$userId}}">
<button class="btn bg-gradient-success" type="submit">Company Logo</button>
</form>
          </div>
          <div class="col-auto my-auto">
            <div class="h-100">
              <h5 class="mb-1">
                {{$data->company_name}}
              </h5>
              <p class="mb-0 font-weight-normal text-sm">
                {{$data->ceo_name}} | {{$data->contact_name}} | {{$data->desgination}}
              </p>
             

            </div>

           

          
          </div>
          <div class="col-auto my-auto text-right">
            <input type="file" name="file1"><button class="btn bg-gradient-primary" type="button">Product Images</button>
<br/>
            <input type="file" name="file2"><button class="btn bg-gradient-success" type="button">Product Profile</button>
          </div>
          <div class="card">
            <div class="card-body ">
                  <h6 class="card-category text-danger">
                      Hurray!! <i class="material-icons">tag_faces</i> Allotted Booth
                  </h6>
                  <h4 class="card-title">
                    <?php
                    if($allotted!=''){?>
                        <a href="voiceView" target="_blank">Booth No {{$allotted->room_no}} allotted. {{$allotted->squarefeet}} Square Feet. {{$allotted->space}} Space</a>
                    <?php } else {
                        ?>
                         <a href="#pablo">Booth not allotted yet. Kindly Contact Admin.</a>
                    <?php }
                    ?>
                     
                  </h4>
          
              </div>
         
                </div>
                
        </div>
      
        <div class="row">
    
          <div class="row">

           
            <div class="col-12 col-xl-6">
                
              <div class="card card-plain h-100">
                <div class="card-header pb-0 p-3">
                  <h6 class="mb-0">Company Details</h6>
                </div>
                <div class="card-body p-3">
                  <h6 class="text-uppercase text-body text-xs font-weight-bolder">Data</h6>
                  <ul class="list-group">
                    <li class="list-group-item border-0 px-0">
                      <div class="form-check form-switch ps-0">
                        <input class="form-check-input ms-auto" type="checkbox" id="flexSwitchCheckDefault" checked>
                        <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="flexSwitchCheckDefault">GST - {{$data->gst_no	}}</label>
                      </div>
                    </li>
                    <li class="list-group-item border-0 px-0">
                      <div class="form-check form-switch ps-0">
                        <input class="form-check-input ms-auto" type="checkbox" id="flexSwitchCheckDefault1" checked>
                        <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="flexSwitchCheckDefault1">PAN - {{$data->pan_no}}</label>
                      </div>
                    </li>
                    <li class="list-group-item border-0 px-0">
                      <div class="form-check form-switch ps-0">
                        <input class="form-check-input ms-auto" type="checkbox" id="flexSwitchCheckDefault2" checked>
                        <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="flexSwitchCheckDefault2">Profile 1 - {{$data->profile1_name}}</label>
                      </div>
                    </li>
                  </ul>
                  <h6 class="text-uppercase text-body text-xs font-weight-bolder mt-4">Additional Details</h6>
                  <ul class="list-group">
                    <li class="list-group-item border-0 px-0">
                      <div class="form-check form-switch ps-0">
                        <input class="form-check-input ms-auto" type="checkbox" id="flexSwitchCheckDefault3">
                        <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="flexSwitchCheckDefault3">New launches and projects</label>
                      </div>
                    </li>
                    <li class="list-group-item border-0 px-0">
                      <div class="form-check form-switch ps-0">
                        <input class="form-check-input ms-auto" type="checkbox" id="flexSwitchCheckDefault4" checked>
                        <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="flexSwitchCheckDefault4">Monthly product updates</label>
                      </div>
                    </li>
                    <li class="list-group-item border-0 px-0 pb-0">
                      <div class="form-check form-switch ps-0">
                        <input class="form-check-input ms-auto" type="checkbox" id="flexSwitchCheckDefault5">
                        <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="flexSwitchCheckDefault5">Subscribe to newsletter</label>
                      </div>
                    </li>
                  </ul>
                </div>

                <div class="card-header pb-0 p-3">
                  <div class="row">
                    <div class="col-md-8 d-flex align-items-center">
                      <h6 class="mb-0">Profile Information</h6>
                    </div>
                    <div class="col-md-4 text-end">
                      <a href="javascript:;">
                     
                        <button type="button" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="fas fa-user-edit text-secondary text-sm"  data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Profile"></i>
                      </button>
                      </a>
                    </div>
                  </div>
                </div>
                <div class="card-body p-3">
                  <p class="text-sm">
                    {{$data->overseas_profile}}
                  </p>
                  <hr class="horizontal gray-light my-4">
                  <ul class="list-group">
                    <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Full Name:</strong> &nbsp; {{$data->ceo_name}}</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Mobile:</strong> &nbsp; ({{$data->std_code}}) {{$data->mobile}}</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Email:</strong> &nbsp; {{$data->email}}</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Location:</strong> &nbsp; {{$data->address}}, {{$data->city}}</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Country:</strong> &nbsp; {{$data->country}} - {{$data->pincode}}</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Website:</strong> &nbsp; {{$data->website}}</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Fascia Name:</strong> &nbsp; {{$data->fascia_name}}</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Scheme Under:</strong> &nbsp; {{$data->schem}}</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Member of AIEMA:</strong> &nbsp; {{$data->member}}</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Booth prefer:</strong> &nbsp; {{$data->booth_prefer}}</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Space:</strong> &nbsp; {{$data->space}}</li>
                    <li class="list-group-item border-0 ps-0 pb-0">
                      <strong class="text-dark text-sm">Social:</strong> &nbsp;
                      <a class="btn btn-facebook btn-simple mb-0 ps-1 pe-2 py-0" href="javascript:;">
                        <i class="fab fa-facebook fa-lg"></i>
                      </a>
                      <a class="btn btn-twitter btn-simple mb-0 ps-1 pe-2 py-0" href="javascript:;">
                        <i class="fab fa-twitter fa-lg"></i>
                      </a>
                      <a class="btn btn-instagram btn-simple mb-0 ps-1 pe-2 py-0" href="javascript:;">
                        <i class="fab fa-instagram fa-lg"></i>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>


              
            </div>
           
            <div class="col-12 col-xl-6">
              <div class="card card-plain h-100">
                <div class="card-header pb-0 p-3">
                  <h6 class="mb-0">Help/Request to ACMEE</h6>
                </div>
                <div class="card-body p-3">
                  <ul class="list-group">
                    <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2 pt-0">
                      <div class="avatar me-3">
                        <img src="{{asset('assets/img/small-logos/icon-bulb.svg')}}" alt="kal" class="border-radius-lg shadow">
                      </div>
                      <div class="d-flex align-items-start flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">You.</h6>
                        <p class="mb-0 text-xs">Need Additional AC in my booth..</p>
                      </div>
                      <a class="btn btn-link pe-3 ps-0 mb-0 ms-auto w-25 w-md-auto" href="javascript:;">Reply</a>
                    </li>
                    <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2 pt-0">
                        <div class="avatar me-3">
                          <img src="{{asset('assets/img/small-logos/icon-bulb.svg')}}" alt="kal" class="border-radius-lg shadow">
                        </div>
                        <div class="d-flex align-items-start flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">You.</h6>
                          <p class="mb-0 text-xs">Need Additional AC in my booth..</p>
                        </div>
                        <a class="btn btn-link pe-3 ps-0 mb-0 ms-auto w-25 w-md-auto" href="javascript:;">Reply</a>
                      </li>
                      <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2 pt-0">
                        <div class="avatar me-3">
                          <img src="{{asset('assets/img/small-logos/icon-bulb.svg')}}" alt="kal" class="border-radius-lg shadow">
                        </div>
                        <div class="d-flex align-items-start flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">You.</h6>
                          <p class="mb-0 text-xs">Need Additional AC in my booth..</p>
                        </div>
                        <a class="btn btn-link pe-3 ps-0 mb-0 ms-auto w-25 w-md-auto" href="javascript:;">Reply</a>
                      </li>
               
                  </ul>
               

                  <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">Finance</h6>
                  </div>
                  <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center">S.No</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Print</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                          
                            <td>Transaction</td>
                           
                            <td class="text-right">0</td>
                            <td class="td-actions text-center">
                                <button type="button" rel="tooltip" class="btn btn-info">
                                    <i class="material-icons">print</i>
                                </button>
                               
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

  
    @include('office.layout.footer')
</div>
  <!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Edit Profile</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
         
        </button>
      </div>
      <div class="modal-body">
                       <form id = "getAlldata"> 

                       <div id="adddata" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 " style="margin-top:10px; ">

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
            <div class="mb-3">
                <label for="company_name" class="form-label">Company Name</label>
                <input type = "text" class="form-control" value = "{{$data->company_name}}" name = "company_name" id="company_name">

            </div>
        </div>
        <div>
            <div class="mb-3">
                <label for="ceo_name" class="form-label">CEO Name</label>
                <input type = "text"  class = "form-control" value = "{{$data->ceo_name}}" name = "ceo_name">

            </div>
        </div>
        <div>
            <div class="mb-3">
                <label for="contact_name" class="form-label">Contact Name</label>
                <input type = "text"  class = "form-control" value = "{{$data->contact_name}}" name = "contact_name">

            </div>
        </div>
        <div>
            <div class="mb-3">
                <label for="designation" class="form-label">Designation</label>
                <input type = "text"  class = "form-control" value = "{{$data->desgination}}" name = "desgination">

            </div>
        </div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
            <div class="mb-3">
                <label for="company_name" class="form-label">Address</label>
                <input type = "text" class = "form-control"VALUE = "{{$data->address}}" name = "address">
            </div>
        </div>
        <div>
            <div class="mb-3">
                <label for="ceo_name" class="form-label">city</label>
                <input type = "text" class = "form-control"VALUE = "{{$data->city}}" name = "city">
            </div>
        </div>
        <div>
            <div class="mb-3">
                <label for="contact_name" class="form-label">pincode</label>
                <input type = "text" class = "form-control"VALUE = "{{$data->pincode}}" name = "pincode">
            </div>
        </div>
        <div>
            <div class="mb-3">
                <label for="designation" class="form-label">country</label>
                <input type = "text" class = "form-control"VALUE = "{{$data->country}}" name = "country">
            </div>
        </div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
            <div class="mb-3">
                <label for="company_name" class="form-label">Mobile</label>
                <input type = "text" class = "form-control"VALUE = "{{$data->std_code}}" name = "std_code">
            </div>
        </div>
        <div>
            <div class="mb-3">
                <label for="ceo_name" class="form-label">Alternative Number</label>
                <input type = "text" class = "form-control"VALUE = "{{$data->mobile}}" name = "mobile">
            </div>
        </div>
        <div>
           
        </div>
        <div>
            <div class="mb-3">
                <label for="designation" class="form-label">website</label>
                <input type = "text" class = "form-control"VALUE = "{{$data->website}}" name = "website">
            </div>
        </div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
            <div class="mb-3">
                <label for="company_name" class="form-label">PAN No</label>
                <input type = "text" class = "form-control"VALUE = "{{$data->pan_no}}" name = "pan_no">
            </div>
        </div>
        <div>
            <div class="mb-3">
                <label for="ceo_name" class="form-label">Gst No	</label>
                <input type = "text" class = "form-control"VALUE = "{{$data->gst_no	}}" name = "gst_no">
            </div>
        </div>
        <!-- <div>
        <div class="mb-3">
                <label for="contact_name" class="form-label">product profile1</label>
                <select name="product_profile1" id="user"  class="js-states form-control @error('enquiry_id') is-invalid @enderror" >
                  @isset($data)
            <option value="{{$data->product_profile1}}" selected>{{$data->profile1_name}}</option>
                @endisset
               </select>
        </div>
        </div>
        <div>
        <div class="mb-3">
                <label for="contact_name" class="form-label">product profile1</label>
                <select name="product_profile2" id="user"  class="js-states form-control @error('enquiry_id') is-invalid @enderror" >
                  @isset($data)
            <option value="{{$data->product_profile2}}" selected>{{$data->profile2_name}}</option>
                @endisset
               </select>
        </div>
        </div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
        <div class="mb-3">
                <label for="contact_name" class="form-label">product profile1</label>
                <select name="product_profile3" id="user"  class="js-states form-control @error('enquiry_id') is-invalid @enderror" >
                  @isset($data)
            <option value="{{$data->product_profile3}}" selected>{{$data->profile3_name}}</option>
                @endisset
               </select>
        </div>
        </div> -->
        <div>
            <div class="mb-3">
                <label for="ceo_name" class="form-label">Brand Names</label>
                <input type = "text" class = "form-control"VALUE = "{{$data->other_product}}" name = "other_product">
            </div>
        </div>
        <div>
            <div class="mb-3">
                <label for="contact_name" class="form-label">booth prefer</label>
                <input type = "text" class = "form-control"VALUE = "{{$data->booth_prefer}}" name = "booth_prefer">
            </div>
        </div>
        <div>
            <div class="mb-3">
                <label for="designation" class="form-label">Space</label>
                <input type = "text" class = "form-control"VALUE = "{{$data->space}}" name = "space">
            </div>
        </div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
            <div class="mb-3">
                <label for="address" class="form-label">Fascia Name</label>
                <input type = "text" class = "form-control"VALUE = "{{$data->fascia_name}}" name = "fascia_name">
            </div>
        </div>
        <div>
            <div class="mb-3">
                <label for="city" class="form-label">Scheme Under</label>
                <input type = "text" class = "form-control"VALUE = "{{$data->schem}}" name = "schem">
            </div>
        </div>
        <div>
            <div class="mb-3">
                <label for="pincode" class="form-label">Member of AIEMA</label>
                <input type = "text" class = "form-control"VALUE = "{{$data->member}}" name = "member">
            </div>
        </div>
        <div>
            <div class="mb-3">
                <label for="country" class="form-label">Overseas Company Name</label>
                <input type = "text" class = "form-control"VALUE = "{{$data->overseas_company}}" name = "overseas_company">
            </div>
        </div>
</div>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
            <div class="mb-3">
                <label for="company_name" class="form-label">Overseas Country Name</label>
                <input type = "text" class = "form-control"VALUE = "{{$data->overseas_country}}" name = "overseas_country">
            </div>
        </div>
        <div>
            <div class="mb-3">
                <label for="ceo_name" class="form-label">Product Profile</label>
                <input type = "text" class = "form-control"VALUE = "{{$data->overseas_profile}}" name = "overseas_profile">
            </div>
        </div>
        <div>
            <div class="mb-3">
                <label for="contact_name" class="form-label">state</label>
                <input type = "text" class = "form-control"VALUE = "{{$data->state}}" name = "state">
            </div>
            <input type = "hidden" class = "form-control"VALUE = "{{$data->id}}" name = "id">

        </div>
        <div>
      
        </div>
    
        
</form>
  
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
        <button  id="updatePr" type="button" class="btn bg-gradient-primary">Update </button>
      </div>
    </div>
  </div>
</div>

@stop
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    


<script>
       
       $(document).ready(function(){
    $('#updatePr').click(function() { 
        var data = $('#getAlldata').serialize();

        $.ajax({
            url: 'api/updateProfile',
            method: 'post',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: data,
            success: function(response){
                console.log(response);
                if (response.message) {
                    Swal.fire({
                        text: response.message,
                        icon: 'success',
                    });  
                }
            },
            error: function(xhr, status, error){
                console.log(error);
                Swal.fire({
                    text: error.responseText, 
                    icon: 'error',
                });  
            }
        });
    });
});



   

      </script>
