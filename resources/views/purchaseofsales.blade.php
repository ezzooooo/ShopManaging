@extends('layouts.app')

@section('content')
    <input type="hidden" name="Month" id="Month" value="{{$Month}}">
    <input type="hidden" name="Month_sales" id="Month_sales" value="{{$Month_sales}}">
    <input type="hidden" name="Month_purchase" id="Month_purchase" value="{{$Month_purchase}}">
    <input type="hidden" name="Month_profit" id="Month_profit" value="{{$Month_profit}}">

    <div class="container">
        <div class="row">
            <button onclick="month_chart();">월별</button>
            <button onclick="quarter_chart()">분기별</button>
        </div>
        <div id="columnchart_material" style="width: 800px; height: 500px; margin:auto;"></div>
        <div id="purchaseofsales_info">
            <div class="col-sm-4 border-right" style="float: left" id="under_chart_sales"></div>
            <div class="col-sm-4 border-right" style="float: left" id="under_chart_purchase"></div>
            <div class="col-sm-4" style="float: left" id="under_chart_profit"></div>
        </div>
    </div>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        var Month = JSON.parse( $("#Month").val());
        var Month_sales = JSON.parse( $("#Month_sales").val());
        var Month_purchase = JSON.parse( $("#Month_purchase").val());
        var Month_profit = JSON.parse( $("#Month_profit").val());

        google.charts.load('current', {'packages':['corechart']});

        month_chart();

        function month_chart() {
            google.charts.setOnLoadCallback(month_drawChart);   // 차트 그리기

            //차트 밑 정보 출력부분
            var whole_sales = 0;
            var whole_purchase = 0;    
            var whole_profit = 0;   

            for(var i=0; i<4; i++) {
                whole_sales += Month_sales[i];
                whole_purchase += Month_purchase[i];
                whole_profit += Month_profit[i];
            }

            var under_chart_sales = $("#under_chart_sales").text("총 매출액 : " + whole_sales + "원");
            var under_chart_purchase = $("#under_chart_purchase").text("총 매입액 : " + whole_purchase + "원");
            var under_chart_profit = $("#under_chart_profit").text("총 이익 : " + whole_profit + "원");
        }
        function quarter_chart() {
            google.charts.setOnLoadCallback(quarter_drawChart);

            //차트 밑 정보 출력부분
            var whole_sales = 0;
            var whole_purchase = 0;    
            var whole_profit = 0;   

            for(var i=0; i<12; i++) {
                whole_sales += Month_sales[i];
                whole_purchase += Month_purchase[i];
                whole_profit += Month_profit[i];
            }

            var under_chart_sales = $("#under_chart_sales").text("총 매출액 : " + whole_sales + "원");
            var under_chart_purchase = $("#under_chart_purchase").text("총 매입액 : " + whole_purchase + "원");
            var under_chart_profit = $("#under_chart_profit").text("총 이익 : " + whole_profit + "원");
        }

        function month_drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['월', '매출', '매입', '순이익'],
          [Month[3], Month_sales[3], Month_purchase[3], Month_profit[3]],
          [Month[2], Month_sales[2], Month_purchase[2], Month_profit[2]],
          [Month[1], Month_sales[1], Month_purchase[1], Month_profit[1]],
          [Month[0], Month_sales[0], Month_purchase[0], Month_profit[0]],
        ]);

        var options = {
          title: "매출 매입 현황 : " + Month[3] + " ~ " + Month[0],
          bars: 'vertical',
          vAxis: {format: 'decimal'},
          height: 400,
          seriesType: 'bars',
        };

        var chart = new google.visualization.ComboChart(document.getElementById('columnchart_material'));

        chart.draw(data, options);
        }

        function quarter_drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['분기', '매출', '매입', '순이익'],

          [Month[11] + ' ~ ' + Month[9], Month_sales[9]+Month_sales[10]+Month_sales[11], 
          Month_purchase[9]+Month_purchase[10]+Month_purchase[11], Month_profit[9]+Month_profit[10]+Month_profit[11]],

          [Month[8] + ' ~ ' + Month[6], Month_sales[6]+Month_sales[7]+Month_sales[8], 
          Month_purchase[6]+Month_purchase[7]+Month_purchase[8], Month_profit[6]+Month_profit[7]+Month_profit[8]],

          [Month[5] + ' ~ ' + Month[3], Month_sales[3]+Month_sales[4]+Month_sales[5], 
          Month_purchase[3]+Month_purchase[4]+Month_purchase[5], Month_profit[3]+Month_profit[4]+Month_profit[5]],

          [Month[2] + ' ~ ' + Month[0], Month_sales[0]+Month_sales[1]+Month_sales[2], 
          Month_purchase[0]+Month_purchase[1]+Month_purchase[2], Month_profit[0]+Month_profit[1]+Month_profit[2]],
        ]);

        var options = {
          title: "매출 매입 현황 : " + Month[11] + " ~ " + Month[0],
          bars: 'vertical',
          vAxis: {format: 'decimal'},
          height: 400,
          seriesType: 'bars',
        };

        var chart = new google.visualization.ComboChart(document.getElementById('columnchart_material'));

        chart.draw(data, options);
        }

        //차트 밑 정보나오는 부분
        
    </script>
@endsection