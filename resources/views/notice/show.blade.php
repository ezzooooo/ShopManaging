@extends('layouts.app')

@section('content')
    <div class="notice-show-box">
        <div class="notice-title row">
            <strong>{{$notice->title}}</strong>
        </div>
        <hr>
        <div class="notice-content">
            {{$notice->content}}
        </div>
    </div>
    @if($notice->store_id == session('user_id'))
    <div class="row">
        <a class="btn btn-primary" href="{{route('notice.edit', ['id' => $notice->id])}}">수정</a>
        <form action="{{route('notice.destroy', ['id' => $notice->id])}}" method="post">
            @method('DELETE')
            @csrf
            <button class="btn btn-primary">삭제</button>
        </form>
    </div>
    @endif
@endsection