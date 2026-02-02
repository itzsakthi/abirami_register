@extends('office.layout.layout')
@section('title', 'Piranthamagal Member List')

@section('content')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.12.13/xlsx.full.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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


    <div class="button-row" style= "text-align: end; margin-right:30px">
        <a style="display: none;" class="btn bg-gradient-success"  href="{{url('pmregisration')}}" ><i class="material-icons opacity-10">person_add</i> PM Member Add</a>
        <button type="button" id="saveExcel" class="btn btn-success" >Export Excel</button>     
    </div>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Piranthamagal Member List</h6>
                    </div>
                </div>
                <div class="p-4">
                    @csrf <!-- Add CSRF token -->
                    <div class="input-group input-group-dynamic mb-4">
                        <label class="form-label">Search here...</label>
                        <input id="search" type="text" class="form-control">
                    </div>
                    <div class="table-responsive" id="table_scroll"style=" text-align:center;width:100%" >
                        <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                            <table class="table align-items-center mb-0" id = "mytable">
                                <thead>
                                    <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">S.No</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Action</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Action</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">PM Id</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Pay</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Name</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Spouse Name</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Whatsapp Number</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Spouse Number</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Family Name</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Address</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Remark</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Reference Of Pulli</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Native</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php $ids = $data->firstItem(); @endphp
                                    @foreach($data as $pmdata)
                                        <tr>
                                            <td>
                                                <p class="text-xs text-secondary mb-0"> {{ $ids }}</p>
                                            </td>
                                            <td> 
                                                <a href="editpmmemeber/{{ $pmdata->id }}" > <i class="material-icons opacity-10">edit</i></a>
                                            </td>
                                            <td id="whatsapp"> 
                                                <a href="#" > <i class="fa fa-whatsapp"></i></a>
                                            </td>
                                            <td> 
                                                <p class="text-xs  font-weight-bold mb-0"> {{ $pmdata->pmid }}</p>
                                            </td>
                                            <td>
                                                @if ($pmdata->is_paid)
                                                    <button class="btn btn-secondary" disabled>
                                                        Paid
                                                    </button>
                                                @else
                                                    <button
                                                        type="button"
                                                        class="btn btn-success pm_pay_main_btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#payModal"
                                                        data-id="{{ $pmdata->pmid }}"
                                                        data-name="{{ $pmdata->name }}">
                                                        Pay
                                                    </button>
                                                @endif
                                            </td>
                                            <td> 
                                                <p class="text-xs  font-weight-bold mb-0"> {{ $pmdata->name }}</p>
                                            </td>
                                            <td> 
                                                <p class="text-xs font-weight-bold mb-0"> {{ $pmdata->spousename }}</p>
                                            </td>        
                                            <td> 
                                                <p class="text-xs font-weight-bold mb-0"> {{ $pmdata->whatsappnumber }}</p>
                                            </td>
                                            <td> 
                                                <p class="text-xs font-weight-bold mb-0"> {{ $pmdata->spousenumber }}</p>
                                            </td>
                                            <td> 
                                                <p class="text-xs font-weight-bold mb-0"> {{ $pmdata->familynickname }}</p>
                                            </td>
                                                <td> <p class="text-xs font-weight-bold mb-0"> {{ $pmdata->address }}</p>
                                            </td>
                                            <td> 
                                                <p class="text-xs font-weight-bold mb-0"> {{ $pmdata->remark }}</p>
                                            </td>
                                            <td> <p class="text-xs font-weight-bold mb-0"> {{ $pmdata->reference }}</p>
                                            </td>
                                            </td>
                                            <td> 
                                                <p class="text-xs font-weight-bold mb-0"> {{ $pmdata->native }}</p>
                                            </td>
                                        </tr>
                                    @php     $ids++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div> 
                    </div>
                </div>
                
                <div class="row" style="margin-bottom:20px">
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
                        <div  style= "margin-right:40px;">
                            {{ $data->appends(['items' => $items])->links() }}
                        </div>
                    </div>
                </div>    
            </div>
        </div>
    </div>

    <div class="modal fade" id="payModal" tabindex="-1" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title">Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
    
                <div class="modal-body">
    
                    <div class="mb-3 ">
                        <label class="form-label">PMID</label>
                        <input type="text" id="modal_pmid" class="form-control p-2" value="" readonly>
                    </div>

                    <div class="mb-3 ">
                        <label class="form-label">PM Name</label>
                        <input type="text" id="modal_pmname" class="form-control p-2" value="" readonly>
                    </div>
    
                    <div class="mb-3">
                        <label class="form-label">Year</label>
                        <input type="text" id="modal_year" class="form-control p-2" value="" readonly>
                    </div>
    
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" id="modal_amount" class="form-control p-2" placeholder="Enter amount">
                    </div>
    
                </div>
    
                <div class="modal-footer">
                    <button type="button" id="pay_btn" class="btn btn-success">Pay</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
    
            </div>
        </div>
    </div>
    <div class="popupreceipt" style="display: none"></div>

    <!-- @include('office.layout.footer') -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js">  </script>

    <script>
        document.getElementById('pagination').onchange = function() {
            var url = window.location.href.split('?')[0]; // Get current URL without query parameters
            var newUrl = url + '?items=' + this.value; // Append the selected items parameter
            window.location.href = newUrl; // Redirect to the new URL
        };
    </script>

    <script>
        $(document).ready(function(){

            // Pay Modal code
            $('#payModal').on('show.bs.modal', function (e) {
                $('#modal_pmid').val($(e.relatedTarget).data('id'));
                $('#modal_pmname').val($(e.relatedTarget).data('name'));
                $('#modal_year').val(new Date().getFullYear());
            });
                
            $('#pay_btn').on('click', function () {
            
                let pmid   = $('#modal_pmid').val();
                let pmname   = $('#modal_pmname').val();
                let year   = $('#modal_year').val();
                let amount = $('#modal_amount').val(); 
            
                $.ajax({
                    url: 'pmpay',  
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        pmname: pmname,
                        pmid: pmid,
                        year: year,
                        amount: amount
                    },
                    success: function (res) {
                        if (res.status === true) {
                            popopen_pm(res.pmdata);                       
                        } else {
                            console.log("something is wrong in backend");
                        }
                        $('#payModal').modal('hide');
                    },
                    error: function (err) {
                        console.log('Payment failed');
                    }
                });
            
            });

            $("#saveExcel").click(function(){
                $.ajax({
                    url: 'pmexport', 
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
                        workbook.SheetNames.push("PMMemberList");
                        workbook.Sheets["PMMemberList"] = worksheet;
                        exportExcelFile(workbook);
                    },
                });
            });
        });

        function exportExcelFile(workbook) {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timestamp = `${year}${month}${day}${hours}${minutes}${seconds}`;
            return XLSX.writeFile(workbook, "PMMemberList_"+timestamp+".xlsx");
        }
    </script>

    <script>
        var $rows = $('#mytable tr');
        $('#search').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
            
            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
    </script>

    <script>
        function popopen_pm(id) {
            console.log(id);
            
            if (id) {
                const popup_container = document.querySelector('.popupreceipt');
                const pop_shadow = popup_container.attachShadow({ mode: 'open' });
                let receiptsHTML = '';
                const array = id;
                const now = (array.created_at).split(' ');
                const padded = String(array.receipt_id).padStart(5, '0');
                receiptsHTML += `
                    <div class="status_show" style="margin-top:-5%">
                        <div class="receipt">
                            <button class="Btn">
                                <a href="{{url('receipt/${array.ref_id}?paytotxt=${array.pay_to_txt}')}}" target="_blank">
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
                                        <div class="receipt_id">ரசீது எண்: PM-${array.receipt_id}-${padded}</div>
                                    </div>
                                    <div class="body">
                                        <p><span class="label_receipt_id">திருமதி/திரு </span> 
                                            <span class="dotted">
                                                <span class="dotted_p">${array.ref_txt}</span>
                                            </span> 
                                            <span class="label_receipt_id">அவர்களிடமிருந்து புள்ளிவரியாக ரூபாய்( </span>
                                            <span class="dotted">
                                                <span class="dotted_p">${array.amount}</span>
                                            </span>
                                            <span class="label_receipt_id">மட்டும் ) நன்றியுடன் பெற்றுக் கொண்டோம்.</span> 
                                        </p>
                                        <p><span class="label_receipt_id">வரி ஆண்டுகள் &nbsp;:</span> 
                                            <span class="dotted">
                                                <span class="dotted_p">${array.pay_to_txt}</span>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="footer">
                                        <div class="amount-box">₹ ${array.amount}
                                            <span style="font-size: 10px;color:rgba(117, 117, 117, 0.808);">/-</span></div>
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
                    close_button.addEventListener('click', function () {
                        popup_container.style.display = 'none';
                        window.location.reload();

                        // window.location.href = `/userprofile/${array.ref_id}`;
                        
                    });
                }
                Swal.hideLoading();
                Swal.close();
                popup_container.style.display = 'block';
            }
            else {
                Swal.fire('oops!', 'Try again.', 'info');
            }
        }
    </script>

@stop
