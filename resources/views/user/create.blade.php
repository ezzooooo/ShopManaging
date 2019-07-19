@extends('layouts.app')

@section('content')
    <h1>직원등록</h1>
    <div class="worker-create-box">
        <form action="{{route('user.store')}}" method="post">
            @csrf
            <div class="worker-create-name">
                <label for="name">이름</label>
                <input type="text" name="name" id="name">
            </div>
            <div class="worker-create-phone">
                <label for="phone">연락처</label>
                <input type="text" name="phone" id="phone">
            </div>
            <div class="worker-create-email">
                <label for="email">이메일(ID)</label>
                <input type="email" name="email" id="email">
            </div>
            <div class="worker-create-password">
                <label for="password">비밀번호</label>
                <input type="password" name="password" id="password">
            </div>
            <div class="worker-create-submit">
                <button>등록</button>
            </div>
        </form>
    </div>
@endsection