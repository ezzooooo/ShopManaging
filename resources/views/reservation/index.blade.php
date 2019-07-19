@extends('layouts.app')

@section('content')
@php
    Cookie::queue('date', Cookie::get('today'));
@endphp
<div class="container">
    <h1>예약신청 리스트</h1>
        <div class="row">
            <div style="float:left" class="col-md-10">
            <form action="{{route('reservations.search')}}">
                @if($stat == "전체")
                <label for="reservation_stat_whole">전체</label>
                <input type="radio" name="reservation_stat" id="reservation_stat_whole" value="전체" checked>
                <label for="reservation_stat_confirm">예약확인 대기</label>
                <input type="radio" name="reservation_stat" id="reservation_stat_confirm" value="예약확인 대기">
                <label for="reservation_stat_confirm">예약확정 대기</label>
                <input type="radio" name="reservation_stat" id="reservation_stat_confirm" value="예약확정 대기">
                <label for="reservation_stat_confirm">예약확정</label>
                <input type="radio" name="reservation_stat" id="reservation_stat_confirm" value="예약확정">
                @elseif($stat == "예약확인 대기")
                <label for="reservation_stat_whole">전체</label>
                <input type="radio" name="reservation_stat" id="reservation_stat_whole" value="전체">
                <label for="reservation_stat_confirm">예약확인 대기</label>
                <input type="radio" name="reservation_stat" id="reservation_stat_confirm" value="예약확인 대기" checked>
                <label for="reservation_stat_confirm">예약확정 대기</label>
                <input type="radio" name="reservation_stat" id="reservation_stat_confirm" value="예약확정 대기">
                <label for="reservation_stat_confirm">예약확정</label>
                <input type="radio" name="reservation_stat" id="reservation_stat_confirm" value="예약확정">
                @elseif($stat == "예약확정 대기")
                <label for="reservation_stat_whole">전체</label>
                <input type="radio" name="reservation_stat" id="reservation_stat_whole" value="전체" >
                <label for="reservation_stat_confirm">예약확인 대기</label>
                <input type="radio" name="reservation_stat" id="reservation_stat_confirm" value="예약확인 대기">
                <label for="reservation_stat_confirm">예약확정 대기</label>
                <input type="radio" name="reservation_stat" id="reservation_stat_confirm" value="예약확정 대기" checked>
                <label for="reservation_stat_confirm">예약확정</label>
                <input type="radio" name="reservation_stat" id="reservation_stat_confirm" value="예약확정">
                @else
                <label for="reservation_stat_whole">전체</label>
                <input type="radio" name="reservation_stat" id="reservation_stat_whole" value="전체" >
                <label for="reservation_stat_confirm">예약확인 대기</label>
                <input type="radio" name="reservation_stat" id="reservation_stat_confirm" value="예약확인 대기">
                <label for="reservation_stat_confirm">예약확정 대기</label>
                <input type="radio" name="reservation_stat" id="reservation_stat_confirm" value="예약확정 대기">
                <label for="reservation_stat_confirm">예약확정</label>
                <input type="radio" name="reservation_stat" id="reservation_stat_confirm" value="예약확정" checked>
                @endif
                <input type="search" name="reservation_name" id="reservation_name" value="{{$name}}">
                <button>검색</button>
            </form>
        </div>
        <div style="float:left" class="col-md-2">
            <button onclick="excel_down();">전체 예약 엑셀저장</button>
        </div>
    </div>
    <br>
    <table class="table table-bordered">
        <tr>
            <th>예약번호</th>
            <th>메모</th>
            <th>고객명</th>
            <th>예약등록시간</th>
            <th>상태</th>
        </tr>
        @foreach ($items as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td><a href="{{route('reservations.show', ['id'=>$item->id])}}">{{ $item->memo }}</a></td>
            <td>{{ $item->customer->name }}</td>
            <td>{{ date("Y-m-d H:i:s", strtotime("$item->created_at")) }}</td>
            <td>{{ $item->stat }}</td>
        </tr>
        @endforeach
    </table>
    {{ $items->links() }}    

    <script>
        function excel_down() {
            location.href="reservations_excel_down";
        }
    </script>
</div>
@endsection