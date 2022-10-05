<?php

namespace App\Traits;

use Illuminate\Support\Facades\Redis;

trait Weather {
    public static function getMainData($lat, $lng) {
        $lat = substr($lat, 0, 6);
        $lng = substr($lng, 0, 6);
        $weatherData = Redis::get('weather_data_for_cords_'.$lat.'_'.$lng);

        return (!empty($weatherData) ? $weatherData : self::requestWeatherData($lat, $lng));
    }

    private static function requestWeatherData($lat, $lng) {
        $response = file_get_contents('https://api.openweathermap.org/data/2.5/weather?lat='.$lat.'&lon='.$lng.'&appid='.env("WEATHER_KEY"));
        Redis::set('weather_data_for_cords_'.$lat.'_'.$lng, $response);
        Redis::expire('weather_data_for_cords_'.$lat.'_'.$lng, 7200);
        return $response;
    }

}
