@extends('layouts.app')

@section('content')
@foreach ($errors->all() as $item)
    echo {{ $item }}
@endforeach

<div class="container" style="background-color:white;">
    <div>
    <h1>DETAILS INFORMATION</h1>
    </div>
    <div class="form">
        <div class="col-md-3 info_add_side">
            <div class="row side_row">매장명</div>
            <div class="row side_row">DATE</div>
            <div class="row side_row">TIME</div>
            <div class="row side_row">NAME</div>
            <div class="row side_row">GENDER</div>
            <div class="row side_row">PHONE NUMBER</div>
            <div class="row side_row">product</div>
            <div class="row side_row">PURPOSE</div>
            <div class="row side_row">결제방법</div>
            <div class="row side_row">VISITING STATUS</div>
            <div class="is_first"></div>
            <div class="row side_row">SPECIAL NOTE</div> 
        </div>
        <div class="col-md-8 info_add_content">
            <form action="{{ route('reservations.store') }}" method="post">
            @csrf
            <div class="row content_row">
                @auth
                    <input type="search" name="store" id="store" value="{{ $store_name }}" disabled>
                    <input type="hidden" name="store_id" id="store_id" value="{{ $store_id }}">
            </div>
                @else
                    @if($store_id != 0)
                        <input type="search" name="store" id="store" value="{{ $store_name }}" disabled>
                        <input type="hidden" name="store_id" id="store_id" value="{{ $store_id }}">
                    @else
                        <input type="search" name="store" id="store" disabled>
                        <input type="hidden" name="store_id" id="store_id">
                        <button type="button" id="store_search_btn" onclick="store_search_popup();">검색</button>
                    @endif
                </div>
                    <div name="notice_box" id="notice_box">
                        <h5>공지사항</h5>
                        <input type="hidden" name="notices" value="{{$notices}}">
                        <ul name="notice_list" id="notice_list">
                            @foreach ($notices as $notice)
                                <li>{{$notice->title}}, {{$notice->content}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endauth
            @if(Cookie::get('date'))
            <div class="row content_row"><input id="date" type="date" name="date" value="{{Cookie::get('date')}}" required></div>
            @else
            <div class="row content_row"><input id="date" type="date" name="date" value="{{Cookie::get('today')}}" required></div>
            @endif
            <div class="row content_row">
                <input type="time" name="start_time" value="{{$start_time < 10 ? "0" . $start_time : $start_time}}:00" min="{{$store_setting->start_time < 10 ? "0" . $store_setting->start_time : $store_setting->start_time}}:00" max="{{ ($store_setting->end_time-1) < 10 ? "0" . ($store_setting->end_time -1) : ($store_setting->end_time -1)}}:00" step="1800" required>&nbsp;~&nbsp;
                <input type="time" name="end_time" value="{{$end_time < 10 ? "0" . $end_time : $end_time}}:00" min="{{ ($store_setting->start_time+1) < 10 ? "0" . ($store_setting->start_time+1) : ($store_setting->start_time+1)}}:00" max="{{ ($store_setting->end_time) < 10 ? "0" . ($store_setting->end_time) : ($store_setting->end_time)}}:00" step="1800" required>
            </div>
            
            <div class="row content_row"><input type="text" name="name" required></div>
            <div class="row content_row">
                <input type="radio" name="gender" id="gender" value="여자" required>여자
                <input type="radio" name="gender" id="gender" value="남자" required>남자
            </div>
            <div class="row content_row"><input type="phone" name="phone" placeholder="010-1234-5678" required></div>
            <div class="rs-product-box">
                <div class="row content_row">
                    <input type="number" name="price" id="price" readonly>
                </div>
                <div id="rs-product-list" class="rs-product-list">
                @if($store_id != 0)
                    @foreach ($products as $product)
                    <input type="radio" class="product" name="product" id="product_{{$product->eng_name}}" value="{{$product->name}}" 
                            title="{{$product->exp}}" price="{{$product->price}}">
                    <label for="product_{{$product->eng_name}}" title="{{$product->exp}}">{{$product->name}}</label>
                    @endforeach
                @else
                    
                @endif
                </div>
            </div>
            <div class="row content_row"><input type="text" name="purpose" placeholder="면접, 웨딩 등" required></div>
            <div class="row content_row">
                <input type="radio" name="is_revisit" id="first_visit" value="첫방문" required>첫방문
                <input type="radio" name="is_revisit" id="re_visit" value="재방문" required>재방문
            </div>
            <div class="is_first">
                <div class="row content_row">*특이사항</div>
                <div class="row content_row">
                    <input type="text" name="feature" id="feature" placeholder="마스크팩을 싫어함">
                </div>
                <div class="row content_row">*생년월일</div>
                <div class="row content_row"><input type="date" name="birth" min="1900-01-01" max="2019-12-31"></div>
            </div>
            <div class="row content_row"><textarea name="memo" id="memo" cols="30" rows="10" required></textarea></div>
            <div class="row content_row"><button type="submit">예약 추가</button></div>            
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var notices = $('#notices').text();

    //입력폼 첫방문, 재방문 클릭 시 show, hide
    $("#first_visit").on("click", function () {
        $(".is_first").show();
    });

    $("#re_visit").on("click", function () {
        $(".is_first").hide();
    });

    //재방문 체크되어있으면 첫방문 입력폼 가리기
    if($("#re_visit").attr("checked") == "checked") {
        $(".is_first").hide();
    }

    //예약종류 선택 시 예약금 설정
    $(document.body).on('click', '.product', function () {
    $('#price').val($(this).attr('price'));
    });
});

/*
function store_search_popup() {
    window.open("/store_search", "매장검색", "width=400, height=300, left=100, top=50");
}*/

</script>

@endsection

