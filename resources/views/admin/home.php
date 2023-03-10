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
                    <!-- Small boxes (Stat box) -->
                    <div class="row">


                        {{box_total_clientes}}
                        {{box_total_carros}}
                        {{box_sexo_mais_carros}}
                        {{box_total_revisoes}}

                        <div class="row m-auto">
                            {{chartjs_marcas_mais_utilizadas}}

                            {{chartjs_marcas_mais_revisoes}}
                        </div>

                        <div class="container">
                            {{box_pessoas_mais_revisoes}}
                        </div>

                        <div class="container-fluid mt-4 mb-3">
                            <a href="{{URL}}/dashboard/relatorio/geral" type="button" class="btn btn-primary btn-lg btn-block">Gerar relatório geral</a>
                        </div>

                    </div>
                    <!-- /.row -->

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