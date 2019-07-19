@extends('layouts.app')

@section('content')
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
    <form action="{{route('user.inquire_email')}}" method="post">
        @csrf
        <label for="inquire_email">이메일주소</label>
        <input type="email" name="inquire_email" id="inquire_email"><button>조회</button>
    </form>
@endsection