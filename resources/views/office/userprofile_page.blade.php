    @extends('office.layout.layout')
    @section('title', 'User Profile Search')

    @section('content')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">User Profile Search</h6>

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

                            <div class="container mt-4">

                           

                                <div class="row" style="padding:4px">
                                    <div class="col-md-4" style="padding:8px">
                                        <input type="text"
                                               id="pulliSearch"
                                               class="form-control"
                                               placeholder="Enter Pulli ID/ Whatsapp No/ Name/ Family name"
                                               style="
                                                   box-shadow: 0 2px 6px rgba(0,0,0,0.25);
                                                   border-radius: 6px;
                                                   padding:8px;
                                                   padding-top:2px;
                                               ">
                                    </div>
                                
                                    <div class="col-md-2">
                                        <button id="searchBtn"
                                                class="btn btn-success"
                                                style="
                                                    box-shadow: 0 2px 6px rgba(0,0,0,0.25);
                                                    border-radius: 6px;
                                                ">
                                            Search
                                        </button>
                                    </div>

                                    <table class="table mt-2 mb-6" style="display: none; background-color: #F5F5F5; width: 100%; text-align: center;" id="yellam_search_table">
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
                                        
                                </div>
                                    

                             

                                <div id="profileResult" class="mt-4"></div>
                            </div>

                        </div>

                    </div>









                    <!-- @include('office.layout.footer') -->
                </div>


                @stop




                <!-- <script src="assets/js/new.js"></script> -->
                <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


                <script src="{{ asset('assets/js/new.js') }}"></script>


                <!-- ====== ionicons ======= -->
                <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
                <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



                <script>

                        function searchUserFn (radio) {
                            const pulliid = radio.value
                            if (!pulliid) return;

                            $('#profileResult').html('<p>Loading...</p>');

                            $.ajax({
                                url: `/userprofile/${pulliid}`,
                                type: 'GET',
                                success: function (res) {
                                    const $html = $('<div>').html(res);
                                
                                    $('#profileResult').html($html.html());

                                    (function initProfileShadow() {
                                        const container = document.getElementById('profileids');
                                        if (!container) return;

                                        // prevent double attach
                                        if (container.shadowRoot) return;

                                        const shadow = container.attachShadow({ mode: 'open' });

                                        shadow.innerHTML = `
                                            <link rel="stylesheet" href="/css/paycard.css">
                                            ${container.innerHTML}
                                        `;

                                        container.innerHTML = '';
                                    })(); // self-invoking
                                   

                                },
                                error: function () {
                                    $('#profileResult').html('<p style="color:red">User not found</p>');
                                }
                            });
                        }

                      

                </script>



<script>

function yellamSeacrhFn(radio) {
  value = radio.value;      
  console.log(radio);
  console.log(value);
  
  
  var name = $(radio).data('dname');
  var no = $(radio).data('dno');
  var address = $(radio).data('dnative')

    $('#name').val(name);
    $('#whatsappno').val(no);
    $('#native').val(address);
    $('#pulliid').val(value);
}

$(document).ready(function () {

    const $input = $('#pulliSearch');
    const $btn   = $('#searchBtn'); 

    function doPulliSearch() {
        const value = $input.val().trim();
        if (!value) return;

        $.ajax({
            url: '/pulliidSearch',
            type: 'POST',
            data: {
                vari_search: value,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                console.log(response);

                $('#yellam_search_table').show();
                const $tbody = $('#yellam_search_table tbody');
                $tbody.empty();

                if (response.data && response.data.length > 0) {
                    let ids = 1;

                    $.each(response.data, function (i, item) {
                        const row = `
                            <tr>
                                <td>
                                    <input type="radio" name="select_vari"
                                        value="${item.pulliid}"
                                        data-dname="${item.name}"
                                        data-dno="${item.whatsappnumber}"
                                        data-dnative="${item.native}"
                                        onclick="searchUserFn(this)">
                                </td>
                                <td>${ids}</td>
                                <td>${item.pulliid}</td>
                                <td>${item.name}</td>
                                <td>${item.whatsappnumber}</td>
                            </tr>
                        `;
                        ids++;
                        $tbody.append(row);
                    });

                } else {
                    $tbody.append('<tr><td colspan="5">No List found</td></tr>');
                }
            },
            error: function (xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    }

    /*  ENTER KEY */
    $input.on('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            doPulliSearch();
        }
    });

    /* SEARCH BUTTON CLICK */
    $btn.on('click', function (e) {
        console.log("btnnnn");
        
        e.preventDefault();
        doPulliSearch();
    });

    $('#pulliSearch').on('input', function () {
        if (!this.value.trim()) {
            $('#yellam_search_table').hide();
        }
    });

});
</script>



                </body>