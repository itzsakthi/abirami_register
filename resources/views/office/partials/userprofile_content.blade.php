

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{asset('css/card.css')}}" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.12.13/xlsx.full.min.js"></script>
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

    .text-xs.font-weight-bold.mb-0 {
        /* Example hover effect */
        padding: 5px;
        /* Padding for spacing */
        display: inline-block;
        /* Ensures the cursor affects the entire area */
    }

    .text-xs.font-weight-bold.mb-0:hover {
        background-color: #f0f0f0;
        /* Light gray background */
        border-radius: 4px;
        /* Rounded corners */
        cursor: pointer;
        /* Change cursor to pointer (hand) on hover */
    }

    .scroll-btn {
        position: fixed;
        bottom: 12%;
        /* use bottom instead of top for better mobile placement */
        right: 55px;
        /* default distance from the right */
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
        right: 15px;
        /* placed to the far right */
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
        background-color: #bfbfbf;
        /* Mild color for disabled state */
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
                table.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            } else {
                table.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
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
<style>
    .grid-container {
        display: flex;
        flex-direction: column;
        width: 100%;
        margin: 0.1rem 0.1rem;
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        border-radius: 8px;
        overflow: hidden;
        /* box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); */
    }

    .grid-row {
        display: grid;
        grid-template-columns: 2fr 2fr 1fr;
        padding: 1rem;
        align-items: center;
    }

    /* .grid-row:nth-child(odd) {
            background-color:rgb(255, 255, 255);
        }

        .grid-row:nth-child(even) {
            background-color:rgb(236, 236, 236);
        } */

    .grid-item {
        padding: 0.5rem 1rem;
        font-size: 14px;
        text-align: left;
    }

    .grid-item.bold {
        font-weight: bold;
    }

    .grid-item.col-span-2 {
        grid-column: span 2;
    }

    .plus-icon {
        text-align: center;
    }

    .fa {
        color: black;
    }

    .grid-container {
        /* box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); */
    }

</style>
<button id="scrollLeftBtn" style="display:none;" class="scroll-btn disabled" disabled>⬅</button>
<button id="scrollRightBtn" style="display:none;" class="scroll-btn">➡</button>
<div class="button-row" style="text-align: end;">
    <button type="button" id="saveAsPdf" class="btn btn-success pdf" style=" margin-right:3px;">Summary Report</button>
    <button type="button" id="saveAsExcel" class="btn btn-success excel" style=" margin-right:30px;">Export
        Excel</button>
    <!-- <button type="button" id="saveExcel2" class="btn btn-success" >Export All</button>            -->
</div>
<div class="container-fluid py-4">

    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">User profile</h6>
                    </div>
                </div>
                <div class="p-4">
                    <!-- From Uiverse.io by EmmaxPlay -->
                    <div class="base-card">
                        <div class="tools">
                            <div class="circle-indicator">
                                <span class="red base-box"></span>
                            </div>
                            <div class="circle-indicator">
                                <span class="yellow base-box"></span>
                            </div>
                            <div class="circle-indicator">
                                <span class="green base-box"></span>
                            </div>
                        </div>
                        <div class="grid-container">
                            <div id="profileids">
                                <div class="grid-row1">
                                    <div class="grid-flex">
                                        <div class="grid-item bold " style="text-align:left">
                                            <div class="detailed-card">
                                                <!-- <img src="{{ asset('images/avatar.jpg') }}" style="height: 128px; width: 128px;" alt="Avatar Image"> -->
                                                <img src="{{ asset('images/avatar2.jpg') }}"
                                                    style="height: 128px; width: 128px;" alt="Avatar Image">
                                                <div class="detailed-content">
                                                    <span class="detailed-title">title</span>
                                                    <p class="description">
                                                        dummy.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid-item bold" style="text-align:left">
                                        <div class="grid-flex">
                                            <div class="detailed-card1">
                                                <div class="detailed-card-content">
                                                    <div class="detailed-card-grid">
                                                        <div>
                                                            <p>
                                                                <h2 style="font-size: 20px;"
                                                                    class="detailed-card-title">
                                                                    Overall Outstanding.
                                                                </h2>
                                                                pullivari & yellam
                                                            </p>
                                                        </div>
                                                        <div class="detailed-card-price">
                                                            <p>
                                                                <span class="card-price-amount pulliamt">$25</span>
                                                                <span class="card-price-period">/-</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="detailed-card-footer">
                                                    <a href="#" class="detailed-card-button show_py">
                                                        Get Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid-item bold" style="text-align:left">
                                        <div class="grid-flex">
                                            <div class="detailed-card1">
                                                <div class="detailed-card-content">
                                                    <div class="detailed-card-grid">
                                                        <div>
                                                            <p>
                                                                <h2 style="font-size: 20px;"
                                                                    class="detailed-card-title">
                                                                    Referral Outstanding.
                                                                </h2>
                                                                Yellam
                                                            </p>
                                                        </div>
                                                        <div class="detailed-card-price">
                                                            <p>
                                                                <span class="card-price-amount yellamamt">$25</span>
                                                                <span class="card-price-period">/-</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="detailed-card-footer">
                                                    <a href="#" class="detailed-card-button show_y">
                                                        Get Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="grid-row" style="background-color: rgb(236, 236, 236);">
                                <div class="grid-item col-span-2" style="font-size: 36px; font-weight: bold;">PULLI VARI
                                </div>
                                <div class="grid-item plus-icon"><button type="button"
                                        class="btn btn-success vari">SHOW</button></div>
                            </div>

                            <!-- Pullivari Table-->
                            <div class="pullivari" style="display:none;">
                                <div class="bg-gradient-success shadow-primary relative border-radius-lg pt-4 pb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 style="font-size: 12px;" class="text-white text-capitalize ps-3">PULLI VARI
                                        </h6>
                                        <div class="d-flex justify_content-center align-item-baseline">
                                            <div class="pending_container">
                                                <div class="pending_content">
                                                    <div class="content__container">
                                                        <span>Outstanding</span>
                                                        <ul class="content__container__list">
                                                            <li class="content__container__list__item balance">₹</li>
                                                            <li class="content__container__list__item balance">ரூ</li>
                                                            <li class="content__container__list__item balance">INR</li>
                                                            <li class="content__container__list__item balance">₹</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="creativebt-submit" data-tooltip="Price:-$0" class="creativebt"
                                                style='margin-right:20px;'>
                                                <div class="creativebt-wrapper">
                                                    <div class="creativebt-text">Pay</div>
                                                    <span class="creativebt-icon">
                                                        <svg viewBox="0 0 24 24" class="bi bi-cart2" height="16"
                                                            width="16">
                                                            <path
                                                                d="M21,18V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H19A2,2 0 0,1 21,5V6H12C10.89,6 10,6.9 10,8V16A2,2 0 0,0 12,18M12,16H22V8H12M16,13.5A1.5,1.5 0 0,1 14.5,12A1.5,1.5 0 0,1 16,10.5A1.5,1.5 0 0,1 17.5,12A1.5,1.5 0 0,1 16,13.5Z"
                                                                fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group input-group-dynamic mt-3 mb-4">
                                    <div class="input-group input-group-outline col-6">
                                        <label class="form-label">Search by year</label>
                                        <input id="search1" type="text" class="form-control s">
                                    </div>
                                    <div class="table-responsive" style="width:100%; max-height: 400px; overflow-y: auto;" id="table_scroll">
                                        <table class="table align-items-center mb-0" style="width:100%;text-align:center;" id="mytable">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7"> SNO</th>
                                                    <th class="">
                                                        <div class="input-group input-group-static">
                                                            <label class="form-check-label">
                                                                <a href="{{ route('income_entry', ['inc_type' => 1]) }}" id="pullivaripay" style="cursor:pointer;">
                                                                    <p class="text-xs font-weight-bold mb-0">
                                                                        <i class="text-uppercase text-secondary text-xs font-weight-bolder fa fa-rupee ">pay</i>
                                                                    </p>
                                                                </a>
                                                            </label>
                                                        </div>
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        year</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Pulli ID</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        NAME</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        AMOUNT</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Print</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @php
                                                $sno=1;$pending_pulliamt_yellamamt=0;$pending_pullivari=0;@endphp
                                                @foreach($finalData as $item)
                                                <tr>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"> {{ $sno }}</p>
                                                    </td>
                                             <td style="text-align:center; vertical-align:middle;">
                                                 @if($item['status']=='not paid')
                                                     @php
                                                         $pending_pulliamt_yellamamt += $item['amt'];
                                                         $pending_pullivari += $item['amt'];
                                                     @endphp
                                             
                                                     <div class="input-group input-group-static mb-2">
                                                         <label class="form-check-label">
                                                             <a href="{{ route('income_entry', ['inc_type' => 1, 'pulliid' => $item['pulliid']]) }}"
                                                                class="yellam_checkbox_link"
                                                                id="pulli_checkAll"
                                                                target="_blank">
                                                                 <p class="text-xs font-weight-bold mb-0"
                                                                    style="margin-left: 85%;">
                                                                     <i class="fa fa-rupee text-warning"> pay</i>
                                                                 </p>
                                                             </a>
                                                         </label>
                                                     </div>
                                                 @else
                                                     <!-- placeholder ONLY to keep DOM stable -->
                                                     <span id="pulli_checkAll" style="display:none;"></span>
                                             
                                                     <p class="text-xs checkboxfont-weight-bold"
                                                        style="margin-left:-45%;">
                                                         <i class="fa fa-check-circle text-success">
                                                             {{ $item['status'] }}
                                                         </i>
                                                     </p>
                                                 @endif
                                             </td>
                                                 
                                             
                                                 
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            {{ $item['year'] }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            {{ $item['pulliid'] }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            {{ $item['name'] }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"> {{ $item['amt'] }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <a href="{{route('receipt', ['id' => $item['pulliid'],'paytotxt'=>$item['year']])}}"
                                                            target="_blank">
                                                            <i class="fa fa fa-print text-success"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @php $sno++;@endphp

                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="grid-row" style="background-color: rgb(255, 255, 255);">
                                <div class="grid-item col-span-2" style="font-size: 36px; font-weight: bold;">YELLAM
                                </div>
                                <div class="grid-item plus-icon"><button type="button" class="btn btn-success yel">SHOW</button></div>
                            </div>

                            <!-- Yellam Table -->
                            <div class="Yellam" style="display:none">
                                <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 style="font-size: 12px;" class="text-white text-capitalize ps-3">YELLAM
                                        </h6>
                                        <div class="d-flex justify_content-center align-item-baseline gap-1">
                                            <div class="pending_container">
                                                <div class="pending_content">
                                                    <div class="content__container">
                                                        <span>Outstanding</span>
                                                        <ul class="content__container__list">
                                                            <li class="content__container__list__item ybalance">₹
                                                            </li>
                                                            <li class="content__container__list__item ybalance">ரூ
                                                            </li>
                                                            <li class="content__container__list__item ybalance">INR
                                                            </li>
                                                            <li class="content__container__list__item ybalance">₹
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="yellam-submit" data-tooltip="Price:-$0" class="creativebt" style='margin-right:20px;'>
                                                <div class="creativebt-wrapper">
                                                    <div class="creativebt-text">Pay</div>
                                                    <span class="creativebt-icon">
                                                        <svg viewBox="0 0 24 24" class="bi bi-cart2" height="16"
                                                            width="16">
                                                            <path
                                                                d="M21,18V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H19A2,2 0 0,1 21,5V6H12C10.89,6 10,6.9 10,8V16A2,2 0 0,0 12,18M12,16H22V8H12M16,13.5A1.5,1.5 0 0,1 14.5,12A1.5,1.5 0 0,1 16,10.5A1.5,1.5 0 0,1 17.5,12A1.5,1.5 0 0,1 16,13.5Z"
                                                                fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group input-group-dynamic mt-2 mb-4">
                                    <div class="input-group input-group-outline col-6">

                                        <label class="form-label">Search by product</label>
                                        <input id="search3" type="text" class="form-control s">
                                    </div>
                                    <div class="table-responsive"
                                        style="width:100%; max-height: 400px; overflow-y: auto;" id="table_scroll">
                                        <table class="table align-items-center mb-0"
                                            style="width:100%;text-align:center" id="mytable2">
                                            <thead>
                                                <tr>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        SNO</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Pulli ID</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Yellam product</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        NAME</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        AMOUNT</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Pending</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Paid</th>
                                                    <th class="">
                                                        <div class="input-group input-group-static">
                                                                            <label class="form-check-label"> 
                                                                                <input type="checkbox" class="yellam-checkbox form-check-input me-2" style="border: 1px solid pink;" 
                                                                                    id="yellam-checkAll"   
                                                                                    checked />
                                                                                    <p class="text-xs font-weight-bold mb-0" >
                                                                                        <i class="text-uppercase text-secondary text-xs font-weight-bolder fa fa-rupee ">pay</i>    
                                                                                    </p>    
                                                                            </label>
                                                                        </div>
                                                    </th>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Print</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <input type="hidden" value="" id="yelam_value" name="yelam_value">
                                                @php $sno=1; $referral_yellamamt=0; @endphp
                                                @foreach($data1 as $room)
                                                @php $y_amt=$room->value - $room->paidtotal ; @endphp
                                                @if(!$room->nameguest)
                                                @php $pending_pulliamt_yellamamt+= ($y_amt); @endphp

                                                <tr>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"> {{ $sno	 }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            {{ $room->pulliid }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            {{ $room->things }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"> {{ $room->name }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"> {{ $room->value }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"> @php
                                                            echo($room->value - $room->paidtotal) @endphp</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            {{ $room->paidtotal }}</p>
                                                    </td>
                                                    @if(($y_amt !=0) || $room->payment!='paid')

                                                    <td>
                                                        <div class="input-group input-group-static mb-2" style="margin-right: -195%;">
                                                            <label class="form-check-label">
                                                                <a href="{{ route('income_entry', ['inc_type' => 4, 'pulliid' => $item['pulliid'] ]) }}" class="yellam_checkbox_link"
                                                                    data-pulliid="{{$room->pulliid}}"
                                                                    data-product="{{$room->things}}"
                                                                    data-amt="{{$y_amt}}"
                                                                    data-id="{{$room->id}}">                                             
                                                                    <p class="text-xs font-weight-bold mb-0" style="margin-right: -195%;">
                                                                        <i class="fa fa-rupee  text-warning">pay</i>
                                                                    </p>
                                                                </a>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td>
                                                        <p class="text-xs checkboxfont-weight-bold "
                                                            style="margin-left:-45% ">
                                                            <i class="fa fa-check-circle text-success">paid</i>
                                                        </p>
                                                    </td>
                                                    @endif
                                                    <td>
                                                        <a href="{{route('receipt', ['id' => $room->id])}}"
                                                            target="_blank">
                                                            <i class="fa fa fa-print text-success"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @php $sno++;@endphp
                                                @else
                                                @php $referral_yellamamt+= ($y_amt); @endphp
                                                @endif

                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="Yellam_external" style="display:none">
                                <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 style="font-size: 12px;" class="text-white text-capitalize ps-3">
                                            Referrals YELLAM</h6>
                                        <div class="d-flex justify_content-center align-item-baseline gap-1">
                                            <div class="pending_container">
                                                <div class="pending_content">
                                                    <div class="content__container">
                                                        <span>Referral O/S</span>
                                                        <ul class="content__container__list">
                                                            <li class="content__container__list__item rybalance">₹
                                                            </li>
                                                            <li class="content__container__list__item rybalance">ரூ
                                                            </li>
                                                            <li class="content__container__list__item rybalance">INR
                                                            </li>
                                                            <li class="content__container__list__item rybalance">₹
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="ref_yellam-submit" data-tooltip="Price:-$0" class="creativebt"
                                                style='margin-right:20px;'>
                                                <div class="creativebt-wrapper">
                                                    <div class="creativebt-text">Pay</div>
                                                    <span class="creativebt-icon">
                                                        <svg viewBox="0 0 24 24" class="bi bi-cart2" height="16"
                                                            width="16">
                                                            <path
                                                                d="M21,18V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H19A2,2 0 0,1 21,5V6H12C10.89,6 10,6.9 10,8V16A2,2 0 0,0 12,18M12,16H22V8H12M16,13.5A1.5,1.5 0 0,1 14.5,12A1.5,1.5 0 0,1 16,10.5A1.5,1.5 0 0,1 17.5,12A1.5,1.5 0 0,1 16,13.5Z"
                                                                fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group input-group-dynamic mt-2 mb-4">
                                    <div class="input-group input-group-outline col-6">

                                        <label class="form-label">Search by product</label>
                                        <input id="search4" type="text" class="form-control s">
                                    </div>
                                    <div class="table-responsive"
                                        style="width:100%; max-height: 400px; overflow-y: auto;" id="table_scroll">
                                        <table class="table align-items-center mb-0"
                                            style="width:100%;text-align:center" id="mytable3">
                                            <thead>
                                                <tr>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        SNO</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Pulli ID</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Yellam product</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        NAME</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        AMOUNT</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Pending</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Paid</th>
                                                    <th class="">
                                                        <div class="input-group input-group-static">
                                                            <label class="form-check-label">
                                                                <input type="checkbox"
                                                                    class="ref_yellam-checkbox form-check-input me-2"
                                                                    style="border: 1px solid pink;"
                                                                    id="ref_yellam-checkAll" checked />
                                                                <p class="text-xs font-weight-bold mb-0">
                                                                    <i
                                                                        class="text-uppercase text-secondary text-xs font-weight-bolder fa fa-rupee ">pay</i>
                                                                </p>
                                                            </label>
                                                        </div>
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Print</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $sno=1; @endphp
                                                @foreach($data1 as $room)
                                                @if($room->nameguest)
                                                @php $y_amt=$room->value - $room->paidtotal ; @endphp
                                                <tr>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"> {{ $sno	 }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            {{ $room->pulliid }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            {{ $room->things }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"> {{ $room->name }}
                                                            || {{$room->nameguest}} <i
                                                                class="text-success">(guest)</i></p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"> {{ $room->value }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"> @php
                                                            echo($room->value - $room->paidtotal) @endphp</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            {{ $room->paidtotal }}</p>
                                                    </td>
                                                    @if((($room->value -$room->paidtotal) !=0) ||
                                                    $room->payment!='paid')

                                                    <td>

                                                        <div class="input-group input-group-static mb-2">
                                                            <label class="form-check-label">
                                                                <input type="checkbox"
                                                                    class="ref_yellam-amt-checkbox form-check-input me-2"
                                                                    style="margin-left:25%;border: 1px solid pink;"
                                                                    name="selected_yellam_amts[{{$room->id}}]"
                                                                    value="{{$y_amt}}" checked />
                                                                <p class="text-xs font-weight-bold mb-0">
                                                                    <i class="fa fa-rupee  text-warning">pay</i>
                                                                </p>
                                                            </label>
                                                        </div>

                                                    </td>
                                                    @else
                                                    <td>
                                                        <p class="text-xs checkboxfont-weight-bold "
                                                            style="margin-left:-45% ">
                                                            <i class="fa fa-check-circle text-success">paid</i>
                                                        </p>
                                                    </td>
                                                    @endif
                                                    <td>
                                                        <a href="{{route('receipt', ['id' => $room->id])}}"
                                                            target="_blank">
                                                            <i class="fa fa fa-print text-success"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @php $sno++;@endphp

                                                @endif

                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid-row" style="background-color: rgb(236, 236, 236);">
                                <div class="grid-item col-span-2" style="font-size: 36px; font-weight: bold;">DONATION
                                </div>
                                <div class="grid-item plus-icon"><button type="button"
                                        class="btn btn-success don">SHOW</button></div>
                            </div>


                            {{-- donation list --}}
                            <div class="donation" style="display:none">
                                <div class="bg-gradient-success shadow-primary border-radius-lg pt-3 pb-3 px-3
                                        d-flex align-items-center justify-content-between">
                                
                                    <h6 style="font-size:12px;" class="text-white text-capitalize m-0">
                                        Donation
                                    </h6>
                                
                                    <button type="button"
                                        class="btn btn-secondary btn-sm"
                                        onclick="window.open('/income_entry?inc_type=2&pulliid={{ $iddata->pulliid }}', '_blank')">
                                        Donate
                                    </button>
                                
                                </div>
                               
                                <div class="input-group input-group-dynamic mt-3 mb-4">
                                    <div class="input-group input-group-outline col-6">
                                        <label class="form-label">Search by type</label>
                                        <input id="search2" type="text" class="form-control s">
                                    </div>
                                    <div class="table-responsive"
                                        style="width:100%; max-height: 400px; overflow-y: auto;" id="table_scroll">
                                        <table class="table align-items-center mb-0"
                                            style="width:100%;text-align:center" id="mytable1">
                                            <thead>
                                                <tr>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        SNO</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Pulli ID</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Donation Name</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Donation Type</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        AMOUNT</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Print</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data as $room)
                                                <tr>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"> {{ $sno}}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"> {{ $room->ref_id }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"> {{ $room->ref_txt }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"> {{ $room->pay_mode }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"> {{ $room->amount }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <a href="{{route('receipt', ['id' => $room->id,'donation'=>'{success}'])}}"
                                                            target="_blank">
                                                            <i class="fa fa fa-print text-success"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @php $sno++;@endphp

                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="grid-row">
                                        <div class="grid-item col-span-2">ACCOUNT</div>
                                        <div class="grid-item plus-icon"><button type="button" class="btn btn-success acc" >SHOW</button></div>
                                    <
                                    /div> -->
                        </div>
                    </div>
                </div>





                <div class="pullivari_page hidden" style="display:none">
                    {{--   {!! $iddata1->links() !!} --}}
                </div>
                <div class="donation_page hidden" style="display:none">
                    {{-- {!! $data->links() !!} --}}
                </div>
                <div class="yellam_page hidden" style="display:none">
                    {{-- {!! $data1->links() !!} --}}
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<section class="dots-container" id="dots-container" style="display: none">
    <div class="status_loader">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
    </div>
</section>

<div class="popupreceipt" style="display: none">

</div>
<script>
</script>
<!-- @include('office.layout.footer') -->

<script>
        document.addEventListener('DOMContentLoaded', function() {
            // const container = document.getElementById('payopen');
            // const shadow = container.attachShadow({mode: 'open'});
            
            // shadow.innerHTML = `
            //         <link rel="stylesheet" href="{{asset('css/paycard.css')}}">
            //         ${container.innerHTML}
            //     `;
            const container1 = document.getElementById('profileids');
            const shadow1 = container1.attachShadow({mode: 'open'});
            shadow1.innerHTML = `
                <link rel="stylesheet" href="{{asset('css/paycard.css')}}">
                ${container1.innerHTML}
            `; 

            var tables = [
                { selector: '#mytable', colspan: 7 },
                { selector: '#mytable1', colspan: 5 },
                { selector: '#mytable2', colspan: 9 },
                { selector: '#mytable3', colspan: 9 }
            ];

            tables.forEach(function(t) {
                var $tbody = $(t.selector + ' tbody');
                if ($tbody.children('tr').length === 0) {
                    $tbody.append('<tr><td colspan="' + t.colspan + '">No List found</td></tr>');
                }
            });
            
        });


        $(document).ready(function() {
            const container1 = document.getElementById('profileids');
            console.log(container1);
            console.log(container1.shadowRoot);
            

            const shadow1 = container1.shadowRoot ;
            if (!container1 || !container1.shadowRoot) {
                console.error('profileids or shadowRoot not found');
                return;
            }
            // console.log("shadow 1: ",shadow1);
            

            //status card user details
            const text = shadow1.querySelector('.detailed-title');
            // console.log("text: ", text);
            // console.log("text.innerhtml: ", text.innerHTML);

            text.innerHTML={!! json_encode($iddata->name) !!};
            
            const details = shadow1.querySelector('.description');
            const ss={!! json_encode($iddata->pulliid) !!}+`<br>`+{!! json_encode($iddata->whatsappnumber) !!}+`<br>`+{!! json_encode($iddata->address) !!};
            details.innerHTML=ss;

            // status card amount
            var pendingAmount = @json($pending_pulliamt_yellamamt);
            var pAmount=@json($pending_pullivari);
            const pulliamt = shadow1.querySelector('.pulliamt');
            pulliamt.innerHTML=( '₹' + (new Intl.NumberFormat("en-IN").format(pendingAmount)));

            var ryAmount = @json($referral_yellamamt);
            const yellamamt = shadow1.querySelector('.yellamamt');
            yellamamt.innerHTML=( '₹' + (new Intl.NumberFormat("en-IN").format(ryAmount)));
            
            var balances = document.querySelectorAll(".balance");
            balances.forEach(el => {
                el.innerHTML += "/-"+(new Intl.NumberFormat("en-IN").format(pAmount));
            });
            var balances1 = document.querySelectorAll(".ybalance");
            balances1.forEach(el => {
                el.innerHTML += "/-"+(new Intl.NumberFormat("en-IN").format((pendingAmount-pAmount)));
            });

            var balances2 = document.querySelectorAll(".rybalance");
            balances2.forEach(el => {
                el.innerHTML += "/-"+(new Intl.NumberFormat("en-IN").format(ryAmount));
            });


            // console.log(pendingAmount);
            // console.log(pAmount);
            // console.log(ryAmount);

            $(".pullivari").hide();
            $(".donation").hide();
            $(".Yellam").hide();
            $(".donation_page").hide();
            $(".pullivari_page").hide();
            $(".yellam_page").hide();
            const urlParams = new URLSearchParams(window.location.search);
            const type = urlParams.get('type');
            if (type){
                $(".Yellam").show();
                $(".Yellam_external").show();
            }
            //pulli bt value change
            const checkAll = document.getElementById('pulli_checkAll');
            console.log(checkAll);
            
            const checkboxes = document.querySelectorAll('.amt-checkbox');

            checkAll.addEventListener('change', function() {
                for (let checkbox of checkboxes) {
                    checkbox.checked = this.checked;
                }
                updateTotal();
            });
            for (let checkbox of checkboxes) {
                checkbox.addEventListener('change', function() {
                    if (!this.checked) {
                        checkAll.checked = false;
                    } else {
                        if (document.querySelectorAll('.amt-checkbox:checked').length === checkboxes.length) {
                            checkAll.checked = true;
                        }
                    }
                    updateTotal();
                });
            }

            function updateTotal() {
                let total = 0;
                $('.amt-checkbox:checked').each(function() {
                    total +=  parseFloat($(this).val().trim()) || 0;
                });
                $('#vari_value').val(total);
                show_total=(new Intl.NumberFormat("en-IN").format(total));
                if (total !== 0) {
                    $('#creativebt-submit').attr('data-tooltip', 'ரூ-' + show_total);
                } else {
                    $('#creativebt-submit').attr('data-tooltip', 'ரூ-' + show_total);
                }
                // console.log(total);
            }

            
            updateTotal();

            //yellam bt change
            const checkAll1 = document.getElementById('yellam-checkAll');
            const checkboxes1 = document.querySelectorAll('.yellam-amt-checkbox');

            checkAll1.addEventListener('change', function() {
                for (let checkbox of checkboxes1) {
                    checkbox.checked = this.checked;
                }
                updateyellamTotal();
            });
            for (let checkbox of checkboxes1) {
                checkbox.addEventListener('change', function() {
                    if (!this.checked) {
                        checkAll1.checked = false;
                    } else {
                        if (document.querySelectorAll('.yellam-amt-checkbox:checked').length === checkboxes1.length) {
                            checkAll1.checked = true;
                        }
                    }
                    updateyellamTotal();
                });
            }

            //ref yellam bt change
            const checkAll2 = document.getElementById('ref_yellam-checkAll');
            const checkboxes2 = document.querySelectorAll('.ref_yellam-amt-checkbox');

            checkAll2.addEventListener('change', function() {
                for (let checkbox of checkboxes2) {
                    checkbox.checked = this.checked;
                }
                updateyellamTotal();
            });
            for (let checkbox of checkboxes2) {
                checkbox.addEventListener('change', function() {
                    if (!this.checked) {
                        checkAll2.checked = false;
                    } else {
                        if (document.querySelectorAll('.ref_yellam-amt-checkbox:checked').length === checkboxes2.length) {
                            checkAll2.checked = true;
                        }
                    }
                    updateyellamTotal();
                });
            }
            function updateyellamTotal() {
                let total = 0;
                $('.yellam-amt-checkbox:checked').each(function() {
                    total +=  parseFloat($(this).val().trim()) || 0;
                });
                $('.ref_yellam-amt-checkbox:checked').each(function() {
                    total +=  parseFloat($(this).val().trim()) || 0;
                });
                $('#yelam_value').val(total);

                show_total=(new Intl.NumberFormat("en-IN").format(total));

                $('#yellam-submit').attr('data-tooltip', 'ரூ-' + show_total);
                $('#ref_yellam-submit').attr('data-tooltip', 'ரூ-' + show_total);
                // console.log(total);
            }

            
            updateyellamTotal(); 

            //ref yellam submit
            document.getElementById('ref_yellam-submit').addEventListener('click', function(event) {
                event.preventDefault();
                yel_payout();
            });

            //yellam submit
            document.getElementById('yellam-submit').addEventListener('click', function(event) {
                event.preventDefault();
                yel_payout();
            });

            function yel_payout(){
                const val = document.getElementById('yelam_value').value;

                if (val === '0') {
                    console.log('Skipping handler for value:', val);
                    return; 
                }
                Swal.fire({
                    title: "Submit",
                    text: "Are you sure?",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#00aaff",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Submit it!"
    
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('yellampay');
                        const formData = new FormData(form); 

                        $('.dots-container').show();

                        fetch(`{{ route('savepayment')}}`, {
                            method: 'POST',
                            
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            // console.log(data);
                            // console.log((JSON.stringify(data.selectedAmts)));
                            const receiptBaseUrl = "{{ url('popupreceipt/00') }}";
                            const url = `${receiptBaseUrl}?ys=${encodeURIComponent(JSON.stringify(data.selectedAmts))}`;

                            if (data.status === true) {
                                fetch(url, {
                                    method: 'get'
                                })
                                .then(response => response.json())
                                .then(data => {
                                    console.log(data);
                                    if (data.status === true) {
                                        const popup_container = document.querySelector('.popupreceipt');
                                        const pop_shadow = popup_container.attachShadow({mode: 'open'});
                                        const array = data.selectedAmts;
                                        let receiptsHTML = '';
                                        array.forEach(el => {
                                            const now=(el.totals[0].created_at).split(' ');
                                            const padded = String(el.yelamporul).padStart(5, '0');
                                            receiptsHTML += `
                                                <div class="status_show" style="margin-top:-5%">
                                                    <div class="receipt">
                                                        <button class="Btn">
                                                            <a href="{{url('receipt/${el.id}')}}" target="_blank">
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
                                                                    <div class="receipt_id">ரசீது எண்: YELAM-${el.totals[0].receipt_id}-${padded}</div>
                                                                </div>
                                                                <div class="body">
                                                                    <p><span class="label_receipt_id">திருமதி/திரு </span> 
                                                                        <span class="dotted">
                                                                            <span class="dotted_p">${el.totals[0].ref_txt}</span>
                                                                        </span> 
                                                                        <span class="label_receipt_id">அவர்களிடமிருந்து ரூபாய்( </span>
                                                                        <span class="dotted">
                                                                            <span class="dotted_p">${el.totals[0].amount}</span>
                                                                        </span>
                                                                        <span class="label_receipt_id">மட்டும் ) ஏலத்தொகை நன்றியுடன் பெற்றுக் கொண்டோம்.</span> 
                                                                    </p>
                                                                    <p><span class="label_receipt_id">பொருள்&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</span> 
                                                                        <span class="dotted">
                                                                            <span class="dotted_p">${el.things}</span>
                                                                        </span>
                                                                    </p>
                                                                </div>
                                                                <div class="footer">
                                                                    <div class="amount-box">₹ ${el.totals[0].amount}
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
                                            $('.dots-container').hide();
                                            const close_button = pop_shadow.querySelector('.close_button');
                                            if (close_button) {
                                                close_button.addEventListener('click', function() {
                                                    popup_container.style.display = 'none';
                                                    window.location.reload();
                                                });
                                            }
                                            // document.querySelector('.popupreceipt').innerHTML = receiptsHTML;
                                        });
                                            swal.close();
                                            popup_container.style.display = 'block';
                                        // Swal.fire('Success!', data.message, 'success').then(() => {
                                            
                                        // });
                                    }
                                    
                                })

                            } 
                            else{
                                $('.dots-container').hide();
                                Swal.fire('oops!', 'Try again.', 'info');
                            }
                        })
                        .catch(error => {
                            $('.dots-container').hide();
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        });
                    }
                })
                .catch(error => {
                    $('.dots-container').hide();
                    Swal.fire('Error!', 'Something went wrong.', 'error');
                });

            }
            document.getElementById('creativebt-submit').addEventListener('click', function(event) {
                event.preventDefault(); 
                const val = document.getElementById('vari_value').value;
  
                if (val === '0') {
                    console.log('Skipping handler for value:', val);
                    return; 
                }

                const form = document.getElementById('pullivaripay');
                const formData = new FormData(form);
                fetch(`{{ route('income_validate')}}`, {
                    method: 'POST',
                    
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === true) {
                        Swal.fire({
                            title: "Submit",
                            text: "Are you sure?",
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
                                fetch(`{{ url('income_store')}}`, {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(data => {
                                    // if (data.status === true) {
                                    //     Swal.fire('Success!', data.message, 'success').then(() => {
                        
                                    //         window.location.reload();
                                    //     });
                                    // }
                                    if (data.status === true) {
                                        const popup_container = document.querySelector('.popupreceipt');
                                        const pop_shadow = popup_container.attachShadow({mode: 'open'});
                                        const array = data.pulli;
                                        let receiptsHTML = '';
                                        
                                            const now=(array.created_at).split(' ');
                                            const padded= String(array.receipt_id).padStart(5, '0');
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
                                                                    <div class="receipt_id">ரசீது எண்: PVNO-${array.receipt_id}-${padded}</div>
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
                                                close_button.addEventListener('click', function() {
                                                    popup_container.style.display = 'none';
                                                    window.location.reload();
                                                });
                                            }
                                            // document.querySelector('.popupreceipt').innerHTML = receiptsHTML;
                                            Swal.hideLoading();
                                            swal.close();
                                            popup_container.style.display = 'block';
                                        // Swal.fire('Success!', data.message, 'success').then(() => {
                                            
                                        // });
                                    }
                                    else if (data.status === 'payyelam') {
                                        Swal.fire('INFO!', data.message, 'info')
                                    }else {
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

            let targetElement = document.getElementById('pullivaripay');
            let targetElement1 = document.querySelector('.Yellam_external');
            const show_py = shadow1.querySelector('.show_py');
            const show_y = shadow1.querySelector('.show_y');
            if (show_py) {
                show_py.addEventListener('click', function() {
                    $(".pullivari").show();
                    $(".Yellam").show();
                    $(".Yellam_external").show();
                    $('.vari').text("Hide");
                    $('.yel').text("Hide");
                    requestAnimationFrame(() => {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'end'
                        });
                    });
                });
            }
            if (show_y) {
                show_y.addEventListener('click', function() {
                    $(".Yellam").show();
                    $(".Yellam_external").show();
                    $('.yel').text("Hide");
                    requestAnimationFrame(() => {
                        targetElement1.scrollIntoView({
                            behavior: 'smooth',
                            block: 'end'
                        });
                    });
                });
            }
            $('.vari').click(function() {
                if ($('.pullivari').is(':visible')) {

                    $(".pullivari").hide();
                    $(".pullivari_page").hide();
                    $(this).text("Show");

                } else {

                    $(".pullivari").show();
                    $(".pullivari_page").show();
                    $(this).text("Hide");
                }
            });

            $('.don').click(function() {
                if ($('.donation').is(':visible')) {

                    $(".donation").hide();
                    $(".donation_page").hide();
                    $(this).text("Show");

                } else {

                    $(".donation").show();
                    $(".donation_page").show();
                    $(this).text("Hide");
                }
            });

            $('.yel').click(function() {
                if ($('.Yellam').is(':visible')) {

                    $(".Yellam").hide();
                    $(".Yellam_external").hide();
                    $(".yellam_page").hide();
                    $(this).text("Show");

                } else {
                    $(".Yellam_external").show();
                    $(".Yellam").show();
                    $(".yellam_page").show();
                    $(this).text("Hide");
                }
            });
    
            $("#saveAsExcel").click(function(){
                var workbook = XLSX.utils.book_new();
                var columnWidths = [
                    {wch: 10},
                    {wch: 60}, 
                    {wch: 40}, 
                ];
                var rowHeights = [
                    {hpx: 30}, 
                    {hpx: 30}, 
                ];

                //pullivari table
                var table = document.getElementById("mytable");
                var worksheet = XLSX.utils.table_to_sheet(table);
                worksheet['!cols'] = columnWidths;
                worksheet['!rows'] = rowHeights;
                workbook.SheetNames.push("Pullivari");
                workbook.Sheets["Pullivari"] = worksheet;

                //donation table
                var table1 = document.getElementById("mytable1");
                var worksheet1 = XLSX.utils.table_to_sheet(table1);
                worksheet1['!cols'] = columnWidths;
                worksheet1['!rows'] = rowHeights;
                workbook.SheetNames.push("Donation");
                workbook.Sheets["Donation"] = worksheet1;

                //yellam table
                var table2 = document.getElementById("mytable2");
                var worksheet2 = XLSX.utils.table_to_sheet(table2);
                worksheet2['!cols'] = columnWidths;
                worksheet2['!rows'] = rowHeights;
                workbook.SheetNames.push("Yellam");
                workbook.Sheets["Yellam"] = worksheet2;

                //ref yellam table
                var table3 = document.getElementById("mytable3");
                var worksheet3 = XLSX.utils.table_to_sheet(table2);
                worksheet3['!cols'] = columnWidths;
                worksheet3['!rows'] = rowHeights;
                workbook.SheetNames.push("ref_Yellam");
                workbook.Sheets["ref_Yellam"] = worksheet2;

                exportExcelFile(workbook);
            });
            // var $rows = $('#mytable tbody tr');
            // $('#search1').keyup(function() {
            //     var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
                
            //     $rows.show().filter(function() {
            //         var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            //         return !~text.indexOf(val);
            //     }).hide();
            // });

            // var $row = $('#mytable1 tbody tr');
            // $('#search2').keyup(function() {
            //     var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
                
            //     $row.show().filter(function() {
            //         var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            //         return !~text.indexOf(val);
            //     }).hide();
            // });

            // var $row2 = $('#mytable2 tbody tr');
            // $('#search3').keyup(function() {
            //     var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
                
            //     $row2.show().filter(function() {
            //         var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            //         return !~text.indexOf(val);
            //     }).hide();
            // });

            // var $row1 = $('#mytable3 tbody tr');
            // $('#search4').keyup(function() {
            //     var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
                
            //     $row1.show().filter(function() {
            //         var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            //         return !~text.indexOf(val);
            //     }).hide();
            // });



            function filterSearch(inputId, tableId) {
                const $input = $(inputId);
                const $rows = $(tableId + ' tbody tr');
                const $tbody = $(tableId + ' tbody');

                if ($tbody.find('#no-record-row').length === 0) {
                    $tbody.append('<tr id="no-record-row" style="display:none;"><td colspan="100%" style="text-align:center; font-weight:bold;">No records found</td></tr>');
                }

                $input.on('keyup', function () {
                    const val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
                    let visibleCount = 0;

                    $rows.each(function () {
                        const text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                        if (text.indexOf(val) !== -1) {
                            $(this).show();
                            visibleCount++;
                        } else {
                            $(this).hide();
                        }
                    });

                    $('#no-record-row', $tbody).toggle(visibleCount === 0);
                });
            }

            filterSearch('#search1', '#mytable');
            filterSearch('#search2', '#mytable1');
            filterSearch('#search3', '#mytable2');
            filterSearch('#search4', '#mytable3');



            $("#saveExcel2").click(function(){
                
                fetch("{{ route('userprofile', ['id' => $iddata->pulliid]) }}", {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest' 
                        }
                    })
                    .then(response => response.json())
                    .then(response => {            
                        // console.log(response);
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
                        
                        // Create workbook and add worksheet
                        const wb = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(wb, ws, "list");
                        
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
    <script>
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
<!-- 
<style>
@media print {
  table, tr, td, th {f
    page-break-inside: avoid !important;
    break-inside: avoid !important;
  }
}
</style> -->

  <script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('saveAsPdf').addEventListener('click', () => {

        const pulliid = {!! json_encode($iddata->pulliid) !!};
        const page = document.getElementById('wholepage');

        const filename = `Report_${pulliid}.pdf`;

        html2pdf().set({
            margin:       10,
            filename:     filename,
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
            pagebreak:    { mode: ['avoid-all', 'css', 'legacy'] } // important
        }).from(page).save();



        //  html2pdf().from(page).save(filename);
        });
    });
  </script>



@include('office.exportpdf')

