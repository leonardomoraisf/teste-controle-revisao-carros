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
                        <div class="col-sm-6 d-flex align-items-center">
                            <h1 class="m-0 mr-3">{{title}}</h1>
                            <a href="{{URL}}/dashboard/relatorio/clientes" type="button" class="btn btn-secondary btn-lg">Gerar relatório clientes</a>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{URL}}/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">{{title}}</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <nav class="nav nav-pills nav-justified mb-4">
                    <a class="nav-item nav-link {{active_geral}}" href="{{URL}}/dashboard/clientes">Geral</a>
                    <a class="nav-item nav-link {{active_sexo}}" href="{{URL}}/dashboard/clientes?f=sexo">Homens / Mulheres</a>
                </nav>
                <!-- Default box -->
                {{card_table}}
                <div class="col">
                    {{status}}
                    {{pagination}}
                </div>
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