/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*********************************!*\
  !*** ./resources/js/weather.js ***!
  \*********************************/
if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(showPosition);
}

function showPosition(position) {
  $.ajax({
    url: '/getWeather',
    data: {
      'latitude': position.coords.latitude,
      'longitude': position.coords.longitude
    },
    'success': function success(data) {
      $('.card-body').html(data);
    }
  });
}
/******/ })()
;