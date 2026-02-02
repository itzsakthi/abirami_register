@extends('office.layout.layout')
@section('title', 'Income & Expense')

@section('content')

<style>
    /* Header colors */
    .income-title {
        background-color: #d1e7dd !important;
        font-weight: 700;
        text-align: center;
    }

    .expense-title {
        background-color: #f8d7da !important;
        font-weight: 700;
        text-align: center;
    }

    /* Parent table */
    .big-table th,
    .big-table td {
        vertical-align: middle;
        text-align: center;
    }

    /* Nested income tables RESET */
    .big-table table {
        margin: 0;
        border: none;
    }

    .big-table table th,
    .big-table table td {
        border: none !important;
        padding: 6px 10px;
    }

    /* Nested income headers */
    .income-with th,
    .income-without th {
        background: #f1f3f5;
        font-size: 13px;
        font-weight: 600;
    }

    /* Amount style */
    .income-with td,
    .income-without td {
        font-weight: 600;
    }

    /* Filter box */
    .filter-box {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
    }

    .previous-year-income {
        padding: 6px 14px;
        background: #F4F6F8;
        border-left: 4px solid #0d6efd;
        font-weight: 600;
        font-size: 14px;
        border-radius: 4px;
    }

    .previous-year-income span {
        color: #198754; /* green for income */
        font-weight: 700;
    }

</style>

<div class="container-fluid py-4">
    <div class="card my-4">

        <!-- HEADER -->
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">

                <h6 class="text-white ps-3 mb-0">Income & Expense Report</h6>
                <button class="btn btn-sm bg-gradient-light me-3" id="export_excel">
                    Export Excel
                </button>
            </div>
        </div>

        <div class="card-body">

            <!-- FILTERS -->
          <div class="filter-box">
              <!-- ROW 1 -->
              <div class="row align-items-end mb-2">
                  <div class="col-md-2">
                      <label>From Date</label>
                      <input type="month" id="from_date" class="form-control">
                  </div>
          
                  <div class="col-md-2">
                      <label>To Date</label>
                      <input type="month" id="to_date" class="form-control">
                  </div>
          
                  <div class="col-md-2">
                      <button id="resultbtn" class="btn btn-success w-75">Get Results</button>
                  </div>
              </div>
          
              <!-- ROW 2 -->
              <div class="row">
                  <div class="col-md-12">
                      <label>Yellam Filter</label><br>
                      <div class="form-check form-check-inline">
                          <input class="form-check-input yellam-toggle" type="radio" name="yellam" value="with" checked>
                          <label class="form-check-label">With Yellam</label>
                      </div>
                      <div class="form-check form-check-inline">
                          <input class="form-check-input yellam-toggle" type="radio" name="yellam" value="without">
                          <label class="form-check-label">Without Yellam</label>
                      </div>
                      <div class="form-check form-check-inline">
                          <div class="previous-year-income">Previous Year Income : <span> ₹ {{ number_format($data,  2) }} </span> </div>
                      </div>
                  </div>
              </div>
          
          </div>
    
              
            <!-- BIG TABLE -->
            <div class="table-responsive p-3">
               <table class="table table-bordered align-middle" style="width:100%">
    <thead>
        <tr>
            <th colspan="2" class="income-title text-center">INCOME</th>
            <th colspan="2" class="expense-title text-center">EXPENSE</th>
        </tr>
    </thead>

    <tbody id="report-body">

        <!-- INCOME ROWS (FIXED) -->
        <tr class="expense-slot">
            <th>Pullivari</th>
            <td id="pullivari_val">0.00</td>

            <th class="expense-particular"></th>
            <td class="expense-amount"></td>
        </tr>

        <tr class="expense-slot">
            <th>PM Vari</th>
            <td id="pmvari_val">0.00</td>
        
            <th class="expense-particular"></th>
            <td class="expense-amount"></td>
        </tr>

        <tr class="yellam-row expense-slot">
            <th>Yellam</th>
            <td id="yellam_val">0.00</td>

            <th class="expense-particular"></th>
            <td class="expense-amount"></td>
        </tr>

        <tr class="expense-slot">   
            <th>Donation</th>
            <td id="donation_val">0.00</td>

            <th class="expense-particular"></th>
            <td class="expense-amount"></td>
        </tr>

        <tr class="expense-only d-none">
            <th colspan="2"></th>
            <th class="expense-particular"></th>
            <td class="expense-amount"></td>
        </tr>
            

        <tr style="background-color: #DBDBDB" class="fw-bold total-row">
            <th>Total</th>
            <td id="income_total">0.00</td>

            <th>Total</th>
            <td id="expense_total">0.00</td>
        </tr>


    </tbody>
