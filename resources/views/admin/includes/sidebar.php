<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{URL}}/dashboard" class="brand-link">
    <img src="{{URL}}/resources/assets/images/logo-simpllis.webp" alt="Simpllis" class="brand-image " style="opacity: .8">
    <span class="brand-text font-weight-light">.</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel pb-3 mb-3 d-flex mt-2">
      <div class="image" style="object-fit: cover;">
        <img src="{{URL}}/resources/assets/images/blank-user.jpg" class="img-circle elevation-2" alt="User Image" style="object-fit: cover;">
      </div>
      <div class="info">
        <a href="#" class="d-block">{{user_name}}</a>
      </div>
    </div>
    <!-- SidebarSearch Form -->
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <li class="nav-item">
          <a href="{{URL}}/dashboard/forms/cliente" class="nav-link {{active_novo_cliente}}">
            <i class="nav-icon fas fa-edit"></i>
            <p>Novo Cliente</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{URL}}/dashboard/marcas" class="nav-link {{active_nova_marca}}">
            <i class="nav-icon fas fa-money-bill"></i>
            <p>Marcas</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{URL}}/dashboard/clientes" class="nav-link {{active_clientes}}">
            <i class="nav-icon fa fa-user"></i>
            <p>Clientes</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{URL}}/dashboard/carros" class="nav-link {{active_carros}}">
            <i class="nav-icon fas fa-car"></i>
            <p>Carros</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{URL}}/dashboard/revisoes" class="nav-link {{active_revisoes}}">
            <i class="nav-icon fa fa-book"></i>
            <p>Revisões</p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>