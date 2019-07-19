@extends('layouts.app')

@section('content')
<div class="sales-create-box">
    <form action="{{route('sales.store')}}" method="post">
        @csrf
        <div class="sales-create-date">
            <label for="date">일자</label>
            <input type="date" name="date" id="date">
        </div>
        <div class="sales-create-name">
            <label for="customer_name">고객이름</label>
            <input type="text" name="customer_name" id="customer_name">
        </div>
        <div class="sales-create-kind"> 
            시술내용
            <div class="sales-create-kind-list">
                <input type="radio" name="customer_kind" id="customer_kind_hairmakeup" value="헤어메이크업">헤어메이크업
                <input type="radio" name="customer_kind" id="customer_kind_hair" value="헤어">헤어
            </div>
        </div>
        <div class="sales-create-type">
            <label for="date">매출구분</label>
            <div class="sales-create-type-list">
                <input type="radio" name="sales_type" id="sales_type_card" value="카드">카드
                <input type="radio" name="sales_type" id="sales_type_cash" value="현금">현금
            </div>
        </div>
        <div class="sales-create-sale">
            <label for="date">매출액</label>
            <input type="number" name="sale" id="sale">
        </div>
        <div class="sales-create-submit">
            <button>매출등록</button>
        </div>
    </form>
</div>
@endsection