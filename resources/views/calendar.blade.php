<script>
$(function () {
    //캘린더 기본설정
    $('#calendar').fullCalendar({
        dayClick: function (date) {
            //console.log(date + ", " + (new Date() - date) / 1000); //디버그용
            //if (((new Date() - date) / 1000) < 54000) { // 해당 일이 끝나기 전이면 동작 ( KST : 86400 - 32400)
            location.href = "/timetable/" + date.format().replace(/-/gi, "");
            //}
        },
        header: {
            left: "month,basicWeek",
            center: "title",
            //right: "month,basicWeek,basicDay"
            right: "prev,today,next"
        },
        editable: true,
        allDayDefault: false,
        defaultView: "month",
        editable: false,
        monthNames: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
        monthNamesShort: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월",
            "12월"
        ],
        dayNames: ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"],
        dayNamesShort: ["일", "월", "화", "수", "목", "금", "토"],
        buttonText: {
            today: "오늘",
            month: "월별",
            week: "주별",
            day: "일별",
        },
        timeFormat: "HH:mm",
    });

    //예약 데이터 저장
    var items = new Array;
    items = JSON.parse($('#reservations').val(), true);
    //console.log(items);

    //달력 데이터 저장
    var cells = new Array;
    cells = $('.fc-day').siblings();

    //예약 날짜와 달력 날짜가 같으면 예약자 표시
    for (k = 0; k < items.length; k++) {
        for (i = 0; i < cells.length; i++) {
            if ($(cells[i]).data('date') == items[k].date) {
                //console.log($(cells[i]).data('date'));
                if(items[k].stat == "예약확정") {
                    $(cells[i]).append("○ " + items[k].customer.name + "<br>");
                } else if(items[k].stat == "예약확정 대기"){
                    $(cells[i]).append("△ " + items[k].customer.name + "<br>");
                } else {
                    
                }
                break;
            }
        }
    }

    $('.fc-button').click(function() {      // 월 변경 시에도 동일하게 적용
        $(document).ready(function() {
            cells = $('.fc-day').siblings();
            //예약 날짜와 달력 날짜가 같으면 예약자 표시
            for (k = 0; k < items.length; k++) {
                for (i = 0; i < cells.length; i++) {
                    if ($(cells[i]).data('date') == items[k].date) {
                        //console.log($(cells[i]).data('date'));
                        if(items[k].stat == "예약확정") {
                            $(cells[i]).append("○ " + items[k].name + "<br>");
                        } else if(items[k].stat == "예약확정 대기"){
                            $(cells[i]).append("△ " + items[k].name + "<br>");
                        } else {
                            
                        }
                        break;
                    }
                }
            }
        });
    });
    
    var result = 0;

    for(i = 0; i<items.length; i++) {
        result += items[i].deposit;
    }
    //console.log(result);
});

</script>