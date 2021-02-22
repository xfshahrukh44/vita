<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>{{env('APP_NAME')}}</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="#" type="image/x-icon">

  <!-- jquery ui css-->
  <link rel="stylesheet" type="text/css" href="{{asset('plugins/jquery-ui/jquery-ui.css')}}">
  <!-- toastr css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
  
  <!-- jquery js -->
  <script type="text/javascript" src="{{asset('plugins/jquery/jquery.js')}}"></script>
  <!-- jquery ui js -->
  <script type="text/javascript" src="{{asset('plugins/jquery-ui/jquery-ui.js')}}"></script>
  <!-- toastr js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


  <link rel="stylesheet" href="{{asset('css/app.css')}}">
  <link rel="stylesheet" href="{{asset('css/custom-style.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('css/jquery.dataTables.min.css')}}">

  <!-- fancy box -->
  <link rel="stylesheet" href="{{asset('fancybox/source/jquery.fancybox.css?v=2.1.7')}}" type="text/css" media="screen" />

  <!-- jquery ui js -->

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  <!-- Fontawesome -->
  <!-- <link rel="stylesheet" href="{{asset('css/fontawesome.css')}}"/> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
</head>
<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            <img src="{{ asset('img/profile.png') }}" class="img-circle elevation-2" alt="User Image" width="20px"><span class="caret"></span> {{ucfirst(Auth::user()->name)}}
          </a>

          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <!-- <a class="dropdown-item" href="#">
              Profile
            </a> -->
            <a class="dropdown-item" href="{{ route('logout') }}"
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
          </a>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </li>
    </ul>

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('dashboard')}}" class="brand-link" id="topSidebar">
      <img src="{{ asset('img/logo.png') }}" alt="LaraStart Logo" class="brand-image img-circle elevation-3"
      style="opacity: .8">
      <span class="brand-text font-weight-light">Vita Foods</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          @can('isSuperAdmin')
            <!-- Dashboard -->
            <li class="nav-item">
              <a href="{{route('dashboard')}}" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt "></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
          @endcan
          <!-- Client Database -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Client Database
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview ml-2">
              <li class="nav-item">
                <a href="{{route('customer.index')}}" class="nav-link">
                  <i class="nav-icon fas fa-users"></i>
                  <p>Customers</p>
                </a>
              </li>
              @can('isSuperAdmin')
                <li class="nav-item">
                  <a href="{{route('vendor.index')}}" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Vendors</p>
                  </a>
                </li>
              @endcan
              <li class="nav-item">
                <a href="{{route('channel.index')}}" class="nav-link">
                  <i class="nav-icon fas fa-users"></i>
                  <p>Channels</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('hub.index')}}" class="nav-link">
                  <i class="nav-icon fas fa-users"></i>
                  <p>Hubs</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('area.index')}}" class="nav-link">
                  <i class="nav-icon  fas fa-map-marked-alt"></i>
                  <p>Areas and Markets</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Stock Management -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-truck"></i>
              <p>
                Stock Management
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview ml-2">
              <li class="nav-item">
                <a href="{{route('product.index')}}" class="nav-link">
                  <i class="nav-icon fab fa-product-hunt"></i>
                  <p>Products</p>
                </a>
              </li>
              @can('isSuperAdmin')
                <li class="nav-item">
                  <a href="{{route('stock_in.index')}}" class="nav-link">
                    <i class="nav-icon fas fa-sign-in-alt"></i>
                    <p>Stock In</p>
                  </a>
                </li>
              @endcan
              @can('isSuperAdmin')
                <li class="nav-item">
                  <a href="{{route('stock_out.index')}}" class="nav-link">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p>Stock Out</p>
                  </a>
                </li>
              @endcan
              <li class="nav-item">
                <a href="{{route('category.index')}}" class="nav-link">
                  <i class="nav-icon fas fa-copyright"></i>
                  <p>Categories</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('brand.index')}}" class="nav-link">
                  <i class="nav-icon fab fa-bootstrap"></i>
                  <p>Brands</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('unit.index')}}" class="nav-link">
                  <i class="nav-icon fas fa-balance-scale-left"></i>
                  <p>Units</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Accounting -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-money-check-alt"></i>
              <p>
                Accounting
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview ml-2">
              @can('isSuperAdmin')
                <li class="nav-item">
                  <a href="{{route('get_customer_ledgers')}}" class="nav-link">
                    <i class="fas fa-book nav-icon"></i>
                    <p>Customer Ledgers</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('get_vendor_ledgers')}}" class="nav-link">
                    <i class="fas fa-book nav-icon"></i>
                    <p>Vendor Ledgers</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('sales_ledgers')}}" class="nav-link">
                    <i class="fas fa-book nav-icon"></i>
                    <p>Sales Ledgers</p>
                  </a>
                </li>
              @endcan
              <li class="nav-item">
                <a href="{{route('receiving.index')}}" class="nav-link">
                  <i class="fas fa-book nav-icon"></i>
                  <p>Receipts</p>
                </a>
              </li>
              @can('isSuperAdmin')
                <li class="nav-item">
                  <a href="{{route('payment.index')}}" class="nav-link">
                    <i class="fas fa-book nav-icon"></i>
                    <p>Payments</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('expense.index')}}" class="nav-link">
                    <i class="fas fa-book nav-icon"></i>
                    <p>Expenses</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('expenses')}}" class="nav-link">
                    <i class="fas fa-book nav-icon"></i>
                    <p>Expense Ledgers</p>
                  </a>
                </li>
              @endcan
            </ul>
          </li>

          <!-- Order Management -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-clipboard"></i>
              <p>
                Order Management
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview ml-2">
              <!-- orders -->
              <li class="nav-item">
                <a href="{{route('order.index')}}" class="nav-link">
                  <i class="nav-icon fa fa-clipboard"></i>
                  <p>Orders</p>
                </a>
              </li>
              @can('isSuperAdmin')
                <!-- invoices -->
                <li class="nav-item">
                  <a href="{{route('invoice.index')}}" class="nav-link">
                    <i class="nav-icon fas fa-file-invoice-dollar"></i>
                    <p>Invoices</p>
                  </a>
                </li>
              @endcan
            </ul>
          </li>

          <!-- Marketing Plan -->
          @can('isSuperAdmin')
            <!-- <li class="nav-item">
              <a href="{{route('search_marketing')}}" class="nav-link">
                <i class="nav-icon fa fa-cart-arrow-down"></i>
                <p>
                  Marketing Plan
                </p>
              </a>
            </li> -->
          @endcan

          <!-- Your Marketing Tasks -->
          <!-- <li class="nav-item">
            <a href="{{route('search_marketing_tasks')}}" class="nav-link">
              <i class="nav-icon fas fa-tasks"></i>
              <p>
                Your Marketing Tasks
              </p>
            </a>
          </li> -->

          <!-- user management -->
          @can('isSuperAdmin')
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-user "></i>
                <p>
                  User Management
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview ml-2">
                <li class="nav-item">
                  <a href="{{route('user.index')}}" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Staff</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('rider')}}" class="nav-link">
                    <i class="fas fa-motorcycle nav-icon"></i>
                    <p>Riders</p>
                  </a>
                </li>
              </ul>
            </li>
          @endcan

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        @yield('content_header')
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @yield('content_body')
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">

  </footer>
</div>

<script src="{{asset('js/app.js')}}"></script>
<script src="{{asset('js/jquery.dataTables.min.js')}}" defer></script>
<script src="{{asset('dist/js/adminlte.js')}}"></script>
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>
<script src="{{asset('dist/js/demo.js')}}"></script>

<!-- fancybox -->
<script type="text/javascript" src="{{asset('fancybox/source/jquery.fancybox.pack.js?v=2.1.7')}}"></script>

<!-- jquery ui js-->
<script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>

<!-- pusher work -->

<!-- pusher -->
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>

<!-- pusher event binder -->
<script>
  // Enable pusher logging - don't include this in production
  // Pusher.logToConsole = true;

  var pusher = new Pusher('c568a790f53b416b3823', {
      cluster: 'ap2'
  });

  var channel = pusher.subscribe('my-channel');
  channel.bind('threshold_reached', function(data) {

      toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": true,
      "showDuration": "0",
      "hideDuration": "0",
      "timeOut": "0",
      "extendedTimeOut": "0",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
      }

      toastr["error"](data.message, "Threshold reached.");
  });
</script>

</body>
</html>