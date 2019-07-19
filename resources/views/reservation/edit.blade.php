@extends('layouts.app')

@section('content')
@foreach ($errors->all() as $error)
    echo {{ $error }}
@endforeach

<div class="container" style="background-color:white;">
    <div>
    <h1>DETAILS INFORMATION</h1>
    </div>
    <div class="form">
        <div class="col-md-3 info_add_side">
            <div class="row side_row">DATE</div>
            <div class="row side_row">TIME</div>
            <div class="row side_row">NAME</div>
            <div class="row side_row">GENDER</div>
            <div class="row side_row">PHONE NUMBER</div>
            <div class="row side_row">product</div>
            <div class="row side_row">PURPOSE</div>
            <div class="row side_row">VISITING STATUS</div>
            <div class="is_first"></div>
            <div class="row side_row">SPECIAL NOTE</div>
            @if($item->stat="예약확정")
            <div class="row side_row">예약금</div>
            @endif
        </div>
        <div class="col-md-8 info_add_content">
           <form action="{{ route('reservations.update', ['id'=>$item->id]) }}" method="post">
            @csrf
            @method("PUT")
            <div class="row content_row">
                <input type="search" name="store" id="store" value="{{ $store_name }}" disabled>
                <input type="hidden" name="store_id" id="store_id" value="{{ $item->store_id }}">
            </div>
            <div class="row content_row"><input id="edit_date" type="date" name="date" value="{{$item->date}}" required></div>
            <div class="row content_row">
                <input type="time" name="start_time" value="{{$item->start_time}}" min="{{$store_setting->start_time < 10 ? "0" . $store_setting->start_time : $store_setting->start_time}}:00" max="{{ ($store_setting->end_time-1) < 10 ? "0" . ($store_setting->end_time -1) : ($store_setting->end_time -1)}}:00" step="1800" required>&nbsp;~&nbsp;
                <input type="time" name="end_time" value="{{$item->end_time}}" min="{{ ($store_setting->start_time+1) < 10 ? "0" . ($store_setting->start_time+1) : ($store_setting->start_time+1)}}:00" max="{{ ($store_setting->end_time) < 10 ? "0" . ($store_setting->end_time) : ($store_setting->end_time)}}:00" step="1800" required>
            </div>
            
           <div class="row content_row"><input type="text" name="name" value="{{$item->customer->name}}" required></div>
           <div class="row content_row">
               @if($item->customer->gender == "여자")
                <input type="radio" name="gender" id="gender_female" value="여자" checked required>여자
                <input type="radio" name="gender" id="gender_male" value="남자" required>남자
                @else
                <input type="radio" name="gender" id="gender_female" value="여자" required>여자
                <input type="radio" name="gender" id="gender_male" value="남자" checked required>남자
                @endif
           </div>
           <div class="row content_row"><input type="phone" name="phone" placeholder="010-1234-5678" value="{{$item->customer-> phone}}" required></div>
           <div class="rs-product-box">
                <div class="row content_row">
                    <input type="number" name="price" id="price" value="{{$item->price}}" required readonly>
                </div>
                <div id="rs-product-list" class="rs-product-list">
                @if($store_id != 0)
                    @foreach ($products as $product)
                    @if($product->name == $item->product)
                    <input type="radio" class="product" name="product" id="product_{{$product->eng_name}}" value="{{$product->name}}" 
                            title="{{$product->exp}}" price="{{$product->price}}" checked>
                    @else
                    <input type="radio" class="product" name="product" id="product_{{$product->eng_name}}" value="{{$product->name}}" 
                            title="{{$product->exp}}" price="{{$product->price}}">
                    @endif
                    <label for="product_{{$product->eng_name}}" title="{{$product->exp}}">{{$product->name}}</label>
                    @endforeach
                @else
                    
                @endif
                </div>
           </div>
           <div class="row content_row"><input type="text" name="purpose" placeholder="면접, 웨딩 등" value="{{$item->purpose}}" required></div>
           <div class="row content_row">
               @if($item->is_revisit == "첫방문")
                <input type="radio" name="is_revisit" id="first_visit" value="첫방문" checked required>첫방문
                <input type="radio" name="is_revisit" id="re_visit" value="재방문" required>재방문
                @else
                <input type="radio" name="is_revisit" id="first_visit" value="첫방문" required>첫방문
                <input type="radio" name="is_revisit" id="re_visit" value="재방문" checked required>재방문
                @endif
           </div>
           <div class="is_first">
                <div class="row content_row">*특이사항</div>
                <div class="row content_row">
                    <input type="text" name="feature" id="feature" placeholder="마스크팩을 싫어함" value="{{$item->customer->feature}}">
                </div>
                <div class="row content_row">*생년월일</div>
                <div class="row content_row"><input type="date" name="birth" min="1900-01-01" max="2019-12-31" value={{$item->customer->birth}}></div>
           </div>
           <div class="row content_row"><textarea name="memo" id="memo" cols="30" rows="10" required>{{$item->memo}}</textarea></div>
           <input type="hidden" name="stat" id="stat" value="{{$item->stat}}">
           <div class="row content_row"><button type="submit">수정하기</button></div>            
           </form>
        </div>
    </div>
</div>

<script src="/js/reservation.js"></script>
<script>
//예약종류 선택 시 예약금 설정
$(document.body).on('click', '.product', function () {
   $('#price').val($(this).attr('price'));
});
</script>
@endsection