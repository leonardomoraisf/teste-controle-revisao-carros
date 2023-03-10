<script src="{{URL}}/resources/assets/js/fontawesome.js" crossorigin="anonymous"></script>
<!-- jQuery -->
<script src="{{URL}}/resources/assets/admin/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{URL}}/resources/assets/admin/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{URL}}/resources/assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- /.card -->
<script src="{{URL}}/resources/assets/admin/plugins/chart.js/Chart.min.js"></script>

<script>
  $(function() {
    function corRandom() {
      var cor = '#' + Math.floor(Math.random() * 16777215).toString(16);
      return cor;
    };
    const cores = [];
    for (var i = 0; i < 30; i++) {
      var novaCor = corRandom();
      cores.push(novaCor);
    }

    async function fetchData(complete) {
      const url = 'http://127.0.0.1/teste-controle-revisao-carros/dashboard/graficos/charts/data/' + complete;
      const response = await fetch(url);

      const datapoints = await response.json();

      return datapoints;
    };

    fetchData('marcasmaisutilizadas').then(datapoints => {
      const nome = datapoints.map(
        function(index) {
          return index.nome;
        })

      const qtd = datapoints.map(
        function(index) {
          return index.qtd;
        })

      donutMMUData.labels = nome;
      donutMMUData.datasets[0].data = qtd;
      donutMMUData.datasets[0].backgroundColor = cores;
      myDonutChartMMU.update();
    })

    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartMMUCanvas = $('#donutChartMMU').get(0).getContext('2d')
    var donutMMUData = {
      labels: [''],
      datasets: [{
        data: [0],
        backgroundColor: [''],
      }]
    }

    var donutMMUOptions = {
      maintainAspectRatio: false,
      responsive: true,
    }

    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    const myDonutChartMMU = new Chart(donutChartMMUCanvas, {
      type: 'doughnut',
      data: donutMMUData,
      options: donutMMUOptions
    })

    fetchData('marcasmaisrevisoes').then(datapoints => {
      const marca = datapoints.map(
        function(index) {
          return index.marca;
        })

      const qtd = datapoints.map(
        function(index) {
          return index.qtd;
        })

      donutMRData.labels = marca;
      donutMRData.datasets[0].data = qtd;
      donutMRData.datasets[0].backgroundColor = cores;
      myDonutChartMR.update();
    })

    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartMRCanvas = $('#donutChartMR').get(0).getContext('2d')
    var donutMRData = {
      labels: [''],
      datasets: [{
        data: [0],
        backgroundColor: [''],
      }]
    }

    var donutMROptions = {
      maintainAspectRatio: false,
      responsive: true,
    }

    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    const myDonutChartMR = new Chart(donutChartMRCanvas, {
      type: 'pie',
      data: donutMRData,
      options: donutMROptions
    })

  })
</script>

<!-- overlayScrollbars -->
<script src="{{URL}}/resources/assets/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

<!-- JQVMap -->
<script src="{{URL}}/resources/assets/admin/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="{{URL}}/resources/assets/admin/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<script src="{{URL}}/resources/assets/admin/plugins/raphael/raphael.min.js"></script>
<script src="{{URL}}/resources/assets/admin/plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="{{URL}}/resources/assets/admin/plugins/jquery-mapael/maps/usa_states.min.js"></script>

<!-- Sparkline -->
<script src="{{URL}}/resources/assets/admin/plugins/sparklines/sparkline.js"></script>
<!-- jQuery Knob Chart -->
<script src="{{URL}}/resources/assets/admin/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- SweetAlert2 -->
<script src="{{URL}}/resources/assets/admin/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Modals e Alerts -->
<script src="{{URL}}/resources/assets/js/modal_toasts"></script>

<!-- AdminLTE App -->
<script src="{{URL}}/resources/assets/admin/dist/js/adminlte.js"></script>

<!-- bs-custom-file-input -->
<script src="{{URL}}/resources/assets/admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script>
  $(function() {
    bsCustomFileInput.init();
  });
</script>