@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>매입내역</h1>
        <a href="{{ route('purchase.create') }}" class="btn btn-primary">추가</a>
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
        </form>
        <div class="row">
            매입액 : {{ $purchase_price }}
        </div>
        <table class="table table-bordered">
            <tr>
                <th>일자</th>
                <th>매입처</th>
                <th>상품</th>
                <th>수량</th>
                <th>단가</th>
                <th>가격</th>
                <th>메모</th>
            </tr>
            @foreach ($purchases as $purchase)
                <tr>
                    <td>{{$purchase->date}}</td>
                    <td>{{$purchase->partner}}</td>
                    <td>{{$purchase->content}}</td>
                    <td>{{$purchase->quantity}}</td>
                    <td>{{$purchase->unit_price}}</td>
                    <td>{{$purchase->price}}</td>
                    <td>{{$purchase->memo}}</td>
                </tr>
            @endforeach
        </table>
        <form action="{{route('purchase.excel_down')}}" method="GET">
            @csrf
            <input type="hidden" name="excel_start_date" id="excel_start_date" value="{{ $start_date }}">
            <input type="hidden" name="excel_end_date" id="excel_end_date" value="{{ $end_date }}">
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