@extends('layouts.app')

@section('content')
<div class="index-info">
    나의 케어 서비스를 위한 어쩌고를 위한 사이트입니다.<br>
    안녕하세요. 예약 관리 시스템입니다.
</div>
<div class="row index-box-row">
    <div class="index-box index-box-home">
        <div class="index-box-home-title">
            HOME
        </div>
    </div>
    <div class="index-box index-box-customer">
        <div class="index-box-customer-title">
            TODAY'S<br>
            CUSTOMER
        </div>
        <div class="index-box-customer-num">
            1
        </div>
    </div>
    <div class="index-box index-box-memo">
        <div class="index-box-memo-title">
            MEMO
        </div>
        <div class="index-box-memo-content">
            <div class="index-box-memo-content-row">
                1. 헤어젤 물량 추가하기
            </div>
            <div class="index-box-memo-content-row">
                2. 김지연님께 문자 보내기
            </div>
        </div>
    </div>
    <div class="index-box index-box-schedule">
        <div class="index-box-schedule-title">
            TODAY'S<br>
            SCHEDULE
        </div>
        <div class="index-box-schedule-timetable">
            여기 타임테이블
        </div>
    </div>
</div>
@endsection