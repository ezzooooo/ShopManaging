@extends('layouts.app')

@section('content')
    <div class="container">
        <table class="table table-bordered">
            <tr>
                <td>매장명</td>
                <td colspan="2">{{ $store->name }}</td>
            </tr>
            <tr>
                <td>카테고리</td>
                <td colspan="2">{{ $user->category }}</td>
            </tr>
            <tr>
                <td>연락처</td>
                <td colspan="2">{{ $user->phone }}</td>
            </tr>
            <tr>
                <td>이메일</td>
                <td colspan="2">{{ $user->email }}</td>
            </tr>
            <tr>
                <td>비밀번호</td>
                <td>●●●●●●●●</td>
                <td><a href="{{ route('user.changepasswd') }}">비밀번호 변경하기</a></td>
            </tr>
            <tr>
                <td>매장설정</td>
                @if($store_setting != null)
                <td colspan="2"><a href="{{route('storesetting.edit', ['id' => $store_setting->id])}}">매장설정</a></td>
                @else
                <td colspan="2"><a href="{{route('storesetting.create')}}">매장설정</a></td>
                @endif
            </tr>
            <tr>
                <td>매장예약주소</td>
                <td><span id="url">{{ $url }}</span></td>
                <td><button onclick="url_copy('#url');">복사하기</button></td>
            </tr>
        </table>
    </div>
    
    @if($user->role == 1)
    <div class="worker-list">
        <h1>직원리스트</h1>
        <a class="btn btn-primary" href="{{route('user.create')}}">직원등록</a>
        <table class="worker-list-table table table-bordered">
            <tr>
                <th>이름</th>
                <th>연락처</th>
                <th>삭제</th>
            </tr>
            @foreach ($workers as $worker)
                <tr>
                    <td>{{$worker->name}}</td>
                    <td>{{$worker->phone}}</td>
                    <td>
                        <form action="{{route('user.destroy', ['id' => $worker->id])}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button>직원삭제</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    @endif
    <script>
    function url_copy(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
        alert("copy complete"); //Optional Alert, 삭제해도 됨
    }
    </script>
@endsection