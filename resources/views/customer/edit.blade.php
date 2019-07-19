@extends('layouts.app')

@section('content')
<div class="customer-edit-box">
    <form action="{{route('customer.update', ['id' => $customer->id])}}" method="post">
        @method('PUT')
        @csrf
        <div class="customer-edit-name">
            <label for="name">이름</label>
            <input type="text" name="name" id="name" value="{{$customer->name}}">
        </div>
        <div class="customer-edit-gender">
            성별
            <div class="customer-edit-gender-list">
                @if($customer->gender == "남자")
                <input type="radio" name="gender" id="gender_male" value="남자" checked>
                <label for="gender_male">남자</label>
                <input type="radio" name="gender" id="gender_female" value="여자">
                <label for="gender_female">여자</label>
                @else
                <input type="radio" name="gender" id="gender_male" value="남자">
                <label for="gender_male">남자</label>
                <input type="radio" name="gender" id="gender_female" value="여자" checked>
                <label for="gender_female">여자</label>
                @endif
            </div>
        </div>
        <div class="customer-edit-phone">
            <label for="phone">전화번호</label>
            <input type="text" name="phone" id="phone" value="{{$customer->phone}}">
        </div>
        <div class="customer-edit-birth">
            <label for="birth">생년월일</label>
            <input type="date" name="birth" id="birth" value="{{$customer->birth}}">
        </div>
        <div class="customer-edit-feature">
            <label for="feature">특이사항</label>
            <input type="text" name="feature" id="feature" value="{{$customer->feature}}">
        </div>
        <div class="customer-edit-submit">
            <button>수정하기</button>
        </div>
    </form>
</div>
@endsection