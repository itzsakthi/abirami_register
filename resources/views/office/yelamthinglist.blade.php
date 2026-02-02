@extends('office.layout.layout')
@section('title', 'All Booths')

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
</style>
  
  <div class="button-row " style= "text-align: end; margin-right:30px;">
        <a style="display: none;" class="btn bg-gradient-success"  href="{{url('yelamthings')}}" ><i class="material-icons opacity-10">payments</i> Yelam things</a>
        <button type="button" id="saveAsExcel" class="btn btn-success" >Export Excel</button>           
    </div>


<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-success shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Yelam Things List</h6>
                    </div>
                </div>
                <div class="p-4">
                    <div class="input-group input-group-dynamic mb-4">
                        <div class="input-group input-group-outline col-6">
                            <label class="form-label">Search here...</label>
                            <input id="search" type="text" class="form-control">
                        </div>
                    </div>
                    

                    <div class="table-responsive">
                        <table class="table align-items-center mb-0" id="mytable" style="text-align:center;">
                            <thead>
                                <tr>
                                    <th class="col-2 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">S.No</th>
                                    <th class="col-10 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">YelamT things</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $ids = $data->firstItem(); @endphp
                                @foreach($data as $room)
                                <tr>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $ids }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $room->things }}</p>
                                    </td>
                                </tr>
                                @php $ids++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>    
                <div class="row" style="padding-bottom:30px">
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
            <div>
        </div>
    </div>

</div>
                      
        
    <!-- @include('office.layout.footer') -->
 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js">  </script>
  </script> 

 

<script>
    document.getElementById('pagination').onchange = function() {
        var url = window.location.href.split('?')[0]; // Get current URL without query parameters
        var newUrl = url + '?items=' + this.value; // Append the selected items parameter
        window.location.href = newUrl; // Redirect to the new URL
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
    return XLSX.writeFile(workbook, "Yelamthings.xlsx");
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




@stop
