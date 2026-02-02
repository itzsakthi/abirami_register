@extends('office.layout.layout')
@section('title', 'Pulli Vari')

@section('content')


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.12.13/xlsx.full.min.js"></script>
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
                            <h6 class="text-white text-capitalize ps-3">Pulli Vari List</h6>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="input-group input-group-dynamic mb-4">
                            <div class="input-group input-group-outline col-6">
                            <label class="form-label">Search here...</label>
                            <input id="search-input" type="text" class="form-control" value="{{ request('search') }}" >
                            </div>
                            <div class="table-responsive" id="table_scroll"style="width:100%" >
                            
                                <table class="table align-items-center mb-0" style="width:100%;text-align:center" id = "mytable">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">S.No</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">PULLI ID </th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">NAME </th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">NATIVE </th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">PHONENO </th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">CURRENT YEAR </th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">PREVIOUS YR </th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">LAST PAID </th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">PAID REPORT </th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">PENDING REPORT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php $ids = $data->firstItem(); @endphp
                                                    
                                    @forelse($data as $room)
                                        <tr>
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $ids }}</p></td>
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $room->pulliid }}</p></td>
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $room->name }}</p></td>
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $room->native }}</p></td>
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $room->whatsappnumber }}</p></td>
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $room->current_yr_amt ?: "0" }}</p></td>
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $room->previous_yr_amt ?: "0" }}</p></td>
                                            <td><p class="text-xs  font-weight-bold mb-0"> {{ $room->lastpaid }}</p></td> 
                                            <td>
                                                <a href="#paid_report"><p class="text-xs font-weight-bold mb-0 paidReport" 
                                                data-bs-toggle="modal" data-bs-target="#exampleModal" 
                                                data-id="{{ $room->pulliid }}" data-name="{{ $room->name }}"
                                                data-yelamporul="{{ $room->native }}" 
                                                data-yelamtype="{{ $room->whatsappnumber }}"  data-reports="{{ true }}" 
                                                >Paid Report</p>
                                                </a>     
                                            </td>
                                            <td>
                                                <a href="#pending_report"><p class="text-xs font-weight-bold mb-0 pendingReport" 
                                                data-bs-toggle="modal" data-bs-target="#exampleModal" 
                                                data-id="{{ $room->pulliid }}" data-name="{{ $room->name }}"
                                                data-yelamporul="{{ $room->native }}" 
                                                data-yelamtype="{{ $room->whatsappnumber }}" data-reports="{{ false }}" 
                                                >Pending Report</p></a>
                                            </td>   
                                        
                                        </tr>
                                        @php $ids++; @endphp
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center text-danger py-4">
                                                    No matching records found.
                                                </td>
                                            </tr>
                                        @endforelse                                            
                                    </tbody>
                                </table>
                            </div>    
                        <div>
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
                            {{ $data->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Paid Report</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id" value="">
                    <div class="form-group">
                        <label for="reference">Pulli Id</label>
                        <input type="text" class="form-control" id="id" name="id" value="" readonly>
                    </div>
                    <div class="form-group">
                        <label for="reference">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="" readonly>
                    </div>
                <div class="form-group">
                        <label for="yelamporul">Native:</label>
                        <input type="text" class="form-control" id="yelamporul" name="yelamporul" value="" readonly>
                    </div>
                    <div class="form-group">
                        <label for="reference">Mobile No:</label>
                        <input type="text" class="form-control" id="reference" name="reference" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="totalamt">Total:</label>
                        <input type="text" class="form-control" id="totalamt" name="totalamt" value="" readonly>
                    </div>
                    <div id="pending_years_container" class="mb-4 py-1"></div>   
                    <div id="paid_years_container" class="mb-4 py-1"></div>   
                    
                    
                    <div class="modal-footer">
                                                    
                        <button id="payBtn" type="button" class="btn btn-success" >Pay</button>
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    
    <!-- End Modal -->
    </div>
  
    <!-- @include('office.layout.footer') -->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js">  </script>
  </script> 

 
    
    <script>
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

    </script>

        
    <script>
        $(document).ready(function () {
            $('#exampleModal').on('show.bs.modal', function (event) {
                const button = event.relatedTarget;

                const reports = button.getAttribute('data-reports');

                $('#id').val(button.getAttribute('data-id'));
                $('#name').val(button.getAttribute('data-name'));
                $('#yelamporul').val(button.getAttribute('data-yelamporul'));
                $('#reference').val(button.getAttribute('data-yelamtype'));

                const payBtn = document.getElementById('payBtn');
                payBtn.onclick = function () {
                    console.log("hiui");
                    
                    const id = document.getElementById('id').value;
                    window.location.href = 'income_entry?type=pullivari&pulliid=' + id;
                };

                if (reports == "1" || reports === "true") {
                    $("label[for='totalamt']").text("Total Paid:");
                    $("#exampleModalLabel").text("Paid Report");
                    // $('#totalamt').val(button.getAttribute('data-totalamt'));
                    $('#payBtn').hide();
                    $('#pending_years_container').hide();
                    $('#paid_years_container').show();


                    const id = $('#id').val();
                    $.ajax({
                        url: '/paymentstatus',
                        method: 'POST',
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            const container = $('#paid_years_container');
                            container.empty();
                            container.append('<h4 class="form-check-label">Year wise Paid :</h4>');
                            let total = 0;
                            if (Array.isArray(response.paidAmt) && response.paidAmt.length > 0) {
                                response.paidAmt.forEach(item => {
                                    const html = `
                                        <div class="input-group input-group-static mb-2">
                                            <span class="d-block px-3 py-1 rounded" style="background-color: #ffe6f0; color: #cc0066; font-weight: bold;">
                                                ${item.year} ⇒ ₹${item.amt}
                                            </span>
                                        </div>`;
                                    container.append(html);
                                    total += parseFloat(item.amt);
                                });
                                $('#totalamt').val(total);
                            } else {
                                container.append('<p>No amount paid</p>');
                                $('#totalamt').val(0);
                            }
                        },
                        error: function (xhr) {
                            console.log("Error occurred: ", xhr.responseText);
                        }
                    });


                } else {
                    $("label[for='totalamt']").text("Total Pending:");
                    $("#exampleModalLabel").text("Pending Report");

                    $('#payBtn').show();
                    $('#pending_years_container').show();
                    $('#paid_years_container').hide();

                    const id = $('#id').val();
                    $.ajax({
                        url: '/paymentstatus',
                        method: 'POST',
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            const container = $('#pending_years_container');
                            container.empty();
                            container.append('<h4 class="form-check-label">Year wise Pending :</h4>');
                            let total = 0;
                            if (Array.isArray(response.amt) && response.amt.length > 0) {
                                response.amt.forEach(item => {
                                    const html = `
                                        <div class="input-group input-group-static mb-2">
                                            <span class="d-block px-3 py-1 rounded" style="background-color: #ffe6f0; color: #cc0066; font-weight: bold;">
                                                ${item.year} ⇒ ₹${item.amt}
                                            </span>
                                        </div>`;
                                    container.append(html);
                                    total += parseFloat(item.amt);
                                });
                                $('#totalamt').val(total);
                            } else {
                                container.append('<p>No pending amount.</p>');
                                $('#totalamt').val(0);
                            }
                        },
                        error: function (xhr) {
                            console.log("Error occurred: ", xhr.responseText);
                        }
                    });
                }
            });
        });

    </script>





@stop
