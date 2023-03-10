<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped projects">
            <div class="d-flex justify-content-between m-2">
                <h2>Homens</h2><span>Idade média: {{media_homens}}</span>
            </div>
            <thead>
                <tr>
                    <th style="width: 1%">
                        #
                    </th>
                    <th style="width: 15%">
                        Nome
                    </th>
                    <th style="width: 5%;">
                        Idade
                    </th>
                    <th style="width: 10%">
                        Carros Registrados
                    </th>
                    <th style="width: 15%;">
                        Média entre revisões
                    </th>
                    <th style="width: 15%;">
                        Previsão próxima revisão
                    </th>
                    <th style="width: 20%">
                    </th>
                </tr>
            </thead>
            <tbody>

                {{itens_homens}}

            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped projects">
            <div class="d-flex justify-content-between m-2">
                <h2>Mulheres</h2><span>Idade média: {{media_mulheres}}</span>
            </div>
            <thead>
                <tr>
                    <th style="width: 1%">
                        #
                    </th>
                    <th style="width: 15%">
                        Nome
                    </th>
                    <th style="width: 5%;">
                        Idade
                    </th>
                    <th style="width: 10%">
                        Carros Registrados
                    </th>
                    <th style="width: 15%;">
                        Média entre revisões
                    </th>
                    <th style="width: 15%;">
                        Previsão próxima revisão
                    </th>
                    <th style="width: 20%">
                    </th>
                </tr>
            </thead>
            <tbody>

                {{itens_mulheres}}

            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>