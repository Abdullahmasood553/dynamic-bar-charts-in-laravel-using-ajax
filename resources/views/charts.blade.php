<html>

<head>
    <title>Dynamic Column Chart in Laravel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="http://code.jquery.com/jquery-3.4.1.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

</head>

<body>
    <header class="text-center p-4 text-white bg-dark"><h1>Dynamic Bar Charts Using AJAX</h1> </header>
    <div class="container mt-4">

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-9">
                        <h3 class="card-title ">Month Wise Profit Data</h3>
                    </div>
                    <div class="col-md-3">
                        <select name="year" id="year" class="form-control">
                            <option value="">Select Year</option>
                            @foreach($year_list as $row)
                            <option value="{{ $row->year }}">{{ $row->year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div id="chart_area" style="width: 700px; height: 400px;"></div>
            </div>
        </div>
    </div>
</body>

</html>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {
        packages: ['corechart', 'bar']
    });
    google.charts.setOnLoadCallback();



    function load_monthwise_data(year, title) {
        var temp_title = title + ' ' + year;
        $.ajax({
            url: "dynamic_chart/fetch_data",
            method: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                year: year
            },
            dataType: "JSON",
            success: function (data) {
                drawMonthwiseChart(data, temp_title);
            }
        })
    }

    function drawMonthwiseChart(chart_data, chart_main_title) {
    
        var jsonData = chart_data;
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Month');
        data.addColumn('number', 'Profit');

        $.each(jsonData, function (i, jsonData) {
            var month = jsonData.month;
            console.log(`Month ${month}`);
            var profit = parseFloat($.trim(jsonData.profit));
            data.addRows([
                [month, profit]
            ]);
            console.log(`Profit ${profit}`);
        });

        var options = {
            title: chart_main_title,
            //    bar: { groupWidth: '100%' }, // Remove space between bars.
            hAxis: {
                title: "Months"
            },
            colors: ['black'],
            is3D: true,
            vAxis: {
                title: 'Profit'
            },

            chartArea: {
                width: '90%',
                height: '85%'
            }
        }

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_area'));
        chart.draw(data, options);

    }

</script>

<script>
    $(document).ready(function () {
        $('#year').change(function () {
            var year = $(this).val();
            if (year != '') {
                load_monthwise_data(year, 'Month Wise Profit Data For');
            }
        });
    });

</script>
