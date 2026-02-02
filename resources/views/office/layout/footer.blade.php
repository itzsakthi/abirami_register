<footer class="footer py-4  ">
    <div class="container-fluid">
      <div class="row align-items-center justify-content-lg-between">
        <div class="col-lg-6 mb-lg-0 mb-4">
          <div class="copyright text-center text-sm text-muted text-lg-start">
            © <script>
              document.write(new Date().getFullYear())
            </script>,
           
            <a href="javascript:;" class="font-weight-bold" target="_blank">Vplan Team</a>
          
          </div>
        </div>
        <div class="col-lg-6">
          <ul class="nav nav-footer justify-content-center justify-content-lg-end">
           
            <li class="nav-item">
              <a href="https://acmee.in" class="nav-link text-muted" target="_blank">About Us</a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link text-muted" target="_blank">Policy</a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link pe-0 text-muted" target="_blank">License</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>
              
  @if(session('success'))
  <div class="position-fixed bottom-1 end-1 z-index-2">
     
    <div class="toast fade p-2 mt-2 bg-gradient-info show" role="alert" aria-live="assertive" id="infoToast" aria-atomic="true">
      <div class="toast-header bg-transparent border-0">
        <i class="material-icons text-white me-2">
    notifications
  </i>
        <span class="me-auto text-white font-weight-bold">Temple Smart </span>
        <small class="text-white">Just Now</small>
        <i class="fas fa-times text-md text-white ms-3 cursor-pointer" data-bs-dismiss="toast" aria-label="Close" aria-hidden="true"></i>
      </div>
      <hr class="horizontal light m-0">
      <div class="toast-body text-white">
        {{ session('success') }}
      </div>
    </div>
   
  </div>



  @endif


  @if ($errors->any())
  <div class="position-fixed bottom-1 end-1 z-index-2">
      @foreach ($errors->all() as $error)
  <div class="toast fade show p-2 mt-2 bg-white" role="alert" aria-live="assertive" id="warningToast" aria-atomic="true">
      <div class="toast-header border-0">
        <i class="material-icons text-warning me-2">
    travel_explore
  </i>
        <span class="me-auto font-weight-bold">Error</span>
        <small class="text-body">Just Now</small>
        <i class="fas fa-times text-md ms-3 cursor-pointer" data-bs-dismiss="toast" aria-label="Close"></i>
      </div>
      <hr class="horizontal dark m-0">
      <div class="toast-body">
        
         
          {{ $error }}
      
      </div>
    </div>
  
    @endforeach
  </div>
  @endif

