@extends('office.layout.layout')
@section('title', 'Yelam List')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.12.13/xlsx.full.min.js"></script>
  <!-- Font Awesome 4.7.0 (for 'fa fa-whatsapp') -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  
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
<style>
  .scroll-btn {
            position: fixed;
            bottom: 12%; /* use bottom instead of top for better mobile placement */
            right: 55px; /* default distance from the right */
            cursor: pointer;
            padding: 4px 8px;
            width: 35px;
            height: 35px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 50%;
            opacity: 1;
            transition: opacity 0.3s;
            z-index: 1000;
        }

        #scrollRightBtn {
            right: 15px; /* placed to the far right */
        }

        /* Responsive tweaks for smaller screens */
        @media (max-width: 768px) {
            .scroll-btn {
                bottom: 8%;
                right: 70px;
                width: 30px;
                height: 30px;
            }

            #scrollRightBtn {
                right: 20px;
            }
        }

        .scroll-btn.disabled {
            background-color: #bfbfbf; /* Mild color for disabled state */
            cursor: not-allowed;
            opacity: 0.7;
        }
</style>
<script>
        document.addEventListener("DOMContentLoaded", function () {
            const table = document.getElementById('table_scroll');
            const scrollLeftBtn = document.getElementById('scrollLeftBtn');
            const scrollRightBtn = document.getElementById('scrollRightBtn');

            function updateButtons() {
                const scrollLeft = table.scrollLeft;
                const maxScrollLeft = table.scrollWidth - table.clientWidth;

                // Leftmost check
                if (scrollLeft <= 0) {
                    scrollLeftBtn.disabled = true;
                    scrollLeftBtn.classList.add('disabled');
                } else {
                    scrollLeftBtn.disabled = false;
                    scrollLeftBtn.classList.remove('disabled');
                }

                // Rightmost check (fixing boundary detection)
                if (scrollLeft >= maxScrollLeft - 2) {  
                    scrollRightBtn.disabled = true;
                    scrollRightBtn.classList.add('disabled');
                } else {
                    scrollRightBtn.disabled = false;
                    scrollRightBtn.classList.remove('disabled');
                }
            }


            function scrollTable(direction) {
                const scrollAmount = 300;

                if (direction === 'left') {
                    table.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                } else {
                    table.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                }

                setTimeout(updateButtons, 500);
            }

            // Add event listeners
            table.addEventListener('scroll', updateButtons);
            scrollLeftBtn.addEventListener('click', () => scrollTable('left'));
            scrollRightBtn.addEventListener('click', () => scrollTable('right'));
            updateButtons(); // Ensure correct initial state
        });
    </script>


<button id="scrollLeftBtn" class="scroll-btn disabled" disabled>⬅</button>
<button id="scrollRightBtn" class="scroll-btn">➡</button>

  <div class="button-row" style= "text-align: end;margin-right:30px;">
  <a style="display:none" class="btn bg-gradient-success"  href="{{url('yelamentryform')}}" ><i class="material-icons opacity-10">person_add</i> Add Entry</a>
  <button type="button" id="saveAsExcel" class="btn btn-success" style="display:none">Export Excel</button>           
  <button type="button" id="saveExcel" class="btn btn-success" >Export All</button>           
</div>
<div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card my-4">
          <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">Yelam List</h6>
                @if (session('message'))
                    <h6 class="text-white text-capitalize ps-3">{{ session('message') }}</h6>
                @endif
            </div>
          </div>
            <div class="p-4">

                    <div class="input-group input-group-dynamic mb-4">
                    <div class="input-group input-group-outline col-6">
              <label class="form-label">Search by Name, Pullid, YesNo</label>
              <input id="search-input" type="text" class="form-control" value="{{ request('search') }}">
          </div>
                      
                          <div class="table-responsive" id="table_scroll">
    <table class="table align-items-center mb-0" id="mytable" style='text-align:center;'>

    <thead>
        <tr>
        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Yellam Id</th>
        {{-- <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Action</th>--}}
        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Action</th> 
        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Paid Status</th>
        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Pulli Id</th>
        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Yelam Porul</th>

<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Name</th>

<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Yelam Type</th>


<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Value</th>

