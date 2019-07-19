@extends('layouts.app')

@section('content')
    <div>
        <form action="{{ route('notice.store') }}" method="post">
            @csrf
            <div class="row">
                <label for="title">제목</label>
                <input type="text" name="title" id="title">
            </div>
            <div class="row">
                <label for="content">내용</label>
                <textarea name="content" id="content"></textarea>
            </div>
            <div class="row">
                <button>등록</button>
            </div>
        </form>
    </div>
@endsection