@extends('layouts.app')

@section('content')
<div class="container">
    <h1>예약정보 확인</h1>
    <table class="table table-bordered">
        <tr>
            <td>예약상태</td>
            <td>{{ $item->stat }}</td>
        </tr>
        <tr>
            <td>예약일자</td>
            <td>{{ date("Y-m-d H:i:s", strtotime("$item->created_at")) }}</td>
        </tr>
        <tr>
            <td>예약시간</td>
            <td>{{ $item->date }} {{ date("H:i",strtotime($item->start_time)) }} ~ {{
                date("H:i",strtotime($item->end_time)) }}</td>
        </tr>
        <tr>
            <td>예약자 성함</td>
            <td>{{ $item->customer->name }}</td>
        </tr>
        <tr>
            <td>성별</td>
            <td>{{ $item->customer->gender }}</td>
        </tr>
        <tr>
            <td>연락처</td>
            <td>{{ $item->customer->phone }}</td>
        </tr>
        <tr>
            <td>예약종류</td>
            <td>{{ $item->product }}</td>
        </tr>
        <tr>
            <td>용도</td>
            <td>{{ $item->purpose }}</td>
        </tr>
        <tr>
            <td>방문횟수</td>
            <td>{{ $item->customer->visit_count }}</td>
        </tr>
        @if ($item->customer->visit_count == 1)
        <tr>
            <td>특이사항</td>
            <td>{{ $item->customer->feature }}</td>
        </tr>
        <tr>
            <td>생년월일</td>
            <td>{{ $item->customer->birth }}</td>
        </tr>
        @endif
        <tr>
            <td>메모</td>
            <td>{{ $item->memo }}</td>
        </tr>
        <tr>
            <td>상품가격</td>
            <td><span id="item_price">{{ $item->price }}</span>원</td>
        </tr>
    </table>
    <div class="row">
        <a href="{{ route('reservations.edit', ['id'=>$item->id]) }}"><button>수정하기</button></a>
        <form id="cancel_form" action="{{ route('reservations.destroy', ['id'=>$item->id]) }}" method="post">
            @method('DELETE')
            @csrf
            <button type="button" onclick="reservation_cancel();">예약 취소</button>
        </form>
        @if($item->stat == "예약확정 대기")
        <form id="confirm_form" action="{{ route('reservations.confirm', ['id'=>$item->id]) }}" method="post">
            @csrf
            <input type="hidden" name="price" id="price">
            <button type="button" onclick="input_price();" id="button_confirm">예약 확정 (입금확인)</button>
        </form>
        @elseif($item->stat == "예약확정")
        <form id="complete_form" action="{{ route('reservations.complete', ['id' => $item->id]) }}" method="post">
            @csrf
            <button type="button" onclick="reservation_complete();" id="button_complete">시술 완료(매출입력)</button>
        </form>
        @elseif($item->stat == "예약확인 대기")
        <form id="add_form" action="{{ route('reservations.add', ['id' => $item->id]) }}" method="post">
            @csrf
            <button type="button" onclick="reservation_add();" id="button_store">예약 추가</button>
        </form>
        @endif
    </div>

    @if($item->customer->visit_count > 1)
    <h1>재방문 고객님 정보</h1>
    <table class="table table-bordered">
        <tr>
            <td class="table-dark">이름</td>
            <td>{{ $item->customer->name }}</td>
            <td class="table-dark">방문횟수</td>
            <td>{{ $item->customer->visit_count }}번</td>
        </tr>
        <tr>
            <td class="table-dark">특이사항</td>
            <td colspan="3">{{ $item->customer->feature }}</td>
        </tr>
        <tr>
            <td class="table-dark">생년월일</td>
            <td colspan="3">{{ $item->customer->birth }}</td>
        </tr>
        <tr>
            <td class="table-dark">방문일시</td>
            <td class="table-dark">시술내용</td>
            <td colspan="2" class="table-dark">메모</td>
        </tr>
        @foreach ($re_items as $re_item)
        <tr>
            <td>{{ $re_item->date }}</td>
            <td>{{ $re_item->product }}</td>
            <td colspan="2">{{ $re_item->memo }}</td>
        </tr>
        @endforeach
    </table>
    @endif
</div>
@endsection

<script>
    //예약 취소 함수
    function reservation_cancel() {
        if (confirm("예약을 취소하시겠습니까?")) {
            cancel_form.submit();
        } else {
            return false;
        }
    }

    //예약금 입력 함수
    function input_price() {
        confirm_form.price.value = prompt("선결제금을 입력해주세요.(총결제금액 : " + $('#item_price').text() + "원)");
        if ( isNaN(confirm_form.price.value) || confirm_form.price.value == "") {                  //숫자가 아니라면
            alert("숫자만 입력 가능합니다.");
            return false;
        } else if ( confirm_form.price.value > parseInt($('#item_price').text())) {
            alert("총결제금액을 초과할 수 없습니다.");
            return false;
        }
        else {
            if (confirm("선결제금 " + confirm_form.price.value + "원이 맞습니까?")) {
                alert("예약이 확정 되었습니다.");
                confirm_form.submit();
            } else {
                return false;
            }
        }
    }

    //예약 추가 함수
    function reservation_add() {
        if (confirm("예약을 추가하시겠습니까?")) {
            add_form.submit();
        } else {
            return false;
        }
    }

    //시술 완료 함수
    function reservation_complete() {
        if (confirm("예약을 완료하고 매출을 등록하시겠습니까?")) {
            complete_form.submit();
        } else {
            return false;
        }
    }

</script>
