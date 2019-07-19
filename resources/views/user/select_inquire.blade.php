@extends('layouts.app')

@section('content')
    <a href="{{route('user.inquire_email')}}">아이디 찾기</a><br>
    <a href="{{route('password.request')}}">비밀번호 찾기</a>
@endsection