<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Native</th>
<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Manual Book Sr.No</th>
<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7" style="display:none;">Reference</th>
<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Remarks</th>
<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Guest Name</th>
<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Guest Whatsapp </th>
<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Guest Native </th>
@if($_SERVER['HTTP_HOST']=="singaravelar.templesmart.in") 
<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Yelam Entry Receipt
@endif

        </tr>
    </thead>
    <tbody>
        
        @php $ids = $data->firstItem(); @endphp
                        @foreach($data as $room)
                            
                            <tr>
                            <td>
                            <p class="text-xs text-secondary mb-0"> {{ $room->id }}</p></td> 
    </td>
    {{-- <td>  --}}
    {{-- <i class="material-icons opacity-10 btn-tooltip" data-bs-placement="bottom"  data-bs-toggle="tooltip"  title="Delete"  data-animation="true" onclick="deletemember( {{$room->id}} )">delete</i></td> --}}
    <td id="whatsapp"> <a href="yelamwhatsappmessage/{{ $room->id }}" > <i class="fa fa-whatsapp"></i></a></td>
    <td>
    @if($room->payment=='Not Paid')

    <p class="text-xs font-weight-bold mb-0" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="{{ $room->id }}" data-name="{{ $room->name }}" data-yelamporul="{{ $room->things }}" data-yelamtype="{{ $room->yelamtype }}" data-amount="{{ $room->value }}">
    <i class="fa fa-rupee  text-warning">Change Paid Status</i>
    
    </p>   
     @else

        @if($_SERVER['HTTP_HOST']=="singaravelar.templesmart.in") 
        <p class="text-xs font-weight-bold mb-0">
            <a href="{{ url('receipt/' . $room->id) }}" target="_blank">
                <i class="fa fa-check-circle text-success"></i> {{ $room->payment }}
            </a>
        </p>
        @else
        <p class="text-xs font-weight-bold mb-0">
            <i class="fa fa-check-circle text-success"></i> {{ $room->payment }}
        </p>
        @endif
     
    

    @endif
</td>
    
                            <td> <p class="text-xs  font-weight-bold mb-0"> {{ $room->pulliid }}</p>
                            </td>
                            <td> <p class="text-xs  font-weight-bold mb-0"> {{ $room->things }}</p>
                            </td>
                                <td> <p class="text-xs  font-weight-bold mb-0"> {{ $room->name }}</p>
                            </td>
                                <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->yelamtype }}</p>
                            </td>
                            <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->value }}</p>
                            </td>
                            
                            <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->native }}</p>
                            </td>
                            <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->bookid }}</p>
                            </td>
                            <td style="display:none;"> <p class="text-xs font-weight-bold mb-0"> {{ $room->reference }}</p>
                            </td>
                            <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->remark }}</p>
                            </td>
                              <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->nameguest }}</p>
                            </td>
                           
                             <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->whatsappnoguest }}</p>
                            </td>
                              <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->nativeguest }}</p>
                            </td>

                            @if($_SERVER['HTTP_HOST']=="singaravelar.templesmart.in") 
                            <td>
                                <p class="text-xs font-weight-bold mb-0">
                                    <a href="{{ url('onlyyellamentryreceipt/' . $room->id) }}" target="_blank">
                                       <i class="fa fa-print text-success"></i>   
                                    </a>
                                </p>
                            </td>
                            @endif
                            
                           
                            </tr>
                            @php     $ids++; @endphp
                        @endforeach
    </tbody>
</table>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Paid Status</h5>
                                        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                    <form id="savepaymentform" action="javascript:void(0);" method="POST">
                                            @csrf
                                            <div class="row">
                                            
                                            <div class="form-group">
                                         <label for="reference">Yelam Id</label>
                                         <input type="text" class="form-control" id="id" name="id"  readonly>
                                         </div>
                                            <div class="form-group">
                                         <label for="reference">Name:</label>
                                         <input type="text" class="form-control" id="name" name="name" readonly>
                                         </div>
                                       <div class="form-group">
                                      <label for="yelamporul">Yelamporul:</label>
                                     <input type="text" class="form-control" id="yelamporul" name="yelamporul"  readonly>
                                           </div>
                                           <div class="form-group">
                                         <label for="reference">Receipt No:</label>
                                         <input type="text" class="form-control" id="reference" name="reference" required>
                                         </div>
                                           <div class="form-group">
                                      <label for="yelamporul">Amount:</label>
                                      <input type="number" class="form-control" id="amount" name="amount"  required>
                                           </div>
                                    
                                           
                                            <div id="pending_years_container" class="mb-4 py-1">
                                            </div>   
                                            <div id="paid_years_container" class="mb-4 py-1">
                                            </div> 
                                    <div class="modal-footer">
                                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                                        <button id="updatePr" type="submit" class="btn bg-gradient-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                       
                        <!-- End Modal -->
                </div>    
            <div>
        </div>
    </div>
                     
