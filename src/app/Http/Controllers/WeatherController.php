<?php

namespace App\Http\Controllers;

use PHPUnit\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    public function index(Request $request)
    {
        $location = $request->q;
        $units = $request->units;

        if (Cache::has("@weather/loc=$location/units=$units"))
            return response(Cache::get("@weather/loc=$location/units=$units"), 200);
        else {
            try {
                return response($this->getWeather($location, $units), 200);
            } catch (Exception $e) {
                return response($e->getMessage(), 422);
            }
        }
    }

    public function getWeather($location, $units)
    {
        try {
            $api_key = env('WEATHER_API_KEY');
            $res = Http::get("api.openweathermap.org/data/2.5/weather?q=$location&appid=$api_key&units=$units");
        } catch (Exception $e) {
            throw new Exception('Weather fetching error.');
        }

        Cache::put("@weather/loc=$location/units=$units", $res->json(), now()->addMinutes(15));
        return $res->json();
    }
}