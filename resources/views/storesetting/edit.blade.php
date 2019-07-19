@extends('layouts.app')

@section('content')
    <div class="storesetting-box">
        <h1>매장설정</h1>
        <form action="{{route('storesetting.update', ['id' => $store_setting->id])}}" method="post">
            @csrf
            @method('PUT')
            <div class="storesetting-start-time">
                <label for="start_time">영업시작시간</label>
                <input type="number" name="start_time" id="start_time" value="{{$store_setting->start_time}}" min=0 max=24>
            </div>
            <div class="storesetting-end-time">
                <label for="end_time">영업종료시간</label>
                <input type="number" name="end_time" id="end_time" value="{{$store_setting->end_time}}" min=0 max=24>
            </div>
            <div class="storesetting-term">
                <h5>텀</h5>
                <div class="storesetting-term-list">
                    @if($store_setting->term == 0)
                    <input type="radio" name="term" id="half_hour" value=0 checked>
                    <label for="half_hour">30분</label>
                    <input type="radio" name="term" id="hour" value=1>
                    <label for="hour">1시간</label>
                    @else
                    <input type="radio" name="term" id="half_hour" value=0>
                    <label for="half_hour">30분</label>
                    <input type="radio" name="term" id="hour" value=1 checked>
                    <label for="hour">1시간</label>
                    @endif
                </div>
                <div class="storesetting submit">
                    <button>설정완료</button>
                </div>
            </form> 
    </div>
@endsection