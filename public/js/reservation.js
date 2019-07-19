(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/reservation"],{

/***/ "./resources/js/reservation.js":
/*!*************************************!*\
  !*** ./resources/js/reservation.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {$(document).ready(function () {
  //입력폼 첫방문, 재방문 클릭 시 show, hide
  $("#first_visit").on("click", function () {
    $(".is_first").show();
  });
  $("#re_visit").on("click", function () {
    $(".is_first").hide();
  }); //재방문 체크되어있으면 첫방문 입력폼 가리기

  if ($("#re_visit").attr("checked") == "checked") {
    $(".is_first").hide();
  } //예약종류 선택 시 예약금 설정


  $("#kind_hairmakeup").on("click", function () {
    $("#deposit").val(45000);
  });
  $("#kind_makeup").on("click", function () {
    $("#deposit").val(30000);
  });
  $("#kind_hair").on("click", function () {
    $("#deposit").val(20000);
  });
  $("#kind_naile").on("click", function () {
    $("#deposit").val(10000);
  });
  $("#kind_waxing").on("click", function () {
    $("#deposit").val(20000);
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ 3:
/*!*******************************************!*\
  !*** multi ./resources/js/reservation.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\Shop\shop-managing\resources\js\reservation.js */"./resources/js/reservation.js");


/***/ })

},[[3,"/js/manifest","/js/vendor"]]]);