@extends('office.layout.layout')
@section('title', 'yelamentryform')

@section('content')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
              <h6 class="text-white text-capitalize ps-3"> Yelam Entry Form</h6>
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
              
            </div>
            <form id="yellamform" enctype="multipart/form-data">
              @csrf
              <!-- <input type="hidden" name="yelamentryform" id="yelamentryform" value=""> -->

              <div class="form-check mb-3">
                <input class="form-check-input" type="radio" value="inhouse" name="yelamtype" id="customRadio1">
                <label class="custom-control-label" for="customRadio1">Inhouse(Pulli)</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" value="external" name="yelamtype" id="customRadio2">
                <label class="custom-control-label" for="customRadio2">External(Guest)</label>
              </div><br><br>
              <div class="inhouse"  style="display:none">
                <div class="input-group input-group-static mb-4">
                  <label for="yelamporul">Yelam Porul</label>
                  <select class="form-control" id="yelamporul" name="yelamporul" required>
                    <option value="">Select Yelam Porul</option>
                    @foreach($products as $room)
                      <option value="{{$room->id}}">{{$room->things}}</option>
                    @endforeach
                  </select><br>
                </div> 
                
                <div class="input-group input-group-static mb-4">
                  <label for="value">Value</label>
                  <input type="number" class="form-control" id="value" name="value" required> <br>                 
                </div>
                
                <div class="input-group input-group-static mb-4 py-1 " id="pulliid_search_container" >
                    <label for="pulliId_mobile_search">Search by Mobile No/ Pulli Id</label>
                    <input type="text" class="form-control" id="pulliId_mobile_search" name="pulliId_mobile_search" > <br>
                </div>

                <table class="table mb-0" style="display: none;  width: 100%; text-align: center;" id="yellam_search_table">
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

                <div class="input-group input-group-static mb-4">
                  <label for="pulliid">Pulli Id</label>
                  <input type="text" class="form-control" id="pulliid" name="pulliid" readonly required><br>
                </div>

                <!-- <div class="input-group input-group-static mb-4">
                  <label for="pulliid">Pulli Id</label>
                  <select class="form-control" id="pulliid" name="pulliid" required>
                    <option value="">Select a value</option>
                    @foreach($data as $room)
                      <option value="{{$room->pulliid}}" data-name="{{$room->name}}" data-phone="{{$room->whatsappnumber}}" data-native="{{$room->native}}">{{$room->pulliid}}
                      
                      </option>
                    @endforeach
                    
                  </select>
                  <br>
                </div> -->

                <div class="input-group input-group-static mb-4">

                  <label for="name">Name</label>
                  <input type="text" class="form-control" id="name" name="name" readonly required><br>
                </div>

                <div class="input-group input-group-static mb-4">
                  <label for="whatsappno">Whatsapp No</label>
                  <input type="text" readonly class="form-control" id="whatsappno" name="whatsappno" required><br>
                </div>

                <div class="input-group input-group-static mb-4">

                  <label for="native">Native</label>
                  <input type="text" readonly class="form-control"id="native" name="native" required><br>
                </div>
                <div class="input-group input-group-static mb-4">
                  <label for="bookid">Manual Book Sr.No</label>
                  <input type="number" class="form-control"  id="bookid" name="bookid" required><br>
                </div>

                <!-- <select class="form-control" id="bookid" name="bookid">
                                        <option value="0">0</option></select> -->
                <!-- <label class="form-label">Payment</label>

                <div class="input-group input-group-dynamic mb-4">
                  <select class="form-control" name = "payment" id="payment"> 
                    <option value = "" seletced>Select Payment</option>
                    <option value = "Paid">Paid</option>
                    <option value = "Not Paid">Not Paid</option>
                  </select>
                </div>   -->

                <div class="input-group input-group-static mb-4" style="display:none">
                  <label for="payment">Payment</label>
                  <input type="text" class="form-control" readonly="readonly" id="payment" name="payment" value="Not Paid" required>
                </div>


                <!-- <div class="input-group input-group-static mb-4">
                  <label for="reference">Reference</label><br>
                  <select name="reference" id="" onChange="ChangeFunction();" class="js-states form-control" >
                    <option value ="" selected disabled>Select reference</option>
                    @foreach ($reference as $datass)
                    <option value="{{ $datass->reference }}"> {{ $datass->reference }} </option>
                    @endforeach
                                                      
                  </select>   
                </div> -->

                <div class="input-group input-group-static mb-4" style="display:none;">
                  <label for="reference">Receipt No:</label>
                  <input type="text" class="form-control"id="reference" name="reference" value="0" readonly required><br>
                </div>
                <div class="input-group input-group-static mb-4">
                  <label for="remark">Remark</label>
                  <input type="text" class="form-control"id="remark" name="remark"><br>
                </div>
                
                <div class="external" style="display:none" >
                  <div class="input-group input-group-static mb-4 ">

                    <label for="name">Name(Guest)</label>
                    <input type="text" class="form-control" id="nameguest" name="nameguest" ><br>
                  </div>

                  <div class="input-group input-group-static mb-4">

                    <label for="whatsappno">Whatsapp No/Contact No(Guest)</label>
                    <input type="text" class="form-control" id="whatsappnoguest" name="whatsappnoguest" ><br>
                  </div>

                  <div class="input-group input-group-static mb-4">

                    <label for="native">Native(Guest)</label>
                    <input type="text" class="form-control"id="nativeguest" name="nativeguest" ><br>
                  </div>
                </div>
                
                <div style="display:flex; flex-direction:row; gap:10%;">
                  <button type="submit"  class="btn bg-gradient-success">Submit</button>
                  <button type="button" onclick="history.back()"  class="btn bg-gradient-primary">Cancel X </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="popupreceipt" style="display: none"></div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      $(".external").hide();
      $(".inhouse").hide();

      $(':radio[id=customRadio1]').change(function() {
        $(".external").hide(); $(".inhouse").show();
        
        $('#yelamporul, #value, #pulliId_mobile_search, #pulliid, #name, #whatsappno, #native, #bookid, #remark, #nameguest, #whatsappnoguest, #nativeguest').val('');
        $('#yellam_search_table').css('display', 'none');
       
        $("#nameguest, #whatsappnoguest, #nativeguest").removeAttr("required");
      });
      $(':radio[id=customRadio2]').change(function() {
        $(".external").show();
        $(".inhouse").show();

        $('#yelamporul, #value, #pulliId_mobile_search, #pulliid, #name, #whatsappno, #native, #bookid, #remark').val('');
        $('#yellam_search_table').css('display', 'none');


        $("#nameguest, #whatsappnoguest, #nativeguest").attr("required", true);
        
      });
      // $('#pulliid').change(function() {
      //   var option = $('#pulliid').find(":selected").attr('data-name');
      //   $('#name').val(option);
      //     var option = $('#pulliid').find(":selected").attr('data-phone');
      //   $('#whatsappno').val(option);
      //     var option = $('#pulliid').find(":selected").attr('data-native');
      //   $('#native').val(option);
      //   var option = $('#pulliid').find(":selected").attr('data-remark');
      //   $('#remark').val(option);

      // });
      document.getElementById('yellamform').addEventListener('submit', function(event) {
        event.preventDefault(); 
        const form = document.getElementById('yellamform');
        const formData = new FormData(form);
        fetch(`{{ route('yellamvalidate')}}`, {
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
                fetch(`{{ route('yelamentrystore')}}`, {
                  method: 'POST',
                  body: formData
                })
                .then(response => response.json())
                .then(data => {
                  if (data.status === true) {
                    var host = "{{ $_SERVER['HTTP_HOST'] }}";
                    if (host == "singaravelar.templesmart.in") {
                      console.log("Welcome! to "+host);
                      popopen(data.id);
                    }
                    else{
                      Swal.fire('Success!', data.message, 'success').then(() => {
                        window.location.reload();
                        // window.location.href='yelamlist';
                      });
                    }
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
  <script>
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
          // This page was loaded from Back/Forward cache
          window.location.reload();
        }
    }); 
  </script>

  <script>
    function popopen(id){
      const receiptBaseUrl = "{{ url('onlyyellamentryreceipt/') }}";
      if (id) {
        const popup_container = document.querySelector('.popupreceipt');
        const pop_shadow = popup_container.attachShadow({mode: 'open'});
        let receiptsHTML = '';
        const array = id;
        const now=(array.created_at).split(' ');
        const padded= String(array.yelamporul).padStart(5, '0');
        receiptsHTML += `
          <div class="status_show" style="margin-top:-5%">
            <div class="receipt">
              <button class="Btn">
                <a href="${receiptBaseUrl}/${array.id}" target="_blank">
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
                    <div class="receipt_id">ரசீது எண்: YES-${array.receipt_id}-${padded}</div>
                  </div>
                  <div class="body">
                    <p><span class="label">எடுத்தவர் பெயர் </span> 
                        <span class="dotted">
                            <span class="dotted_p">${array.name}</span>
                        </span> 
                    </p>
                    <p><span class="label">பொருள்&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</span> 
                      <span class="dotted">
                          <span class="dotted_p">${array.things}</span>
                      </span>
                    </p>
                    <p><span class="label">ஏலத்தொகை&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</span> 
                      <span class="dotted">
                          <span class="dotted_p">${array.value}</span>
                      </span>
                    </p>
                  </div>
                  <div class="footer">
                    <div class="amount-box">₹ ${array.value}
                        <span style="font-size: 10px;color:rgba(117, 117, 117, 0.808);">/-${array.value}</span></div>
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
            window.location.reload();
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
      const input = document.getElementById('pulliId_mobile_search');
      if (input) {
          
          input.addEventListener('keydown', function(event) {
              if (event.key === 'Enter') {
                // document.getElementById('pulliid, name, native, whatsappno').value = '';

                ['pulliid', 'name', 'native', 'whatsappno'].forEach(function(id) {
                  document.getElementById(id).value = '';
                });
                  event.preventDefault();
                  const value = event.target.value;
                  $.ajax({
                      url: '/pulliidSearch',
                      method: 'POST',
                      data: {
                          vari_search: value,
                          _token: '{{ csrf_token() }}'
                      },
                      success: function(response) {
                        console.log(response);
                        
                          $('#yellam_search_table').css('display', 'table');
                          const tbody = $('#yellam_search_table tbody');
                          tbody.empty();
                          if (response.data && response.data.length > 0) {
                              let ids = 1;
                              response.data.forEach(function(item) {
                                  let row = `
                                      <tr>
                                          <td>
                                              <input type="radio" name="select_vari"
                                                  value="${item.pulliid}" 
                                                  data-dname="${item.name}" 
                                                  data-dno="${item.whatsappnumber}" 
                                                  data-dnative="${item.native}"
                                                  onclick="yellamSeacrhFn(this)">
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
                      error: function(xhr) {
                          console.log("Error occurred: ", xhr.responseText);
                      }
                  });
              }
          });
      }
    });
</script>



<script>

    function yellamSeacrhFn(radio) {
      value = radio.value;      
      
      var name = $(radio).data('dname');
      var no = $(radio).data('dno');
      var address = $(radio).data('dnative')

        $('#name').val(name);
        $('#whatsappno').val(no);
        $('#native').val(address);
        $('#pulliid').val(value);
    }
</script>




@endsection

