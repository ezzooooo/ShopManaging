@extends('layouts.app')

@section('content')
<h1>내 상품 리스트</h1>
<ul>
    @foreach ($products as $product)
    <li title="{{$product->exp}}">{{$product->name}}, {{$product->price}}원</li>
    @endforeach
</ul>
<a class="btn btn-primary" href="{{route('product.create')}}">상품등록</a>
@endsection