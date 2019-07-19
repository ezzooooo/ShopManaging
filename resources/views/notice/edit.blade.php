@extends('layouts.app')

@section('content')
@if($notice->store_id == session('user_id'))
    <div>
        <form action="{{ route('notice.update', ['id'=>$notice->id]) }}" method="post">
            @method('PUT')
            @csrf
            <div class="row">
                <label for="title">제목</label>
                <input type="text" name="title" id="title" value="{{$notice->title}}">
            </div>
            <div class="row">
                <label for="content">내용</label>
                <textarea name="content" id="content">{{$notice->content}}</textarea>
            </div>
            <div class="row">
                <button>수정</button>
            </div>
        </form>
    </div>
@else
<h1>본인거만 수정하세용 ㅜㅜ</h1>
@endif
@endsection