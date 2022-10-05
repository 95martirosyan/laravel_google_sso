
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
}
function showPosition(position) {
    $.ajax({
        url : '/getWeather',
        data : {
            'latitude' : position.coords.latitude,
            'longitude' : position.coords.longitude
        },
        'success' : function (data){
            $('.card-body').html(data)

        }
    });
}
