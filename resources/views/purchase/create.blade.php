@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('purchase.store')}}" method="POST">
        @csrf
        <table class="table table-bordered">  
            <tr>
                <td>일자</td>
                <td><input type="date" name="date" id="date" value="{{Cookie::get('today')}}"></td>
            </tr>
            <tr>
                <td>업체</td>
                <td><input type="text" name="partner" id="partner"></td>
            </tr>
            <tr>
                <td>상품</td>
                <td><input type="text" name="content" id="content"></td>
            </tr>
            <tr>
                <td>수량</td>
                <td><input type="number" name="quantity" id="quantity"></td>
            </tr>
            <tr>
                <td>단가</td>
                <td><input type="number" name="unit_price" id="unit_price"></td>
            </tr>
            <tr>
                <td>총가격</td>
                <td><input type="number" name="price" id="price"></td>
            </tr>
            <tr>
                <td>메모</td>
                <td><input type="text" name="memo" id="memo"></td>
            </tr>
        </table>
        <button type="submit">저장</button>
        <button type="reset">취소</button>
        </form>
    </div>
    <script>


        $("#quantity").on('keyup change', function(){
            console.log($('#quantity').val());
            $('#price').val($('#quantity').val() * $('#unit_price').val());
        });

        $('#unit_price').on('keyup change', function() {
            console.log($("#unit_price").val());
            $('#price').val($('#quantity').val() * $('#unit_price').val());
        });
        
    </script>
@endsection

