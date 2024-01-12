<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class StockController extends Controller
{
    protected $api_key = 'eeb2d697583a3add8d4c7b38874a52bb';

    public function topGainers(){
        $url = 'https://financialmodelingprep.com/api/v3/gainers?apikey='.$this->api_key;
        $gainers = json_decode(file_get_contents($url));
        return Inertia::render('TopGainers',['gainers' => $gainers]);
    }

    public function dayLosers(){
        $url = 'https://financialmodelingprep.com/api/v3/losers?apikey='.$this->api_key;
        $losers = json_decode(file_get_contents($url));
        return Inertia::render('DayLosers',['losers' => $losers]);
    }

    public function daymostActive(){
        $url = 'https://financialmodelingprep.com/api/v3/actives?apikey='.$this->api_key;
        $actives = json_decode(file_get_contents($url));
        return Inertia::render('MostActives',['actives' => $actives]);
    }

    public function mostActive(){
        $url = 'https://financialmodelingprep.com/api/v3/actives?apikey='.$this->api_key;
        $actives = file_get_contents($url);
        return $actives;
    }

    public function gainers(){
        $url = 'https://financialmodelingprep.com/api/v3/gainers?apikey='.$this->api_key;
        $gainers = file_get_contents($url);
        return $gainers;
    }

    public function losers(){
        $url = 'https://financialmodelingprep.com/api/v3/losers?apikey='.$this->api_key;
        $losers = file_get_contents($url);
        return $losers;
    }

}
