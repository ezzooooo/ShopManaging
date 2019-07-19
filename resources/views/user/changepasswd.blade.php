@extends('layouts.app')

@section('content')
    <div class="container">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <h1>비밀번호 변경</h1>
        <form action="{{ route('user.changepasswdcheck') }}" method="post">
        @csrf
            <input type="password" name="cur_passwd" id="cur_passwd" placeholder="현재 비밀번호"><br><br>
            <input type="password" name="new_passwd" id="new_passwd" placeholder="새 비밀번호"><br>
            <input type="password" name="new_passwd_confirmation" id="new_passwd_confirm" placeholder="새 비밀번호 확인"><br><br>
            <button type="submit">확인</button><br>
            <button type="reset">취소</button>
        </form>
    </div>
@endsection