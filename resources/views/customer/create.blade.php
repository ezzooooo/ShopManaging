@extends('layouts.app')

@section('content')
<div class="customer-create-box">
    <form action="{{route('customer.store')}}" method="post">
        @csrf
        <div class="customer-create-name">
            <label for="name">이름</label>
            <input type="text" name="name" id="name">
        </div>
        <div class="customer-create-gender">
            성별
            <div class="customer-create-gender-list">
                <input type="radio" name="gender" id="gender_male" value="남자">
                <label for="gender_male">남자</label>
                <input type="radio" name="gender" id="gender_female" value="여자">
                <label for="gender_female">여자</label>
            </div>
        </div>
        <div class="customer-create-phone">
            <label for="phone">전화번호</label>
            <input type="text" name="phone" id="phone">
        </div>
        <div class="customer-create-birth">
            <label for="birth">생년월일</label>
            <input type="date" name="birth" id="birth">
        </div>
        <div class="customer-create-feature">
            <label for="feature">특이사항</label>
            <input type="text" name="feature" id="feature">
        </div>
        <div class="customer-create-submit">
            <button>등록</button>
        </div>
    </form>
</div>
    
@endsection