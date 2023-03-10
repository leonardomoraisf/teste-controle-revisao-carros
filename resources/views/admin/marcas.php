<!DOCTYPE html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Simpllis | {{title}}</title>

  {{links}}

</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    {{header}}

    {{sidebar}}

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">{{title}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{URL}}/dashboard">Home</a></li>
                <li class="breadcrumb-item active">Forms</li>
                <li class="breadcrumb-item active">{{title}}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            {{status}}
            {{statusSuccess}}
            {{statusError}}
          </div>
        </div>
        <form method="post" enctype="multipart/form-data">
          <div class="container-fluid">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Nova Marca</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <div class="card-body">
                <div class="row w-75">
                  <div class="col-sm-6">
                    <!-- select -->
                    <div class="form-group">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Nome</label>
                        <input name="nome" type="text" class="form-control " id="exampleInputEmail1" placeholder="Coloque o nome da marca" autofocus>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row m-2">
                <div class="col float-right">
                  <button type="submit" class="btn btn-success float-right">Submit</button>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <div class="d-flex justify-content-center">
              {{box_quantidade_marca_homem}}
              {{box_quantidade_marca_mulher}}
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table m-0">
                  <thead>
                    <tr>
                      <th>Nome</th>
                      <th>Quantidade de carros registrada</th>
                    </tr>
                  </thead>
                  <tbody>
                    {{itens_marcas}}
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.card-body -->

        </form>
    </div>
  </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->
  {{scriptlinks}}

</body>

</html>