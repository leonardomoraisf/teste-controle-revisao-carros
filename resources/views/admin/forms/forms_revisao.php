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
        <form method="post" enctype="multipart/form-data">
          <div class="container-fluid">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Nova revisão para {{marca_carro_cliente}} de: {{nome_cliente}}</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <div class="card-body">
                <div class="form-group w-50">
                  <label for="exampleInputEmail1">Data da revisão</label>
                  <input name="data" type="date" class="form-control " id="exampleInputEmail1">
                </div>
                <div class="row w-50">
                  <div class="col-sm-6">
                    <!-- select -->
                    <div class="form-group">
                      <label>Status</label>
                      <select name="status" class="form-control ">
                        <option value=""></option>
                        <option value="0">Pendente</option>
                        <option value="1">Completa</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row w-50">
                  <div class="col-sm-6">
                    <!-- select -->
                    <div class="form-group">
                      <label>Tipo</label>
                      <select name="tipo" class="form-control ">
                        <option value=""></option>
                        {{tipos}}
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleFormControlTextarea1">Detalhes da revisão</label>
                  <textarea name="detalhes" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div class="row">
              {{statusSuccess}}
              {{statusError}}
              <div class="col float-right">
                <button type="submit" class="btn btn-success float-right">Submit</button>
              </div>
            </div>
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