@extends('layouts.app')

@section('content')
<div class="whole-notice">
<h1>전체 공지사항</h1>
<table>
    <tr>
        <th>제목</th>
        <th>등록일</th>
    </tr>
    @foreach ($whole_notices as $notice)
        <tr>
            <td><a href="{{route('notice.show', ['id' => $notice->id])}}">{{$notice->title}}</a></td>
            <td>{{$notice->updated_at}}</td>
        </tr> 
    @endforeach
</table>
</div>
<hr>
<div class="my-notice">
<h1>내 공지사항</h1>
<table>
    <tr>
        <th>제목</th>
        <th>등록일</th>
    </tr>
    @foreach ($my_notices as $notice)
        <tr>
            <td><a href="{{route('notice.show', ['id' => $notice->id])}}">{{$notice->title}}</a></td>
            <td>{{$notice->updated_at}}</td>
        </tr>
    @endforeach
</table>
<a class="btn btn-primary" href="{{route('notice.create')}}">추가</a>
</div>
@endsection