</table>

            </div>

        </div>
    </div>
</div>

@endsection


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>


<script>
$(document).ready(function () {
    
    $('#from_date').on('change', function () {
        let fromMonth = $(this).val();
        $('#to_date').attr('min', fromMonth);
    });
    
    $('#to_date').on('change', function () {
        let toMonth = $(this).val();
        $('#from_date').attr('max', toMonth);
    });

    

    let reportData = [];

    $('#resultbtn').on('click', function () {
        $.ajax({
            url: "{{ route('pl_report_calc') }}",
            type: "POST",
            data: {
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val(),
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {
                
                reportData = response;
                renderTable(reportData);
            }
        });
    });

    $('input[name="yellam"]').on('change', function () {
        renderTable(reportData);
    });

    function renderTable(reportData) {
        if (!reportData) return;
    
        let showYellam = $('input[name="yellam"]:checked').val() === 'with';
    
        let pullivari = Number(reportData.pullivari || 0);
        let pmvari = Number(reportData.pmvari || 0);
        let yellam    = Number(reportData.yellam || 0);
        let donation  = Number(reportData.donation || 0);
    
        let incomeTotal = pullivari + pmvari + (showYellam ? yellam : 0) + donation;
    
        
        $('#pullivari_val').text(pullivari.toFixed(2));
        $('#pmvari_val').text(pmvari.toFixed(2));
        $('#yellam_val').text(yellam.toFixed(2));
        $('#donation_val').text(donation.toFixed(2));
        $('#income_total').text(incomeTotal.toFixed(2));
    
        $('.yellam-row').toggle(showYellam);
    
        /* EXPENSE  */
        $('.expense-particular').text('');
        $('.expense-amount').text('');
    
        // remove previously cloned rows
        $('.expense-only').not(':first').remove();
    
        let expenseRows = Object.entries(reportData.expenses || {});
        let expenseTotal = 0;
    
        // get available visible slots (independent of yellam)
        let slots = $('.expense-slot:visible').toArray();
    
        // loop expenses
        expenseRows.forEach((item, i) => {
    
            expenseTotal += Number(item[1]);
    
            let row;
    
            // use existing slot if available
            if (slots[i]) {
                row = $(slots[i]);
            } else {
                // otherwise clone expense-only row
                let newRow = $('.expense-only').first().clone().removeClass('d-none');
                $('#report-body .total-row').before(newRow);
                row = newRow;
            }
    
            row.find('.expense-particular').text(item[0]);
            row.find('.expense-amount').text(Number(item[1]).toFixed(2));
        });
    
        $('#expense_total').text(expenseTotal.toFixed(2));
    
    
        
    }

        
    $('#export_excel').on('click', function () {
        // Clone table so UI is untouched
        let tableClone = $('.table').first().clone();
    
        // Remove hidden rows (Yellam when "Without Yellam")
        tableClone.find('tr').each(function () {
            if ($(this).css('display') === 'none') {
                $(this).remove();
            }
        });
    
        // Remove action / unwanted elements if any (safe)
        tableClone.find('button, input').remove();
    
        // Convert table to worksheet
        let wb = XLSX.utils.book_new();
        let ws = XLSX.utils.table_to_sheet(tableClone[0]);
    
        XLSX.utils.book_append_sheet(wb, ws, 'Income & Expense');
    
        // File name based on filter
        let yellamMode = $('input[name="yellam"]:checked').val() === 'with'
            ? 'with_yellam'
            : 'without_yellam';
    
        XLSX.writeFile(wb, `income_expense_${yellamMode}.xlsx`);
    });

        

});
</script>

