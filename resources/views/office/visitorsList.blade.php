@extends('office.layout.layout')
@section('title', 'All Booths')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.12.13/xlsx.full.min.js"></script>
<!-- Font Awesome 4.7.0 (for 'fa fa-whatsapp') -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <div class="button-row " style= "text-align: end; margin-right:30px;">
        <a style="display:none" class="btn bg-gradient-success"  href="{{url('regisration')}}" ><i class="material-icons opacity-10">emoji_people</i>Register NEW Entry</a>
        <button type="button" id="saveAsExcel" class="btn btn-success" >Export Excel</button>           
    </div>     
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

<div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card my-4">
          <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
              <h6 class="text-white text-capitalize ps-3">Member List</h6>
            </div>
          </div>
            <div class="p-4">
                <div class="input-group input-group-dynamic mb-4">
                    <div class="input-group input-group-outline col-6">
                        <label class="form-label">Search here...</label>
                        <input id="search-input" type="text" class="form-control">
                    </div>
                </div>
                <form action="{{ url('editAllocate') }}" method="post">
                    @csrf <!-- Add CSRF token -->
                    <div class="input-group input-group-dynamic mb-4">
                          <div class="table-responsive" id="table_scroll">
                          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
    <table class="table align-items-center mb-0" id = "mytable" style="text-align:center;">
    <thead>
        <tr>
        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">S.No</th>
        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Action</th>
        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Action</th>
        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Pulli Id</th>
<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Name</th>
<!-- <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Father Name</th> -->
<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Spouse Name</th>
<!-- <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Phone number</th> -->
<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Whatsapp Number</th>
<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Spouse Number</th>
<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Family Name</th>
<!-- <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Email</th> -->
<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Address</th>
<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Karai</th>
<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Reference</th>
<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Native</th>
        </tr>
    </thead>
    <tbody>


    @php $ids = $data->firstItem(); @endphp
                        @foreach($data as $room)
                            <tr>
                            <td>
                            <p class="text-xs text-secondary mb-0"> {{ $ids }}</p></td>
                            <td> <a href="editmemeber/{{ $room->id }}" > <i class="material-icons opacity-10">edit</i></a></td>
                            <td id="whatsapp"> <a href="sendwhatsappmsg/{{ $room->id }}" > <i class="fa fa-whatsapp"></i></a>
    </td>
                            @if($_SERVER['HTTP_HOST']=="singaravelar.templesmart.in" ||  $_SERVER['HTTP_HOST'] == '127.0.0.1:8000') 
                            <td> <a href="userprofile/{{ $room->pulliid }}"><p class="text-xs  font-weight-bold mb-0"> {{ $room->pulliid }}</p></a>
                            </td>
                            @else
                            <td> <a href="#{{ $room->pulliid }}"><p class="text-xs  font-weight-bold mb-0"> {{ $room->pulliid }}</p></a>
                            </td>
                            @endif
                                <td> <p class="text-xs  font-weight-bold mb-0"> {{ $room->name }}</p>
                            </td>
                                <!-- <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->fathername }}</p>
                            </td> -->
                            <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->spousename }}</p>
                            </td>
                            <!-- <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->phonenumber }}</p>
</td>                         -->
                           <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->whatsappnumber }}</p>
                            </td>
                            <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->spousenumber }}</p>
                            </td>
                            <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->familynickname }}</p>
                            </td>
                            <!-- <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->email }}</p>
                            </td> -->
                                <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->address }}</p>
                            </td>
                            <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->karai }}</p>
                            </td>
                            <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->reference }}</p>
                            </td>
                            </td>
                                <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->native }}</p>
                            </td>
                            </tr>
                            @php     $ids++; @endphp
                        @endforeach
    </tbody>
</table>
</div>    
<div>
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
                                                <option value="100" {{ $items == 100 ? 'selected' : '' }}>100</option>
                                                <option value="150" {{ $items == 150 ? 'selected' : '' }}>150</option>
                                                <option value="200" {{ $items == 200 ? 'selected' : '' }}>200</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div  style= "margin-right:30px;">
                                {{ $data->appends(['items' => $items])->links() }}
                            </div>
                        </div>
                    </div>
        </div>
      </div>
    </div>
  
    <!-- @include('office.layout.footer') -->
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js">  </script>
  </script> 

 

<script>
    
    document.getElementById('pagination').onchange = function() {
        const url = new URL(window.location.href);
        url.searchParams.delete('items'); 
        url.searchParams.delete('page');
        url.searchParams.set('items', this.value);
        window.location.href = url.toString();
    };
</script>


  <script> 
        $(document).ready(function () { 
            $("#whatsapp").click(function () { 
                alert("Are you sure to send whatsapp Message!"); 
            }); 
        }); 
    </script> 
   <script>
   $(document).ready(function(){
    $("#saveAsExcel").click(function(){
        var workbook = XLSX.utils.book_new();
        
        // Get the HTML table
        var table = document.getElementById("mytable");
        
        // Filter out <td> elements that contain "person"
      
        // Convert filtered HTML table to worksheet
        var worksheet = XLSX.utils.table_to_sheet(table);
        
        // Set column widths (change width values as needed)
        var columnWidths = [
            {wch: 10},
            {wch: 60}, 
            {wch: 40}, 
            // Add more objects for additional columns if needed
        ];
        var rowHeights = [
            {hpx: 30}, // Set height of first row to 30 pixels
            {hpx: 30}, // Set height of second row to 40 pixels
            // Add more objects for additional rows if needed
        ];

        // Apply column widths
        worksheet['!cols'] = columnWidths;
        worksheet['!rows'] = rowHeights;
        
        // Add worksheet to workbook
        workbook.SheetNames.push("VisitorList");
        workbook.Sheets["VisitorList"] = worksheet;
      
        // Export the Excel file
        exportExcelFile(workbook);
    });
})

function exportExcelFile(workbook) {
    return XLSX.writeFile(workbook, "MemberList.xlsx");
}

    
    </script>

    <script>
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

        // var $rows = $('#mytable tr');
        // $('#search').keyup(function() {
        //     var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
            
        //     $rows.show().filter(function() {
        //         var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        //         return !~text.indexOf(val);
        //     }).hide();
        // });
        </script>




@stop
