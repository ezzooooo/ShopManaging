@extends('layouts.app')

@section('content')
<div class="sales-edit-box">
    <form action="{{route('sales.update', ['id' => $sales->id])}}" method="post">
        @csrf
        @method('PUT')
        <!--
        <div class="sales-edit-date">
            <label for="date">일자</label>
            <input type="date" name="date" id="date" value="{{Cookie::get('today')}}">
        </div>
    -->
        <div class="sales-edit-name">
            <label for="customer_name">고객이름</label>
            <input type="text" name="customer_name" id="customer_name" value="{{$sales->customer_name}}" readonly>
        </div>
        <div class="sales-edit-kind"> 
            <label for="product">시술내용</label>
            <input type="text" name="product" id="product" value="{{$sales->product}}" readonly>
        </div>
        <div class="sales-edit-payment-box">
            <div class="sales-edit-product-price">
                <label for="product_price">상품가격</label>
                <input type="number" name="product_price" id="product_price" value="{{$sales->reservations->price}}" readonly>
            </div>
            <div class="sales-edit-pre-cash">
                <label for="pre_cash">선결제금</label>
                <input type="number" name="pre_cash" id="pre_cash" value="{{$sales->pre_cash}}" readonly>
            </div>
            <div class="sales-edit-amount">
                <label for="amount">결제금액</label>
                <input type="number" name="amount" id="amount" value="{{$sales->reservations->price - $sales->pre_cash}}" readonly>
            </div>
            <div class="sales-edit-cash">
                <label for="cash">현금</label>
                <input type="number" name="cash" id="cash">
            </div>
            <div class="sales-edit-card">
                <label for="card">카드</label>
                <input type="number" name="card" id="card">
            </div>
        </div>
        <div class="sales-edit-submit">
            <button>매출등록</button>
        </div>
    </form>
</div>

<script>
$('#cash').on('keyup change', function() {
    $('#card').val($('#amount').val() - $('#cash').val());
});

$('#card').on('keyup change', function() {
    $('#cash').val($('#amount').val() - $('#card').val());
});
</script>
@endsection