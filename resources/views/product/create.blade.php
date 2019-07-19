@extends('layouts.app')

@section('content')
    <div class="product-create-box">
        <form action="{{route('product.store')}}" method="post">
            @csrf
            <div class="product-create-name">
                <label for="name">상품명</label>
                <input type="text" name="name" id="name">
            </div>
            <div class="product-create-eng-name">
                <label for="name">상품명(영어)</label>
                <input type="text" name="eng_name" id="eng_name">
            </div>
            <div class="product-create-price">
                <label for="price">가격</label>
                <input type="number" name="price" id="price">
            </div>
            <div class="product-create-exp">
                <label for="exp">기타정보</label>
                <input type="text" name="exp" id="exp">
            </div>
            <div class="product-create-submit">
                <button class="btn btn-primary">상품등록</button>
            </div>
        </form>
    </div>
@endsection