</div>
</div>
                <div class="row">
                    <div class="col-md-12 d-flex align-items-center justify-content-between">
                        <div  style= "margin-left:30px;margin-top:-10px">
                            <form>
                                <div class="input-group input-group-outline" >
                                    <div class="items-per-page d-flex align-items-center">
                                        <select id="pagination" class="form-control small-select" >
            
                                            <option value="" >Per Page</option>            
                                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                                            <option value="150" {{ $perPage == 150 ? 'selected' : '' }}>150</option>
                                            <option value="200" {{ $perPage == 200 ? 'selected' : '' }}>200</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div  style= "margin-right:40px;">
                            {{ $data->appends(['items' => $perPage])->links() }}
                        </div>
                    </div>
                </div>
      </div>
    </div>
  
    <!-- @include('office.layout.footer') -->
  </div>

  <div class="popupreceipt" style="display: none"></div>

  <script>
    $(".modal").on("hidden.bs.modal", function(){
        $(".modal-body1").html("");
    });
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('pagination').onchange = function() {
            const url = new URL(window.location.href);
            url.searchParams.delete('items'); 
            url.searchParams.delete('page');
            url.searchParams.set('items', this.value);
            window.location.href = url.toString();
        };
    });
</script>

  <script>
   $(document).ready(function(){
    $("#saveAsExcel").click(function(){
        var workbook = XLSX.utils.book_new();
        var table = document.getElementById("mytable");
        var worksheet = XLSX.utils.table_to_sheet(table);
        var columnWidths = [
            {wch: 10},
            {wch: 60}, 
            {wch: 40}, 
        ];
        var rowHeights = [
            {hpx: 30}, 
            {hpx: 30}, 
        ];
        worksheet['!cols'] = columnWidths;
        worksheet['!rows'] = rowHeights;
        workbook.SheetNames.push("VisitorList");
        workbook.Sheets["VisitorList"] = worksheet;
        exportExcelFile(workbook);
    });
})

function exportExcelFile(workbook) {
    return XLSX.writeFile(workbook, "YelamList.xlsx");
}
</script>
<script>
    
// var $rows = $('#mytable tr');
// $('#search').keyup(function() {
//     var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
    
//     $rows.show().filter(function() {
//         var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
//         return !~text.indexOf(val);
//     }).hide();
// });
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("search-input");

            if (searchInput) {
                console.log("Search input found!");

                searchInput.addEventListener("keypress", function (event) {
                    if (event.key === "Enter") {
                        const inputValue = searchInput.value;
                        console.log("Searching for:", inputValue);

                        const url = new URL(window.location.href);
                        url.searchParams.set('search', inputValue);
                        window.location.href = url.toString();
                    }
                });
            } else {
                console.error("Search input not found!");
            }
        });
    </script>

<script>
$(document).ready(function(){
    $("#saveExcel").click(function(){
        $.ajax({
            url: 'export', 
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'get',
            success: function(response) {
                console.log(response); 
                var data = response.data; 
                var worksheet = XLSX.utils.json_to_sheet(data);
                var columnWidths = [
                    {wch: 10},
                    {wch: 60}, 
                    {wch: 40}, 
                    {wch: 40}, 
                ];
                worksheet['!cols'] = columnWidths;
                var workbook = XLSX.utils.book_new();
                workbook.SheetNames.push("VisitorList");
                workbook.Sheets["VisitorList"] = worksheet;
                exportExcelFile(workbook);
            },
            // error: function(xhr, status, error) {
            //     console.error(xhr.responseText);
            //     console.error("Status:", status);
            //     console.error("Error:", error);
            //     alert("An error occurred while fetching data from the database.");
            // }
        });
    });
});

function exportExcelFile(workbook) {
    return XLSX.writeFile(workbook, "YellamAllList.xlsx");
}


</script>

