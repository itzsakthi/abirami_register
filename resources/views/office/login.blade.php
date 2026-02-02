<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
     Login
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="{{asset('assets/css/nucleo-icons.css')}}" rel="stylesheet" />
  <link href="{{asset('assets/css/nucleo-svg.css')}}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="{{asset('assets/css/material-dashboard.css?v=3.0.0')}}" rel="stylesheet" />
</head>

<body class="bg-gray-200">
 
  <main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('{{asset('assets/img/son.jpg')}}');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                    <?php
                    if($_SERVER['HTTP_HOST']=="durgaiamman.templesmart.in"){?>
                        <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">துர்க்கையம்மன் கோவில் </h4> 
                     <?php
                    } else if($_SERVER['HTTP_HOST']=="singaravelar.templesmart.in"){?>
                    <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Singaravelar கோவில் </h4> 
                    <?php
                    } else if($_SERVER['HTTP_HOST']=="demo.templesmart.in"){?>
                    <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Demo கோவில் </h4> 
                    <?php } 
                    
                    else {?>
                     <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">சோணையன் கோவில் படைப்பு வீடு Sign in </h4>
                  
                     <?php   
                    }
                    ?>
                  <div class="row mt-3">
                    <div class="col-2 text-center ms-auto">
                      <a class="btn btn-link px-3" href="javascript:;">
                        <i class="fa fa-facebook text-white text-lg"></i>
                      </a>
                    </div>
                    <div class="col-2 text-center px-1">
                      <a class="btn btn-link px-3" href="javascript:;">
                        <i class="fa fa-github text-white text-lg"></i>
                      </a>
                    </div>
                    <div class="col-2 text-center me-auto">
                      <a class="btn btn-link px-3" href="javascript:;">
                        <i class="fa fa-google text-white text-lg"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <!-- <form method="GET" action="/dashboard"> -->
                <form method="POST" action="{{ route('login.custom') }}">

                    @csrf
                  <div class="input-group input-group-outline my-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                  </div>
                  <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                  </div>
                  <div class="form-check form-switch d-flex align-items-center mb-3">
                    <input class="form-check-input" type="checkbox" id="rememberMe">
                    <label class="form-check-label mb-0 ms-2" for="rememberMe">Remember me</label>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Sign in</button>
                  </div>
                  <p class="mt-4 text-sm text-center">
                    Don't know password ?
                    <a href="javascript:;" class="text-primary text-gradient font-weight-bold">Forgot Password</a>
                  </p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer position-absolute bottom-2 py-2 w-100">
        <div class="container">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-12 col-md-6 my-auto">
              <div class="copyright text-center text-sm text-white text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
               
                <a href="#" class="font-weight-bold text-white" target="_blank">Vplan Team</a>
               
              </div>
            </div>
            <div class="col-12 col-md-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                  <a href="" class="nav-link text-white" target="_blank"></a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link text-white" target="_blank">About Us</a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link text-white" target="_blank">Policy</a>
                </li>
              
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>
  <!--   Core JS Files   -->
  <script src="{{asset('assets/js/core/popper.min.js')}}"></script>
  <script src="{{asset('assets/js/core/bootstrap.min.js')}}"></script>
  <script src="{{asset('assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
  <script src="{{asset('assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{asset('assets/js/material-dashboard.min.js?v=3.0.0')}}"></script>
</body>
@if(session('error'))
<div class="position-fixed bottom-1 end-1 z-index-2">

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
      
       
        {{ session('error') }}
    
    </div>
  </div>


</div>
@endif
</html>