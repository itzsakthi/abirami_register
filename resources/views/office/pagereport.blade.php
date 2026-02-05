@extends('office.layout.layout')
@section('title', 'Report')
@section('content')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.12.13/xlsx.full.min.js"></script>

{{-- tailwind --}}
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">


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


<button type="button" id="saveAsExcel" class="btn btn-success" style="margin-left:80%;">Export Excel</button>       
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4" >
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3"> Report Page</h6> 
                    </div>
                </div>
                <div class="d-flex my-2 justify-content-start">     
                    <form  id="s_content" style="display:none;" class="relative w-full max-w-5xl mx-auto mb-4 px-2 py-3 bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-xl border border-gray-200 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 backdrop-blur-sm">
                        <div class="relative z-10">
                            <div class="flex items-center gap-4">
                                <label for="advanced_search_type" class="ml-8 text-lg font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent dark:from-blue-400 dark:to-purple-400 uppercase tracking-wide whitespace-nowrap">
                                    Select Report Type
                                </label> 
                                <div class="relative group flex-1">
                                    <select id="advanced_search_type"
                                        class="appearance-none block w-full min-w-[400px] px-4 py-2 bg-gradient-to-r from-gray-100 to-white border-2 border-gray-300 rounded-xl shadow-inner focus:outline-none focus:ring-4 focus:ring-blue-500/30 focus:border-blue-500 text-sm text-gray-700 dark:bg-gradient-to-r dark:from-gray-800 dark:to-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-400/30 dark:focus:border-blue-400 transition-all duration-300 hover:border-blue-400 hover:shadow-lg font-medium pr-12 cursor-pointer group-hover:bg-gradient-to-r group-hover:from-blue-50 group-hover:to-purple-50">
                                        <!-- <option value="0" selected class="bg-white text-gray-900 font-medium py-2">ALL</option> -->
                                        <option value="0">Select Type</option>
                                        <option value="1" class="bg-white text-gray-900 font-medium py-2">PULLIVARI</option>
                                        <option value="2" class="bg-white text-gray-900 font-medium py-2">DONATION</option>
                                        <option value="3" class="bg-white text-gray-900 font-medium py-2">YELLAM</option>
                                        <option value="4" class="bg-white text-gray-900 font-medium py-2">EXPENSE</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <svg class="w-5 h-5 text-blue-500 transform transition-transform duration-300 group-hover:rotate-180 group-hover:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-blue-400/20 to-purple-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute -top-1 -left-1 -right-1 h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-t-2xl opacity-0 hover:opacity-100 transition-opacity duration-300"></div>
                    </form>
                </div>

                <div id='previous_yellam_search' style="display:none;">

                    <div class="p-4">
                        <div class="input-group input-group-static mb-4">
                            <label for="yelamporul">Yelam Porul</label>
                            <select class="form-control" id="yelamporul" name="yelamporul">
                                <option value="">Select Yelam Porul</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->things }}</option>
                                @endforeach  
                            </select>
                        </div>
                        <div style="display: flex; flex direction: row; gap: 1%;">
                            <div  class="input-group input-group-outline my-3">
                                <label  class="form-label" for="pulliid">Pulli Id</label>
                                <input type="text" class="form-control" id="pulliid" name="pulliid">
                            </div>
    
                            <div  class="input-group input-group-outline my-3">
                                <label class="form-label" for="native">Native</label>
                                <input type="text" class="form-control" id="native" name="native">
                            </div>
    
                            <div  class="input-group input-group-outline my-3">
                                <label class="form-label" for="nameguest">Guest Name</label>
                                <input type="text" class="form-control" id="nameguest" name="nameguest">
                            </div>
                        </div>
                        <div  style="display: flex; flex-direction: row; margin-left:5%; gap: 50px;">
    
                            <div  class="input-group input-group-outline my-3">
                                <label class="form-label" for="whatsappnoguest">Guest Whatsapp Number</label>
                                <input type="text" class="form-control" id="whatsappnoguest" name="whatsappnoguest">
                            </div>
        
                            <div  class="input-group input-group-outline my-3">
                                <label class="form-label" for="nativeguest">Guest Native</label>
                                <input type="text" class="form-control" id="nativeguest" name="nativeguest">
                            </div> 
                        </div> 
                    </div> 
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-6 mt-3"> 
                                <div class="form-check text-center"> 
                                    <button type="button" id="submit" class="btn btn-success">Advanced Search</button>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>





                <div id='advanced_yellam_search' style="display:none;">

                    <form id="yellam_form">
                        <div class="p-4">
                            <div class="input-group input-group-static mb-4">
                                <label for="y_yelamporul">Yelam Porul</label>
                                <select class="form-control" id="y_yelamporul" name="y_yelamporul">
                                    <option value="">Select Yelam Porul</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->things }}</option>
                                    @endforeach  
                                </select>
                            </div>
                            <div class="input-group input-group-static mb-4">
                                <label for="yellam_paid_unpaid">Paid/ Unpaid</label>
                                <select class="form-control" id="yellam_paid_unpaid" name="yellam_paid_unpaid">
                                    <option value="">Select Paid Status</option>
                                    <option value="paid">Paid</option>
                                    <option value="Not paid">Unpaid</option>
                                </select>
                            </div>
                            <div style="display: flex; flex direction: row; gap: 1%;">
                                <div  class="input-group input-group-outline my-3">
                                    <label  class="form-label" for="y_pulliid">Pulli Id</label>
                                    <input type="text" class="form-control" id="y_pulliid" name="y_pulliid">
                                </div>
        
                                <div  class="input-group input-group-outline my-3">
                                    <label class="form-label" for="y_native">Native</label>
                                    <input type="text" class="form-control" id="y_native" name="y_native">
                                </div>
        
                                <div  class="input-group input-group-outline my-3">
                                    <label class="form-label" for="y_nameguest">Guest Name</label>
                                    <input type="text" class="form-control" id="y_nameguest" name="y_nameguest">
                                </div>
                            </div>
                            <div  style="display: flex; flex-direction: row; margin-left:5%; gap: 50px;">
        
                                <div  class="input-group input-group-outline my-3">
                                    <label class="form-label" for="y_whatsappnoguest">Guest Whatsapp Number</label>
                                    <input type="text" class="form-control" id="y_whatsappnoguest" name="y_whatsappnoguest">
                                </div>
            
                                <div  class="input-group input-group-outline my-3">
                                    <label class="form-label" for="y_nativeguest">Guest Native</label>
                                    <input type="text" class="form-control" id="y_nativeguest" name="y_nativeguest">
                                </div> 
                            </div> 
                           
                          
                            <div style="display: flex; flex direction: row; gap: 1%;">
                                <div  class="input-group input-group-outline my-3">
                                    <label class="form-label" for="yellam_from">From Date</label>
                                    <input type="text" class="form-control" id="yellam_from" name="yellam_from">
                                </div>
                                <div  class="input-group input-group-outline my-3">
                                    <label class="form-label" for="yellam_to">To Date</label>
                                    <input type="text" class="form-control" id="yellam_to" name="yellam_to">
                                </div>
                            </div>
                        </div> 
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-6 mt-3"> 
                                    <div class="form-check text-center"> 
                                        <button type="button" id="reset_yellam" class="btn btn-danger" style="margin-right:3%;">Reset</button>
                                        <button type="button" id="yellam_submit" class="btn btn-success">Advanced Search</button>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </form>
                </div>


                <div id='advanced_don_search' style="display:none;">

                    {{-- advanced Donation search --}}
                    <form id="don_form">
                        <div class="p-4">
                            <div class="input-group input-group-static mb-4">
                                <label for="don_pulli_ids">Pulli id</label>
                                <select class="form-control" id="don_pulli_ids" name="don_pulli_ids">
                                    <option value="">Select Pulli ID</option>
                                    <option value="NA">Others</option>
                                    @foreach ($pullidata as $ids)
                                        <option value="{{ $ids->pulliid }}">{{ $ids->pulliid }}</option>
                                    @endforeach  
                                </select>
                            </div>
                            <div class="input-group input-group-static mb-4">
                                <label for="don_type_ids">Donation type</label>
                                <select class="form-control" id="don_type_ids" name="don_type_ids">
                                    <option value="">Select Donation Type</option>
                                    <option value="MONEY">MONEY</option>
                                    <option value="ASSET MOVABLE">ASSET MOVABLE</option>
                                    <option value="ASSET IMMOVABLE">ASSET IMMOVABLE</option>
                                </select>
                            </div>
                            <div style="display: flex; flex direction: row; gap: 1%;">

                                <div  class="input-group input-group-outline my-3">
                                    <label class="form-label" for="don_name">Name</label>
                                    <input type="text" class="form-control" id="don_name" name="don_name">
                                </div>
                                <div  class="input-group input-group-outline my-3">
                                    <label class="form-label" for="don_mob_no">Mobile NO</label>
                                    <input type="number" class="form-control" id="don_mob_no" name="don_mob_no">
                                </div>
                                 <div  class="input-group input-group-outline my-3">
                                    <label class="form-label" for="don__native">Native</label>
                                    <input type="text" class="form-control" id="don__native" name="don__native">
                                </div>
                                
                            </div>
                            <div style="display: flex; flex direction: row; gap: 1%;">
                                <div  class="input-group input-group-outline my-3">
                                    <label class="form-label" for="don_from">From Date</label>
                                    <input type="text" class="form-control" id="don_from" name="don_from">
                                </div>
                                <div  class="input-group input-group-outline my-3">
                                    <label class="form-label" for="don_to">To Date</label>
                                    <input type="text" class="form-control" id="don_to" name="don_to">
                                </div>
                            </div>
                        </div> 
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-6 mt-3"> 
                                    <div class="form-check text-center"> 
                                        <button type="button" id="reset_donation" class="btn btn-danger" style="margin-right:3%;">Reset</button>
                                        <button type="button" id="don_submit" class="btn btn-success">Advanced Search</button>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </form>
                </div>

                <div id='advanced_pulli_search' style="display:none;">

                    {{-- advanced pulli search --}}
                    <form id="pulli_form">
                        <div class="p-4">
                            <div class="input-group input-group-static mb-4">
                                <label for="pulli_ids">Pulli id</label>
                                <select class="form-control" id="pulli_ids" name="pulli_ids">
                                    <option value="">Select Pulli ID</option>
                                    @foreach ($pullidata as $ids)
                                        <option value="{{ $ids->pulliid }}">{{ $ids->pulliid }}</option>
                                    @endforeach  
                                </select>
                            </div>
                            <div class="input-group input-group-static mb-4">
                                <label for="master_pulli_year">Pullivari Year</label>
                                <select class="form-control" id="master_pulli_year" name="master_pulli_year">
                                    <option value="">Select Year</option>
                                    @foreach ($master_pulli_data as $ids)
                                        <option value="{{ $ids->annual }}">{{ $ids->annual }} to {{ $ids->by_annual }}</option>
                                    @endforeach  
                                </select>
                            </div>
                            <div class="input-group input-group-static mb-4">
                                <label for="pulli_paid_unpaid">Paid/ Unpaid</label>
                                <select class="form-control" id="pulli_paid_unpaid" name="pulli_paid_unpaid">
                                    <option value="">Select Paid Status</option>
                                    <option value="paid"> Paid </option>
                                    <option value="Not paid"> Unpaid </option>                  
                                    <option value=""> Both </option>                  
                                </select>
                            </div>
                            <div style="display: flex; flex direction: row; gap: 1%;">

                                <div  class="input-group input-group-outline my-3">
                                    <label class="form-label" for="pulli_name">Name</label>
                                    <input type="text" class="form-control" id="pulli_name" name="pulli_name">
                                </div>
                                <div  class="input-group input-group-outline my-3">
                                    <label class="form-label" for="mob_no">Mobile NO</label>
                                    <input type="number" class="form-control" id="mob_no" name="mob_no">
                                </div>
                                <div  class="input-group input-group-outline my-3">
                                    <label class="form-label" for="pulli_native">Native</label>
                                    <input type="text" class="form-control" id="pulli_native" name="pulli_native">
                                </div>
                              
                               
                            </div>
                            <div id="pulli_dates_container" style="display: none; flex-direction: row; gap: 1%;">
                                <div  class="input-group input-group-outline my-3">
                                    <label class="form-label" for="pullivari_from">From Date</label>
                                    <input type="text" class="form-control" id="pullivari_from" name="pullivari_from">
                                </div>
                                <div  class="input-group input-group-outline my-3">
                                    <label class="form-label" for="pullivari_to">To Date</label>
                                    <input type="text" class="form-control" id="pullivari_to" name="pullivari_to">
                                </div>
                            </div>
                        </div> 
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-6 mt-3"> 
                                    <div class="form-check text-center"> 
                                        <button type="button" id="reset_pullivari" class="btn btn-danger" style="margin-right:3%;">Reset</button>
                                        <button type="button" id="pulli_submit" class="btn btn-success">Advanced Search</button>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </form>
                </div>

                <div id='advanced_expense_search' style="display:none;">
                    {{-- advanced expense search --}}
                    <form id="expense_form">
                        <div class="p-4">
                            
                            <div style="display: flex; flex direction: row; gap: 5%;">
                          
                                <div  class="input-group input-group-static my-3">
                                    <label for="exp_name">Name</label>
                                    <select class="form-control" id="exp_name" name="exp_name">
                                        <option value="">Select Expense Name</option>
                                        @foreach($expensedata1 as $ids)
                                            <option value="{{ $ids->expenses_name }}">{{ $ids->expenses_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div  class="input-group input-group-static my-3">
                                    <label for="exp_pay_to">Pay To</label>
                                    <select class="form-control" id="exp_pay_to" name="exp_pay_to">
                                        <option value="">Select Pay To</option>
                                        @foreach($expensedata2 as $ids)
                                            <option value="{{ $ids->pay_to_txt }}">{{ $ids->pay_to_txt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div  class="input-group input-group-static my-3">
                                    <label for="exp_pay_mode">Pay Mode</label>
                                    <select class="form-control" id="exp_pay_mode" name="exp_pay_mode">
                                        <option value="">Select Pay Mode</option>
                                        @foreach($expensedata2 as $ids)
                                            <option value="{{ $ids->pay_mode }}">{{ $ids->pay_mode }}</option>
                                        @endforeach
                                    </select>         
                                 </div>
                            </div>
                            <div style="display: flex; flex direction: row; gap: 1%;">
                                <div  class="input-group input-group-outline my-3">
                                    <label class="form-label" for="exp_from">From Date</label>
                                    <input type="text" class="form-control" id="exp_from" name="exp_from">
                                </div>
                                <div  class="input-group input-group-outline my-3">
                                    <label class="form-label" for="exp_to">To Date</label>
                                    <input type="text" class="form-control" id="exp_to" name="exp_to">
                                </div>
                            </div>
                            <!--
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label" for="year">Date</label>
                                <input type="text" class="form-control" id="year" name="year">
                            </div> -->
                        </div> 
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-6 mt-3"> 
                                    <div class="form-check text-center"> 
                                        <button type="button" id="reset_expense" class="btn btn-danger" style="margin-right:3%;">Reset</button>
                                        <button type="button" id="expense_submit" class="btn btn-success">Advanced Search</button>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </form>
                </div>


            </div> 
        </div>
    </div>
</div>


{{-- Previous Yellam result table --}}
<div class="container-fluid py-4" style="display: none;" id="previous_yellam_result">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="p-4">
                    <div class="card-header p-0 position-relative mt-4 mx-3 z-index-2">
                        <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 style="font-weight:bold;" class="text-white text-capitalize ps-3">Result</h6>
                        </div>
                    </div>
                    <div class="table-responsive" style="width: 100%;">
                        <table   id="previous_yellam_table" class="table mb-0" style="width: 100%; text-align: center;">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sno</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pulli Id</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Yelam Porul</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Yelamtype</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Value</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Native</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Guest Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Guest Whatsapp</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Guest Native</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="10" class="text-center text-danger py-4">
                                        Search for entries.
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





{{-- Yellam result table --}}
<div class="container-fluid py-4" style="display: none;" id="advanced_yellam_result">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="p-4">
                    <div class="card-header p-0 position-relative mt-4 mx-3 z-index-2">
                        <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 style="font-weight:bold;" class="text-white text-capitalize ps-3">Result</h6>
                        </div>
                    </div>
                    <div class="table-responsive" style="width: 100%;">
                        <table   id="yellam_table" class="table mb-0" style="width: 100%; text-align: center;">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sno</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pulli Id</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Yelam Porul</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Yelamtype</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Value</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Native</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Guest Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Guest Whatsapp</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Guest Native</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="10" class="text-center text-danger py-4">
                                        Search for entries.
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

{{-- pullivari result table --}}
<div class="container-fluid py-4" style="display: none;" id="advanced_pulli_result">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="p-4">
                    <div class="card-header p-0 position-relative mt-4 mx-3 z-index-2">
                        <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 style="font-weight:bold;" class="text-white text-capitalize ps-3">Result</h6>
                        </div>
                    </div>
                    <div class="table-responsive" style="width: 100%;">
                        <table  id="pulli_table" class="table mb-0" style="width: 100%; text-align: center;">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sno</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pulli Id</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">whatsappnumber</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Spouse whatsappnumber</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Native</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">year</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="9" class="text-center text-danger py-4">
                                        Search for entries.
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

{{-- Donation result table --}}
<div class="container-fluid py-4" style="display: none;" id="advanced_don_result">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="p-4">
                    <div class="card-header p-0 position-relative mt-4 mx-3 z-index-2">
                        <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 style="font-weight:bold;" class="text-white text-capitalize ps-3">Result</h6>
                        </div>
                    </div>
                    <div class="table-responsive" style="width: 100%;">
                        <table  id="don_table" class="table mb-0" style="width: 100%; text-align: center;">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sno</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pulli Id</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">whatsappnumber</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Spouse whatsappnumber</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Native</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8" class="text-center text-danger py-4">
                                        Search for entries.
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

{{-- Expense result table --}}
<div class="container-fluid py-4" style="display: none;" id="advanced_expense_result">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="p-4">
                    <div class="card-header p-0 position-relative mt-4 mx-3 z-index-2">
                        <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 style="font-weight:bold;" class="text-white text-capitalize ps-3">Expense Result</h6>
                        </div>
                    </div>
                    <div class="table-responsive" style="width: 100%;">
                        <table  id="expense_table" class="table mb-0" style="width: 100%; text-align: center;">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sno</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Expense Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pay to</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Debit</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pay mode</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center text-danger py-4">
                                        Search for entries.
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


{{-- showing reportpage based on temple --}}
@if (request()->getHost() !== 'singaravelar.templesmart.in' && request()->getHost() !== 'napvm.templesmart.in')
    <script>
        $('#previous_yellam_search').show();
        $('#previous_yellam_result').show();   //showing only yellam for other temple
</script>
@else
    <script>
        $('#s_content').show();  //switch option for singaravel temple & nagammai temple
    </script>
@endif

@include('office.layout.footer')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('#submit').click(function() {
            var yelamporul = $('#yelamporul').val();
            var pulliid = $('#pulliid').val();
            var native = $('#native').val();
            var nameguest = $('#nameguest').val();
            var nativeguest = $('#nativeguest').val();
            var whatsappnoguest = $('#whatsappnoguest').val();
            $.ajax({
                url: 'api/Allreport',
                data: {
                    yelamporul: yelamporul,
                    pulliid: pulliid,
                    native: native,
                    nameguest: nameguest,
                    nativeguest: nativeguest,
                    whatsappnoguest: whatsappnoguest,
                },
                method: 'POST',
                success: function(response) {
                    console.log(response);
                    const tbody = $('#previous_yellam_table tbody');
                    tbody.empty();
                    // if (response.data === undefined || response.data.length == 0) {
                    //     var tableRow = '<tr><td colspan="9">No List found</td></tr>';
                    //     tbody.append(tableRow);
                    //     }
                    if (response.data && response.data.length > 0) {
                        let ids = 1;
                        response.data.forEach(function(item) {
                            let row = `
                                <tr>
                                    <td><p class="text-xs  font-weight-bold mb-0">${ids}</p></td>
                                    <td><p class="text-xs  font-weight-bold mb-0">${item.pulliid}</p></td>
                                    <td><p class="text-xs  font-weight-bold mb-0">${item.things}</p></td>
                                    <td><p class="text-xs  font-weight-bold mb-0">${item.name}</p></td>
                                    <td><p class="text-xs  font-weight-bold mb-0">${item.yelamtype}</p></td>
                                    <td><p class="text-xs  font-weight-bold mb-0">${item.value}</p></td>
                                    <td><p class="text-xs  font-weight-bold mb-0">${item.native}</p></td>
                                    <td><p class="text-xs  font-weight-bold mb-0">${item.nameguest}</p></td>
                                    <td><p class="text-xs  font-weight-bold mb-0">${item.whatsappnoguest}</p></td>
                                    <td><p class="text-xs  font-weight-bold mb-0">${item.nativeguest}</p></td>
                                </tr>
                            `;
                            ids++;
                            tbody.append(row);
                        });
                    } else {
                        tbody.append('<tr><td colspan="10">No List found</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Please try again using a search Key.');
                }
            });
        });
        
        $("#yelamporul").on('input', function() {
            if ($(this).val() !== '') {
                $("#pulliid").val('');
                $("#native").val('');
                $("#nameguest").val('');
                $("#nativeguest").val('');
                $("#whatsappnoguest").val('');
            }
        });
        $("#pulliid").on('input', function() {
            if ($(this).val() !== '') {
                $("#yelamporul").val('');
                $("#native").val('');
                $("#nameguest").val('');
                $("#nativeguest").val('');
                $("#whatsappnoguest").val('');
            }
        });
        $("#native").on('input', function() {
            if ($(this).val() !== '') {
                $("#pulliid").val('');
                $("#yelamporul").val('');
                $("#nameguest").val('');
                $("#nativeguest").val('');
                $("#whatsappnoguest").val('');
            }
        });
        $("#nameguest").on('input', function() {
            if ($(this).val() !== '') {
                $("#pulliid").val('');
                $("#yelamporul").val('');
                $("#native").val('');
                $("#nativeguest").val('');
                $("#whatsappnoguest").val('');
            }
        });
        $("#nativeguest").on('input', function() {
            if ($(this).val() !== '') {
                $("#yelamporul").val('');
                $("#pulliid").val('');
                $("#native").val('');
                $("#nameguest").val('');
                $("#whatsappnoguest").val('');
            }
        });
        $("#whatsappnoguest").on('input', function() {
            if ($(this).val() !== '') {
                $("#yelamporul").val('');
                $("#pulliid").val('');
                $("#native").val('');
                $("#nameguest").val('');
                $("#nativeguest").val('');
            }
        });
        
    });
</script>



<script>
   $(document).ready(function(){

        $("#saveAsExcel").click(function(){
            var workbook = XLSX.utils.book_new();
            
            // Get the HTML table
            var current_table = document.getElementById("advanced_search_type").value;

            if(current_table == 1) {
                var table = document.getElementById("pulli_table");
            } else if(current_table == 2) {
                var table = document.getElementById("don_table");
            } else if(current_table == 3) {
                var table = document.getElementById("yellam_table");
            } else if(current_table == 4) {
                var table = document.getElementById("expense_table");
            }

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
            

            let sheetName = "ReportList";
            if(current_table == 1) sheetName = "Pullivari_Report";
            if(current_table == 2) sheetName = "Donation_Report";
            if(current_table == 3) sheetName = "Yellam_Report";
            if(current_table == 4) sheetName = "Expense_Report";

            workbook.SheetNames.push(sheetName);
            workbook.Sheets[sheetName] = worksheet;

            let fileName = sheetName + ".xlsx";
            exportExcelFile(workbook, fileName);
        
        });
    })

    function exportExcelFile(workbook, fileName = "ReportList.xlsx") {
        return XLSX.writeFile(workbook, fileName);
    }

</script>

<!-- Flatpicker Script-->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
{{-- for pulli and yellam change using option field --}}
<script>
    const thisYear = new Date().getFullYear();

    //don_to
    flatpickr("#don_to", {
        minDate: `${thisYear+1}-01-01`
    });
    //for don_from
    flatpickr("#don_from", {
    maxDate: `${thisYear + 1}-12-31`, 
        onChange: function (selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) {
                const selectedYear = selectedDates[0].getFullYear() ;
                // const selectedMonth = selectedDates[0].getMonth() + 2;
                
                // console.log(selectedYear);
                // console.log(selectedMonth);
                
                // const minDate = `${selectedYear}-${selectedMonth}-01`;
                const nextDay = new Date(selectedDates[0]);
                nextDay.setDate(nextDay.getDate() + 2);

                const minDate = nextDay.toISOString().split('T')[0];


                    $('#don_to').val('')
                    don_to = flatpickr("#don_to", {
                        // maxDate: `${selectedYear}-12-31`,
                        minDate: minDate,
                    });
                
            }
        }
    });
   


    //pullivari_to
    flatpickr("#pullivari_to", {
        minDate: `${thisYear+1}-01-01`
    });
    //for pullivari_from
    flatpickr("#pullivari_from", {
    maxDate: `${thisYear + 1}-12-31`, 
        onChange: function (selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) {
                const selectedYear = selectedDates[0].getFullYear() ;
                // const selectedMonth = selectedDates[0].getMonth() + 2;
                
                // console.log(selectedYear);
                // console.log(selectedMonth);
                
                // const minDate = `${selectedYear}-${selectedMonth}-01`;

                const nextDay = new Date(selectedDates[0]);
                nextDay.setDate(nextDay.getDate() + 2);

                const minDate = nextDay.toISOString().split('T')[0];

                    $('#pullivari_to').val('')
                    pullivari_to = flatpickr("#pullivari_to", {
                        // maxDate: `${selectedYear}-12-31`,
                        minDate: minDate,
                    });
                
            }
        }
    });
   




    //exp_to
    flatpickr("#exp_to", {
        minDate: `${thisYear+1}-01-01`
    });
    //for exp_from
    flatpickr("#exp_from", {
    maxDate: `${thisYear + 1}-12-31`, 
        onChange: function (selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) {
                const selectedYear = selectedDates[0].getFullYear() ;
                // const selectedMonth = selectedDates[0].getMonth() + 2;
                
                // console.log(selectedYear);
                // console.log(selectedMonth);
                
                // const minDate = `${selectedYear}-${selectedMonth}-01`;

                const nextDay = new Date(selectedDates[0]);
                nextDay.setDate(nextDay.getDate() + 2);

                const minDate = nextDay.toISOString().split('T')[0];

                    $('#exp_to').val('')
                    exp_to = flatpickr("#exp_to", {
                        // maxDate: `${selectedYear}-12-31`,
                        minDate: minDate,
                    });
                
            }
        }
    });

    //yellam_to
    flatpickr("#yellam_to", {
        minDate: `${thisYear+1}-01-01`
    });
    //for yellam_from
    flatpickr("#yellam_from", {
    maxDate: `${thisYear + 1}-12-31`, 
        onChange: function (selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) {
                const selectedYear = selectedDates[0].getFullYear() ;
                // const selectedMonth = selectedDates[0].getMonth() + 2;
                
                // console.log(selectedYear);
                // console.log(selectedMonth);
                
                // const minDate = `${selectedYear}-${selectedMonth}-01`;

                const nextDay = new Date(selectedDates[0]);
                nextDay.setDate(nextDay.getDate() + 2);

                const minDate = nextDay.toISOString().split('T')[0];

                    $('#yellam_to').val('')
                    yellam_to = flatpickr("#yellam_to", {
                        // maxDate: `${selectedYear}-12-31`,
                        minDate: minDate,
                    });
                
            }
        }
    });



    document.getElementById("s_content").addEventListener('change', (event) => {
        var selectedValue = event.target.value;
        if(selectedValue) {
            if ((selectedValue == 1)) { 
                $('#reset_expense').trigger('click');
                $('#reset_yellam').trigger('click');
                $('#reset_donation').trigger('click');

                $('#advanced_yellam_search').hide();
                $('#advanced_yellam_result').hide();
                $('#advanced_pulli_result').show();
                $('#advanced_pulli_search').show();
                $('#advanced_don_result').hide();
                $('#advanced_don_search').hide();
                $('#advanced_expense_search').hide();
                $('#advanced_expense_result').hide();

            } 
            else if (selectedValue == 2 ){
                $('#reset_expense').trigger('click');
                $('#reset_yellam').trigger('click');
                $('#reset_pullivari').trigger('click');

                $('#advanced_yellam_search').hide();
                $('#advanced_yellam_result').hide();
                $('#advanced_don_search').show();
                $('#advanced_don_result').show();
                $('#advanced_pulli_search').hide();
                $('#advanced_pulli_result').hide();
                $('#advanced_expense_search').hide();
                $('#advanced_expense_result').hide();
            }
            else if (selectedValue == 3 ){
                $('#reset_expense').trigger('click');
                $('#reset_donation').trigger('click');
                $('#reset_pullivari').trigger('click');

                $('#advanced_yellam_search').show();
                $('#advanced_yellam_result').show();
                $('#advanced_pulli_result').hide();
                $('#advanced_pulli_search').hide();
                $('#advanced_don_result').hide();
                $('#advanced_don_search').hide();
                $('#advanced_expense_search').hide();
                $('#advanced_expense_result').hide();
            }
            else if (selectedValue == 4 ){
                $('#reset_yellam').trigger('click');
                $('#reset_donation').trigger('click');
                $('#reset_pullivari').trigger('click');

                $('#advanced_yellam_search').hide();
                $('#advanced_yellam_result').hide();
                $('#advanced_pulli_result').hide();
                $('#advanced_pulli_search').hide();
                $('#advanced_don_result').hide();
                $('#advanced_don_search').hide();
                $('#advanced_expense_search').show();
                $('#advanced_expense_result').show();
            }
            else{
                $('#advanced_yellam_search').hide();
                $('#advanced_yellam_result').hide();
                $('#advanced_pulli_result').hide();
                $('#advanced_pulli_search').hide();
                $('#advanced_don_result').hide();
                $('#advanced_don_search').hide();
                $('#advanced_expense_search').hide();
                $('#advanced_expense_result').hide();
            }

        };
    });
    //pulli search
    document.getElementById("pulli_submit").addEventListener('click', function(event) {
        const form = document.getElementById('pulli_form');
        const formData = new FormData(form);

        const pullivari_from = formData.get('pullivari_from')?.trim();
        const pullivari_to = formData.get('pullivari_to')?.trim();

        if (pullivari_to && !pullivari_from) {
        Swal.fire({
                icon: 'warning',
                title: 'Missing From Date',
                text: "Please select 'From Date' when 'To Date' is filled.",
            });
            return;
        }

        $.ajax({
            url: 'api/pullisearch',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Success:', response.amt);
                const tbody = $('#pulli_table tbody');
                tbody.empty();

                if (response.amt && response.amt.length > 0) {
                    let ids = 1;
                    response.amt.forEach(function(item) {
                        let row = `
                            <tr>
                                <td><p class="text-xs  font-weight-bold mb-0">${ids}</p></td>
                                <td><p class="text-xs  font-weight-bold mb-0">${item.pulliid}</p></td>
                                <td><p class="text-xs  font-weight-bold mb-0">${item.name}</p></td>
                                <td><p class="text-xs  font-weight-bold mb-0">${item.whatsappnumber}</p></td>
                                <td><p class="text-xs  font-weight-bold mb-0">${item.spousenumber}</p></td>
                                <td><p class="text-xs  font-weight-bold mb-0">${item.native}</p></td>
                                <td><p class="text-xs  font-weight-bold mb-0">${item.amt}</p></td>
                                <td><p class="text-xs  font-weight-bold mb-0">${item.year}</p></td>
                                <td><p class="text-xs  font-weight-bold mb-0">${item.status}</p></td>
                            </tr>
                        `;
                        ids++;
                        tbody.append(row);
                    });
                } else {
                    tbody.append('<tr><td colspan="9">No List found</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });

    //Donation search
    document.getElementById("don_submit").addEventListener('click', function(event) {
        const form = document.getElementById('don_form');
        const formData = new FormData(form);

        const don_from = formData.get('don_from')?.trim();
        const don_to = formData.get('don_to')?.trim();

        if (don_to && !don_from) {
        Swal.fire({
                icon: 'warning',
                title: 'Missing From Date',
                text: "Please select 'From Date' when 'To Date' is filled.",
            });
            return;
        }

        $.ajax({
            url: 'api/donsearch',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Success:', response.amt);
                const tbody = $('#don_table tbody');
                tbody.empty();

                if (response.amt && response.amt.length > 0) {
                    let ids = 1;
                    response.amt.forEach(function(item) {
                        let pulliid, native, whatsappnumber;

                        if (item.ref_id === 'NA') {
                            pulliid = 'other';

                            if (item.pay_to_txt?.includes('|||')) {
                                let parts = item.pay_to_txt.split("|||");
                                whatsappnumber = parts[0] || '';
                                native = parts[1] || '';
                            } else {
                                whatsappnumber = '';
                                native = '';
                            }
                        } else {
                            pulliid = item.pulliid;
                            native = item.native;
                            whatsappnumber = item.whatsappnumber;
                        }

                        let row = `
                            <tr>
                                <td><p class="text-xs  font-weight-bold mb-0">${ids}</p></td>
                                <td><p class="text-xs  font-weight-bold mb-0">${pulliid}</p></td>
                                <td><p class="text-xs  font-weight-bold mb-0">${item.ref_txt}</p></td>
                                <td><p class="text-xs  font-weight-bold mb-0">${whatsappnumber}</p></td>
                                <td><p class="text-xs  font-weight-bold mb-0">${item.spousenumber ?? '--'}</p></td>
                                <td><p class="text-xs  font-weight-bold mb-0">${native}</p></td>
                                <td><p class="text-xs  font-weight-bold mb-0">${item.amount}</p></td>
                                <td><p class="text-xs  font-weight-bold mb-0">${item.pay_mode}</p></td>
                            </tr>
                        `;
                        ids++;
                        tbody.append(row);
                    });
                } else {
                    tbody.append('<tr><td colspan="9">No List found</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });


    //Yellam search
    //Have to work on it !!!!!!
    document.getElementById("yellam_submit").addEventListener('click', function(event) {
        const form = document.getElementById('yellam_form');
        const formData = new FormData(form);

        const yellam_from = formData.get('yellam_from')?.trim();
        const yellam_to = formData.get('yellam_to')?.trim();

        if (yellam_to && !yellam_from) {
        Swal.fire({
                icon: 'warning',
                title: 'Missing From Date',
                text: "Please select 'From Date' when 'To Date' is filled.",
            });
            return;
        }

        $.ajax({
            url: 'api/yellamsearch',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                  console.log(response);
                  const tbody = $('#yellam_table tbody');
                  tbody.empty();
                  // if (response.data === undefined || response.data.length == 0) {
                  //     var tableRow = '<tr><td colspan="9">No List found</td></tr>';
                  //     tbody.append(tableRow);
                  //     }
                  if (response.data && response.data.length > 0) {
                      let ids = 1;
                      response.data.forEach(function(item) {
                          let row = `
                              <tr>
                                  <td><p class="text-xs  font-weight-bold mb-0">${ids}</p></td>
                                  <td><p class="text-xs  font-weight-bold mb-0">${item.pulliid}</p></td>
                                  <td><p class="text-xs  font-weight-bold mb-0">${item.things}</p></td>
                                  <td><p class="text-xs  font-weight-bold mb-0">${item.name}</p></td>
                                  <td><p class="text-xs  font-weight-bold mb-0">${item.yelamtype}</p></td>
                                  <td><p class="text-xs  font-weight-bold mb-0">${item.value}</p></td>
                                  <td><p class="text-xs  font-weight-bold mb-0">${item.native}</p></td>
                                  <td><p class="text-xs  font-weight-bold mb-0">${item.nameguest}</p></td>
                                  <td><p class="text-xs  font-weight-bold mb-0">${item.whatsappnoguest}</p></td>
                                  <td><p class="text-xs  font-weight-bold mb-0">${item.nativeguest}</p></td>
                                  <td><p class="text-xs  font-weight-bold mb-0">${item.payment}</p></td>
                              </tr>
                          `;
                          ids++;
                          tbody.append(row);
                      });
                  } else {
                      tbody.append('<tr><td colspan="10">No List found</td></tr>');
                  }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });



    //Expense search
    document.getElementById("expense_submit").addEventListener('click', function(event) {
        const form = document.getElementById('expense_form');
        const formData = new FormData(form);

        const exp_from = formData.get('exp_from')?.trim();
        const exp_to = formData.get('exp_to')?.trim();

        if (exp_to && !exp_from) {
        Swal.fire({
                icon: 'warning',
                title: 'Missing From Date',
                text: "Please select 'From Date' when 'To Date' is filled.",
            });
            return;
        }
        
        $.ajax({
            url: 'api/expensesearch',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // console.log('Success:', response.amt);
                // return;
                const tbody = $('#expense_table tbody');
                tbody.empty();

                if (response.amt && response.amt.length > 0) {
                    let ids = 1;
                    response.amt.forEach(function(item) {
                        console.log(item);
                   
                        let row = `
                            <tr>
                                <td><p class="text-xs  font-weight-bold mb-0">${ids}</p></td>
                                <td><p class="text-xs  font-weight-bold mb-0">${item.ref_txt}</p></td>
                                <td><p class="text-xs  font-weight-bold mb-0">${item.pay_to_txt}</p></td>
                                <td><p class="text-xs  font-weight-bold mb-0">${item.amount}</p></td>
                                <td><p class="text-xs  font-weight-bold mb-0">${item.pay_mode}</p></td>
                                <td><p class="text-xs  font-weight-bold mb-0">${item.remarks}</p></td>
                            </tr>
                        `;
                        ids++;
                        tbody.append(row);
                    });
                } else {
                    tbody.append('<tr><td colspan="6">No List found</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });
    
</script>


<script>
        
    $('#pulli_paid_unpaid').on('change', function() {
        const paidStatus = $('#pulli_paid_unpaid').val();

        if (paidStatus === 'paid') {
            $('#pulli_dates_container').css('display', 'flex');
        } else {
            $('#pulli_dates_container').css('display', 'none');
        }
    });


    $('#reset_expense').on('click', resetExpense)
    $('#reset_yellam').on('click', resetYellam)
    $('#reset_donation').on('click', resetDonation)
    $('#reset_pullivari').on('click', resetPullivari)

    function resetExpense() {
        $('#exp_name').val('');
        $('#exp_pay_to').val('');
        $('#exp_pay_mode').val('');
        $('#exp_from').val('');
        $('#exp_to').val('');
        $('#expense_table tbody').empty();
    }
    function resetYellam() {
        $('#y_yelamporul').val('');
        $('#y_pulliid').val('');
        $('#y_native').val('');
        $('#y_nameguest').val('');
        $('#y_whatsappnoguest').val('');
        $('#y_nativeguest').val('');
        $('#yellam_paid_unpaid').val('');
        $('#yellam_from').val('');
        $('#yellam_to').val('');
        $('#yellam_table tbody').empty();
    }
    function resetDonation() {
        $('#don_pulli_ids').val('');
        $('#don_type_ids').val('');
        $('#don_name').val('');
        $('#don_mob_no').val('');
        $('#don__native').val('');
        $('#don_from').val('');
        $('#don_to').val('');
        $('#don_table tbody').empty();
    }
    function resetPullivari() {
        $('#pulli_ids').val('');
        $('#pulli_name').val('');
        $('#mob_no').val('');
        $('#pullivari_from').val('');
        $('#pullivari_to').val('');
        $('#pulli_native').val('');
        $('#master_pulli_year').val('');
        $('#pulli_paid_unpaid').val('');
        $('#pulli_table tbody').empty();
    }

</script>



@stop