<script>
    $('#exampleModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id');
        var name = button.data('name');
        // var reference = button.data('reference');
        var yelamporul = button.data('yelamporul');
        var yelamtype = button.data('yelamtype');
        var amount = button.data('amount');

        // Update modal inputs with the fetched data
        var modal = $(this);
        modal.find('#id').val(id);
        modal.find('#name').val(name);
        // modal.find('#reference').val(reference);
        modal.find('#yelamporul').val(yelamporul);
        modal.find('#yelamtype').val(yelamtype);
        var host = "{{ $_SERVER['HTTP_HOST'] }}";
        // if (host !== "singaravelar.templesmart.in") {
            console.log("Welcome! to "+host);
            $.ajax({
                url: '/yelamlist',
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
    
                    // console.log(response);
                    const container = $('#pending_years_container');
                    const container1 = $('#paid_years_container');
                    container.empty();
                    container1.empty();
    
                    let total = amount;
                    container.append('<h4 class="form-check-label">Total Credited :</h4>');
                    container1.append('<h4 class="form-check-label">Yellam porul Total :</h4>');
                    const html = `
                            <div class="input-group input-group-static mb-2">
                                <span class="d-block px-3 py-1 rounded" style="background-color: #ffe6f0; color: #cc0066; font-weight: bold;">
                                    Overall value  ⇒ ₹${total}
                                </span>
                            </div>`;
                        
                    container1.append(html);
    
                    
                    if (response.data) {
                        const totalCredit = response.data.total_credit || 0;
                        const html = `
                            <div class="input-group input-group-static mb-2">
                                <span class="d-block px-3 py-1 rounded" style="background-color: #ffe6f0; color: #cc0066; font-weight: bold;">
                                    Total paid ⇒ ₹${totalCredit}
                                </span>
                                
                            </div>`;
                        container.append(html);
                        // console.log(response.data.total_credit);
                        total -= parseFloat(response.data.total_credit);
                        
                    } else {
                        const html = `
                            <div class="input-group input-group-static mb-2">
                                <p>No amount paid</p>
                            </div>`;
                        
                        container.append(html);
                        
                    }
                    modal.find('#amount').attr('max', total);
                    modal.find('#amount').val(total);
                },
                error: function (xhr) {
                    console.log("Error occurred: ", xhr.responseText);
                }
            });
        // }
        // else{
        //     modal.find('#amount').attr('max', amount);
        //     modal.find('#amount').val(amount);

        // }
    });
</script>

<script>
    function deletemember(id) {
        Swal.fire({
            title: 'Are you sure you want to Delete this Yellam?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Yellam Deleted!',
                    text: 'The Yellam has been successfully Deleted.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                setTimeout(function() {
                    window.location.href = 'deletemember/' + id;
                }, 2000);
            } else {
            }
        });
    }
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('savepaymentform');

        form.addEventListener('submit', function (event) {
            event.preventDefault(); 

            const formData = new FormData(form); 

            $.ajax({
                url: 'savepayment',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log("Success:", response);
            
                if (response.data) {
                    if (response.status === true) {
                        var host = "{{ $_SERVER['HTTP_HOST'] }}";
                        if (host == "singaravelar.templesmart.in") {
                            console.log("Welcome! to "+host);
                            const popup_container = document.querySelector('.popupreceipt');
                            const pop_shadow = popup_container.attachShadow({mode: 'open'});
                            let receiptsHTML = '';
                            const array = response.data;
                            const now=(array.created_at).split(' ');
                            const padded= String(array.yelamporul).padStart(5, '0');
                            receiptsHTML += `
                                <div class="status_show" style="margin-top:-5%">
                                    <div class="receipt">
                                        <button class="Btn">
                                            <a href="{{url('receipt/${array.pay_to_txt}')}}" target="_blank">
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
                                                    <div class="receipt_id">ரசீது எண்: YELAM-${array.receipt_id}-${padded}</div>
                                                </div>
                                                <div class="body">
                                                    <p><span class="label">திருமதி/திரு </span>
                                                    <span class="dotted">
                                                            <span class="dotted_p">${array.ref_txt}</span>
                                                        </span> 
                                                        <span class="label">அவர்களிடமிருந்து ரூபாய்( </span>
                                                        <span class="dotted">
                                                            <span class="dotted_p">${array.amount}</span>
                                                        </span>
                                                        <span class="label">மட்டும் ) ஏலத்தொகை நன்றியுடன் பெற்றுக் கொண்டோம்.</span> 
                                                    </p>
                                                    <p><span class="label">பொருள்&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</span> 
                                                        <span class="dotted">
                                                            <span class="dotted_p">${array.things}</span>
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="footer">
                                                    <div class="amount-box">₹ ${array.amount} 
                                                        <span style="font-size: 10px;color:rgba(117, 117, 117, 0.808);">/-${array.amount} </span></div>
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
                            Swal.fire('Success!', response.data.message, 'success').then(() => {
                            window.location.reload();
                            });
                        }
                    } else {
                        Swal.fire('Error!', data.message || 'Check the Form Values.', 'error');
                    }
                    
                }
                else{
                    Swal.fire('oops!', 'Try again.', 'info');
                }

            
                },
                error: function (xhr) {
                    console.error("AJAX Error:", xhr.responseText);
                }
            });
        });
    });
</script>





@stop