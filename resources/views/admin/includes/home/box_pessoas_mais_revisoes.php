<!-- USERS LIST -->
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Pessoas com mais revisões</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body p-0">
        <ul class="users-list clearfix">
            {{itens}}
        </ul>
        <!-- /.users-list -->
    </div>
    <!-- /.card-body -->
    <div class="card-footer text-center">
        <a href="{{URL}}/dashboard/revisoes">Ver todas as revisões</a>
    </div>
    <!-- /.card-footer -->
</div>
<!--/.card -->