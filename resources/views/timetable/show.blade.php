@extends('layouts.app')

@section('content')
<div class="container">
    @php
        $yesterday = strtotime("-1 days", strtotime($date));
        $tomorrow = strtotime("+1 days", strtotime($date));
        Cookie::queue('date', date("Y-m-d", strtotime($date)));
    @endphp
    
    <div class="row">
        <a href="{{ date("Ymd",$yesterday) }}"><button>1일전</button></a>
        <h1>{{ date("Y년 m월 d일",strtotime($date)) }}</h1>
        <a href="{{ date("Ymd", $tomorrow) }}"><button>1일후</button></a>
    </div>
    <div class="row">오늘의 예약손님 : {{ count($all_items)-count($wait_items) }}명</div>
    <div class="row">예약확정 대기 중 : {{ count($wait_items) }}명</div>
    <div class="row"><h1>예약 타임 테이블</h1></div>
    <div class="">
        <table class="table table-bordered time-table">
            @php ($i = 0)
            @foreach ($all_items as $item)
            @while ($i < ($store_setting->end_time - $store_setting->start_time) * 2 + 1) 
                <tr>
                    <td>{{ ($store_setting->start_time+floor($i/2))}} : {{$i%2 == 1 ? "30" : "00"}}</td>
                    @if( ( ($store_setting->start_time+floor($i/2)) < 10 ? "0" . ($store_setting->start_time+ floor($i/2)) : ($store_setting->start_time+ floor($i/2)) ) . ":" . ($i%2 ? "30" : "00"). ":00" == $item->start_time)
                    <td class="table-dark time-table-col time-content" id="time-content-{{$i}}" rowspan="1">
                        <a href="/reservations/{{$item->id}}">
                            <div class="full-cell already-reserve">
                                <h1>{{$item->customer->name}}님</h1>
                                <ul>
                                    <li>시술예약 : {{$item->product}}</li>
                                    <li>예약일시 : {{$item->date}}</li>
                                    <li>연락처 : {{$item->customer->phone}}</li>
                                    <li>성별 : {{$item->customer->gender}}</li>
                                    <li>용도 : {{$item->purpose}}</li>
                                </ul>
                            </div>
                        </a>
                    </td>
                </tr>
                @while ( ( ($store_setting->start_time+floor($i/2)) < 10 ? "0" . ($store_setting->start_time+ floor($i/2)) : ($store_setting->start_time+ floor($i/2)) ) . ":" . ($i%2 ? "30" : "00"). ":00" != $item->end_time)
                @php ($i++)
                <tr>
                    <td>{{$store_setting->start_time+floor($i/2)}} : {{$i%2 == 1 ? "30" : "00"}}</td>
                    <td class="table-dark time-table-col time-content" id="time-content-{{$i}}" rowspan="1">
                        <a href="/reservations/{{$item->id}}">
                            <div class="full-cell already-reserve">
                                <h1>{{$item->customer->name}}님</h1>
                                <ul>
                                    <li>시술예약 : {{$item->product}}</li>
                                    <li>예약일시 : {{$item->date}}</li>
                                    <li>연락처 : {{$item->customer->phone}}</li>
                                    <li>성별 : {{$item->customer->gender}}</li>
                                    <li>용도 : {{$item->purpose}}</li>
                                </ul>
                            </div>
                        </a>
                    </td>
                </tr>
                @endwhile
                @php ($i++)
                @break
                @else
                <td class="table-primary time-table-col yet-reserve" id="yet-time-content-{{$i}}">
                    <a class="" id="">
                        <div class="full-cell">
                            시간선택
                        </div>
                    </a>
                </td>
                </tr>
                @php ($i++)
                @endif
                @endwhile
                @endforeach
                @while($i < ($store_setting->end_time - $store_setting->start_time) * 2 + 1) 
                <tr>
                    <td>{{$store_setting->start_time+floor($i/2)}} : {{$i%2 == 1 ? "30" : "00"}}</td>
                    <td class="table-primary time-table-col yet-reserve" id="yet-time-content-{{$i}}">
                        <a class="" id="">
                            <div class="full-cell">
                                시간선택
                            </div>
                        </a>
                    </td>
                </tr>
                @php ($i++)
                @endwhile
        </table>
    </div>
    <form id="start_reserve_form" action="{{route('reservations.create')}}">
        <button type="button" id="reserve-btn" onclick="start_reserve();" class="btn btn-primary">예약하기</button>
        <input id="reserve-times" type="hidden" name="times" value="">
    </form>
    
</div>
<script src="/js/timetable.js"></script>
<script>
var times = [];
var start_time = 0;
var end_time = 19;

$('.yet-reserve').on('click', function() {
    var i = parseInt(this.id.replace('yet-time-content-',""));

    if($(this).hasClass('selected-time-content')) {
        if(i > start_time && i < end_time) {
            alert('중간에 있는 시간은 해제할 수 없습니다.');
            return;
        } else {
            if(i == start_time) {
                start_time++;
            } else if(i == end_time) {
                end_time--;
            }
            $(this).removeClass('selected-time-content');
            var index = times.indexOf(i);
            times.splice(index,1);
            if(times.length == 0) {
                start_time = 0;
                end_time = 19;
            }
        }
    } else {
        if(start_time == 0 && end_time == 19) {
            start_time = i;
            end_time = i;
        } else {
            if( start_time - i == 1 ) {
                start_time = i;
            } else if( i - end_time == 1) {
                end_time = i;
            } else {
                alert('30분 단위로 선택해주세요.');
                return;
            }
        }

        $(this).addClass('selected-time-content');
        times.push(i);
    }
    $('#reserve-times').val(times);
    console.log(times, start_time, end_time);
});

function start_reserve() {
    if(times.length <= 1) {
        alert('30분 이상 선택을 해주세요.');
        return;
    }
    start_reserve_form.submit();
}
</script>

<style>
.selected-time-content {
    background-color: #ff0000;
}
</style>
@endsection
