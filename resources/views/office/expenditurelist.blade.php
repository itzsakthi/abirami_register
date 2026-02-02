@extends('office.layout.layout')
@section('title', 'Expenses List')

@section('content')

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .form-container {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-top: 1rem;
        }

        .radio-group {
            display: flex;
            gap: 20px; 
        }

        .form-check {
            display: flex;
            gap: 0.5rem;
        }

        .form-check-input {
            margin: 0;
        }

        .form-check-label {
            font-size: 14px;
            font-weight: 500;
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

    <div class="button-row" style= "text-align: end; margin-right:30px">
      <!-- <button type="button" id="saveAsExcel" class="btn btn-success 1" style="display:none" >Export Excel</button> 
        <button type="button" id="saveAsExcel1" class="btn btn-success 2" >Export Excel</button>            -->
        <button type="button" id="saveExcel2" class="btn btn-success" >Export All</button>           
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Expenses List</h6>
                            <button id="scrollLeftBtn" class="scroll-btn disabled" disabled>⬅</button>
                            <button id="scrollRightBtn" class="scroll-btn">➡</button>
                        </div>
                    </div>
                    <form action="" class="form-container">
                        <div class="radio-group">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="master" name="user_type" id="customRadio1">
                                <label class="form-check-label" for="customRadio1">EXPENSE MASTER</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="entry" name="user_type" id="customRadio2">
                                <label class="form-check-label" for="customRadio2">EXPENSE ENTRY</label>
                            </div>
                        </div>

                        
                    </form>
                    <div class="p-4">
                        <div class="input-group input-group-dynamic mb-4" style="margin-top:-15px" >
                            <div class="input-group input-group-outline col-6">
                                <label class="form-label">Search here...</label>
                                <input id="search" type="text" class="form-control s">
                                <input id="search1" type="text" class="form-control s1">
                            </div>
                            
                            <div class="table-responsive" style="width: 100%;">
                                <table class="table align-items-center mb-0 master" style=" text-align: center;display:none;width:100%;" id="mytable">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">SNO</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Expense name</th> 
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $ids = $data1->firstItem();@endphp
                                            @foreach($data1 as $room)
                                                <tr>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"> {{ $ids }}</p></td> 
                                                    </td>
                                                    <td> <p class="text-xs  font-weight-bold mb-0"> {{ $room->expenses_name }}</p>
                                                    </td>
                                                    <td> <p class="text-xs  font-weight-bold mb-0"> {{ $room->description }}</p>
                                                    </td>
                                                </tr>
                                            @php     $ids++; @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            <div>
                            <div class="table-responsive" style="width: 100%;" id="table_scroll">
                                <table class="table mb-0 entry" style="display:none; width: 100%; text-align: center;"  id="mytable1">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">SNO</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">expenses_name</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">pay_to</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">debit</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">pay_mode</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">remarks</th>
                                            <!-- <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">authorized_by</th> -->
                                            <!-- <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">delete</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $ids1 = $data->firstItem(); @endphp
                                            @foreach($data as $room)
                                            <tr>
                                                <td> <p class="text-xs font-weight-bold mb-0"> {{ $ids1	 }}</p></td>
                                                <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->ref_txt }}</p></td>
                                                <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->pay_to_txt }}</p></td>
                                                <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->amount }}</p>
                                                </td>
                                                <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->pay_mode }}</p>
                                                </td>
                                                <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->remarks }}</p>
                                                </td>
                                                <!-- <td> <p class="text-xs font-weight-bold mb-0"> {{ $room->id }}</p>
                                                </td> -->
                                                <!-- <td>
                                                    <button onclick="deletemember( {{ $room->id }} )" class="btn btn-danger">Delete</button>
                                                </td> -->
                                            </tr>
                                            @php     $ids1++;@endphp
                                                
                                            @endforeach
                                    </tbody>
                                </table>
                            <div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="master_page hidden" style="display:none">
            <div class="row">
                <div class="col-md-12 d-flex align-items-center justify-content-between">
                    <div  style= "margin-left:30px;margin-top:-10px">
                        <form>
                            <div class="input-group input-group-outline" >
                                <div class="items-per-page d-flex align-items-center">
                                    <select id="pagination1" class="form-control small-select" >
        
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
                        {{ $data1->appends(['items' => $perPage,'type'=>'master'])->links() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="entry_page hidden" style="display:none">
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
                        {{ $data->appends(['items' => $perPage,'type'=>'entry'])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
        <!-- @include('office.layout.footer') -->

    <script>

        $(document).ready(function() {
            
            const urlParams = new URLSearchParams(window.location.search);
            const type = urlParams.get('type');
            console.log(type)
            if (type === 'master') {
                $('#customRadio1').prop('checked', true);
                $(".master").show();
                $(".master_page").show();
                $(".add").show();
                $(".1").show();
                $(".s").show();
                $(".entry").hide();
                $(".entry_page").hide();
                $(".s1").hide();
                $(".add1").hide();
                $(".2").hide();

            } else if (type === 'entry') {
                $('#customRadio2').prop('checked', true);
                $(".entry").show();
                $(".entry_page").show();
                $(".s1").show();
                $(".add1").show();
                $(".2").show();
                $(".master").hide();
                $(".master_page").hide();
                $(".add").hide();
                $(".1").hide();
                $(".s").hide();
            }
            else{
                $('#customRadio2').prop('checked', true);
                $(".entry").show();
                $(".entry_page").show();
                $(".s1").show();
                $(".add1").show();
                $(".2").show();
                $(".master").hide();
                $(".master_page").hide();
                $(".add").hide();
                $(".1").hide();
                $(".s").hide();
            }
            
            $(':radio[id=customRadio1]').change(function() {
            $(".entry").hide(); $(".master").show();$(".master_page").show();
            $(".entry_page").hide();
            $(".1").show();
            $(".2").hide();
            $(".s").show();
            $(".s1").hide();
            $(".add1").hide();
            $(".add").show();
            });

            $(':radio[id=customRadio2]').change(function() {
            $(".entry").show();
            $(".master").hide();
            $(".master_page").hide();
            $(".entry_page").show();
            $(".1").hide();
            $(".2").show();
            $(".s").hide();
            $(".s1").show();
            $(".add1").show();
            $(".add").hide();
            });

            $('.add').click(function() {
                window.location.href = 'ExpenditureEntry?type=master';
            });
            $('.add1').click(function() {
                window.location.href = 'ExpenditureEntry?type=entry';
            });

            
            document.getElementById('pagination').onchange = function() {
                const url = new URL(window.location.href);
                url.searchParams.delete('items'); 
                url.searchParams.delete('page');
                url.searchParams.set('type','entry');
                url.searchParams.set('items', this.value);
                window.location.href = url.toString();
            };
        
        
            document.getElementById('pagination1').onchange = function() {
                const url = new URL(window.location.href);
                url.searchParams.delete('items');
                url.searchParams.delete('page');
                url.searchParams.set('type','master'); 
                url.searchParams.set('items', this.value);
                window.location.href = url.toString();
            };
            

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
                workbook.SheetNames.push("List");
                workbook.Sheets["List"] = worksheet;
                exportExcelFile(workbook);
            });

            $("#saveAsExcel1").click(function(){
                var workbook = XLSX.utils.book_new();
                var table = document.getElementById("mytable1");
                for (let row of table.rows) {
                
                    row.deleteCell(7);
                        
                }
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
                workbook.SheetNames.push("List");
                workbook.Sheets["List"] = worksheet;
                exportExcelFile(workbook);
            });

            
            var $rows = $('#mytable tr');
            $('#search').keyup(function() {
                var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
                
                $rows.show().filter(function() {
                    var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                    return !~text.indexOf(val);
                }).hide();
            });
            var $row = $('#mytable1 tr');
            $('#search1').keyup(function() {
                var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
                
                $row.show().filter(function() {
                    var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                    return !~text.indexOf(val);
                }).hide();
            });

            $("#saveExcel2").click(function(){
                
                fetch("{{ route('expenditurelist') }}", {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest' 
                        }
                    })
                    .then(response => response.json())
                    .then(response => {            
                        console.log(response);
                        // Create worksheet from the fetched data
                        const ws = XLSX.utils.json_to_sheet(response.data.map((item, index) => ({
                            'S.No': index + 1,
                            'Expense Name': item.expenses_name,
                            'Pay to': item.pay_to,
                            'Debit': item.debit,
                            'Pay mode': item.pay_mode,
                            'Remarks': item.remarks,
                            'Auth by': item.authorized_by,
                            
                        })));

                        const ws1 = XLSX.utils.json_to_sheet(response.data1.map((item, index) => ({
                            'S.No': index + 1,
                            'Expense Name': item.expenses_name,
                            'Description': item.description,
                            
                        })));
                        
                        // Create workbook and add worksheet
                        const wb = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(wb, ws, "enquiry list");
                        XLSX.utils.book_append_sheet(wb, ws1, "master list");
                        
                        // Generate file and download
                        XLSX.writeFile(wb, "list.xlsx");          
                    
                    })
                    .catch(error => {
                        console.error('Export failed:', error);
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                        alert('Export failed. Please try again.');
                    });
            });

            function exportExcelFile(workbook) {
                return XLSX.writeFile(workbook, "List.xlsx");
            }

        });
        function deletemember(id) {
            Swal.fire({
                title: 'Are you sure you want to Delete this Yellam?',
                icon: 'warning',
                showCancelButton: true,
                allowOutsideClick:false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Id Deleted!',
                        text: 'The ID has been successfully Deleted.',
                        icon: 'success',
                        allowOutsideClick:false,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    setTimeout(function() {
                        window.location.href = 'delete_enquiry/' + id;
                    }, 2000);
                } else {
                }
            });
        }
    </script>

    @stop