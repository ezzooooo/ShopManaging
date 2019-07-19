@extends('layouts.app')

@section('content')
    <div class="customer-show-box">
        <div class="customer-show-name">
            {{$customer->name}} 고객님
        </div>
        <div class="customer-show-gender">
            성별 : {{$customer->gender}}
        </div>
        <div class="customer-show-phone">
            연락처 : {{$customer->phone}}
        </div>
        <div class="customer-show-birth">
            생년월일 : {{$customer->birth}}
        </div>
        <div class="customer-show-feature">
            특이사항 : {{$customer->feature}}
        </div>
        <div class="customer-show-visit-count">
            방문횟수 : {{$customer->visit_count}}
        </div>
        <div class="customer-show-sales">
            총결제금액 : {{$customer->sales}}
        </div>
        <div class="customer-edit-button">
            <a class="btn btn-primary" href="{{route('customer.edit', ['id' => $customer->id])}}">수정</a>
        </div>
    </div>
    <div class="customer-usage-history-box">
        <table class="customer-usage-history-table table table-bordered">
            <tr>
                <th>예약상태</th>
                <th>예약일</th>
                <th>예약시간</th>
                <th>예약종류</th>
                <th>용도</th>
                <th>결제방법</th>
                <th>결제금액</th>
                <th>메모</th>
            </tr>
            @foreach ($reservations as $rs)
                <tr>
                    <td>{{$rs->stat}}</td>
                    <td>{{$rs->date}}</td>
                    <td>{{$rs->start_time}} ~ {{$rs->end_time}}</td>
                    <td>{{$rs->kind}}</td>
                    <td>{{$rs->purpose}}</td>
                    <td>{{$rs->sales_type}}</td>
                    <td>{{$rs->deposit}}</td>
                    <td>{{$rs->memo}}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection