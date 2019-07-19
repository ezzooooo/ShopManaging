@extends('layouts.app')

@section('content')
<h1>고객정보 리스트</h1>
<table class="table table-bordered">
    <tr class="table-danger">
        <th>이름</th>
        <th>성별</th>
        <th>전화번호</th>
        <th>생년월일</th>
        <th>특이사항</th>
        <th>방문(예정)횟수</th>
        <th>매출</th>
    </tr>
    @foreach ($customers as $customer)
    <tr>
        <td><a href="{{route('customer.show', ['id' => $customer->id])}}">{{ $customer->name }}</a></td>
        <td>{{ $customer->gender }}</td>
        <td>{{ $customer->phone }}</td>
        <td>{{ $customer->birth }}</td>
        <td>{{ $customer->feature }}</td>
        <td>{{ $customer->visit_count }}</td>
        <td>{{ $customer->sales }}</td>
    </tr>
    @endforeach
</table>
<a class="btn btn-primary" href="{{route('customer.create')}}">추가</a>
@endsection