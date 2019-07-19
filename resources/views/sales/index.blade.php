@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>매출내역</h1>
        <form action="#" method="get">
        <div class="row">
            조회기간선택
            <input type="date" name="start_date" id="start_date" value="{{ $start_date }}">~
            <input type="date" name="end_date" id="end_date" value="{{ $end_date }}">
            <button type="button" id="day_before" value="{{ $day_before }}">1일</button>
            <button type="button" id="week_before" value="{{ $week_before }}">1주</button>
            <button type="button" id="half_month_before" value="{{ $half_month_before }}">15일</button>
            <button type="button" id="month_before" value="{{ $month_before }}">1개월</button>
            <button type="button" id="three_month_before" value="{{ $three_month_before }}">3개월</button>
            <button type="submit">조회</button>
        </div>
        <!--
        <div class="row">
            결제구분
            @if($sales_type == "전체")
            <input type="radio" name="sales_type" id="sales_type" value="전체" checked>전체
            <input type="radio" name="sales_type" id="sales_type" value="현금">현금
            <input type="radio" name="sales_type" id="sales_type" value="카드">카드
            @elseif($sales_type == "현금")
            <input type="radio" name="sales_type" id="sales_type" value="전체">전체
            <input type="radio" name="sales_type" id="sales_type" value="현금" checked>현금
            <input type="radio" name="sales_type" id="sales_type" value="카드">카드
            @else
            <input type="radio" name="sales_type" id="sales_type" value="전체">전체
            <input type="radio" name="sales_type" id="sales_type" value="현금">현금
            <input type="radio" name="sales_type" id="sales_type" value="카드" checked>카드
            @endif
        </div>
        -->
        </form>
        <div class="row">
            예상매출 : {{ $estimate_sales }}, 확정매출 : {{ $definite_sales }}, 총매출 : {{ $estimate_sales + $definite_sales }}
        </div>
        <table class="table table-bordered">
            <tr>
                <th>일자</th>
                <th>고객이름</th>
                <th>상품</th>
                <th>현금</th>
                <th>카드</th>
                <th>총매출</th>
                <th>상태</th>
            </tr>
            @foreach ($sales as $sale)
                <tr>
                    <td>{{ date("Y-m-d", strtotime($sale->updated_at)) }}</td>
                    <td>{{ $sale->customer_name }}</td>
                    <td>{{ $sale->product }}</td>
                    <td>{{ $sale->cash }}</td>
                    <td>{{ $sale->card }}</td>
                    <td>{{ $sale->sales }}</td>
                    <td>{{ $sale->sales_stat }}</td>
                </tr>
            @endforeach
        </table>
        <form action="{{route('sales.excel_down')}}" method="GET">
            @csrf
            <input type="hidden" name="excel_start_date" id="excel_start_date" value="{{ $start_date }}">
            <input type="hidden" name="excel_end_date" id="excel_end_date" value="{{ $end_date }}">
            <input type="hidden" name="excel_sales_type" id="excel_sales_type" value="{{ $sales_type }}">
            <!--<a href="{{route('sales.create')}}" class="btn btn-primary">매출등록</a>-->
            <button>엑셀저장</button>
        </form>
    </div>

    <script>
        $(function() {
            $("#day_before").on("click", function () {
                $("#start_date").val($("#day_before").val());
                $("#end_date").val($("#day_before").val());
            });
            $("#week_before").on("click", function () {
                $("#start_date").val($("#week_before").val());
                $("#end_date").val($("#day_before").val());
            });
            $("#half_month_before").on("click", function () {
                $("#start_date").val($("#half_month_before").val());
                $("#end_date").val($("#day_before").val());
            });
            $("#month_before").on("click", function () {
                $("#start_date").val($("#month_before").val());
                $("#end_date").val($("#day_before").val());
            });
            $("#three_month_before").on("click", function () {
                $("#start_date").val($("#three_month_before").val());
                $("#end_date").val($("#day_before").val());
            });
        });
    </script>
@endsection