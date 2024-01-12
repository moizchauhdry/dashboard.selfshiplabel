<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class MarketController extends Controller
{
    protected $api_key = 'eeb2d697583a3add8d4c7b38874a52bb';

    public function cryptoSidebar(){
        $url = 'https://financialmodelingprep.com/api/v3/quote/BTCUSD,DOGEUSD,ETHUSD,ADAUSD,XRPUSD,LTCUSD?apikey='.$this->api_key;
        $cryptoCurrency = file_get_contents($url);
        return $cryptoCurrency; 
    }

    //
    public function index(){
        //eeb2d697583a3add8d4c7b38874a52bb
        //https://financialmodelingprep.com/api/v3/profile/AAPL,MSFT,MRVL,COUP,MTN,HQY,DADA,SFIX,ENIC,NAPA,GIII,BBCP,THCB,JFIN,NEXT?apikey=eeb2d697583a3add8d4c7b38874a52bb
        $url = 'https://financialmodelingprep.com/api/v3/profile/AAPL,MSFT,MRVL,COUP,MTN,HQY,DADA,SFIX,ENIC,NAPA,GIII,BBCP,THCB,JFIN,NEXT,ENOB,THCA,GSMG,PSTI,BSGM?apikey=eeb2d697583a3add8d4c7b38874a52bb';
        $stock_list = json_decode(file_get_contents($url));
        return Inertia::render('MarketEarningActivity',['stock_list' => $stock_list]);
    }

    public function show($symbol){

        $url = 'https://financialmodelingprep.com/api/v3/historical/earning_calendar/' . $symbol . '?limit=6&apikey=eeb2d697583a3add8d4c7b38874a52bb';
        $QuarterlyEarnings = json_decode(file_get_contents($url));

        $url = 'https://financialmodelingprep.com/api/v3/quote/' . $symbol . '?apikey=eeb2d697583a3add8d4c7b38874a52bb';
        $Summary = json_decode(file_get_contents($url));

        $url = 'https://financialmodelingprep.com/api/v3/profile/' . $symbol . '?apikey=eeb2d697583a3add8d4c7b38874a52bb';
        $Profile = json_decode(file_get_contents($url));

        
        return Inertia::render('MarketEarningActivityDetail',[  'symbol' => $symbol,
                                                                'QuarterlyEarnings' => $QuarterlyEarnings,
                                                                'SummaryData' => $Summary[0],
                                                                'Profile' => $Profile[0] ]);
    }

    public function history($symbol){
        $url = 'https://financialmodelingprep.com/api/v3/historical-price-full/'.$symbol.'?from=2021-04-01&to=2021-06-07&apikey=eeb2d697583a3add8d4c7b38874a52bb';
        $history = file_get_contents($url);
        return $history; 

    }

    public function majorIndexes(){
        $url = 'https://financialmodelingprep.com/api/v3/quote/%5EGSPC,%5EDJI,%5ENDX,%5ERUT,%5EFTSE,%5EN225?apikey=eeb2d697583a3add8d4c7b38874a52bb';
        $majorIndexes = file_get_contents($url);
        return $majorIndexes; 
    }

    public function realtime(Request $request , $symbol){
        $url = 'https://financialmodelingprep.com/api/v3/historical-chart/15min/'.$symbol.'?apikey=eeb2d697583a3add8d4c7b38874a52bb';
        $realTime = json_decode(file_get_contents($url));
        return Inertia::render('MarketEarningActivityDetail',[  'symbol' => $symbol,
                                                                'realTime' => $realTime ]);
    }

    public function dividendHistory(Request $request , $symbol){

        $url = 'https://financialmodelingprep.com/api/v3/profile/' . $symbol . '?apikey=eeb2d697583a3add8d4c7b38874a52bb';
        $Profile = json_decode(file_get_contents($url));

        $url = 'https://financialmodelingprep.com/api/v3/historical-price-full/stock_dividend/' . $symbol . '?apikey=eeb2d697583a3add8d4c7b38874a52bb';
        $dividendHistory = json_decode(file_get_contents($url));

        return Inertia::render('MarketEarningActivitydividendHistory',[  'symbol' => $symbol,
                                                                'Profile' => $Profile[0],
                                                                'dividendHistory' => $dividendHistory ]);

    }

    public function historicalData(Request $request , $symbol){

        $url = 'https://financialmodelingprep.com/api/v3/profile/' . $symbol . '?apikey=eeb2d697583a3add8d4c7b38874a52bb';
        $Profile = json_decode(file_get_contents($url));

        $current_date = date('Y-m-d');
        $past_date = date("Y-m-d", strtotime($current_date." -4 months"));


        $url = 'https://financialmodelingprep.com/api/v3/historical-price-full/AAPL?from='.$past_date.'&to='.$current_date.'&apikey=eeb2d697583a3add8d4c7b38874a52bb';
        $HistoricalData = json_decode(file_get_contents($url));

        return Inertia::render('MarketEarningActivityHistoryData',[  'symbol' => $symbol,
                                                                'Profile' => $Profile[0],
                                                                'HistoricalData' => $HistoricalData ]);

    }

    public function financials(Request $request , $symbol){
        $url = 'https://financialmodelingprep.com/api/v3/profile/' . $symbol . '?apikey=eeb2d697583a3add8d4c7b38874a52bb';
        $Profile = json_decode(file_get_contents($url));


        $url = 'https://financialmodelingprep.com/api/v3/income-statement/' . $symbol . '?limit=4&apikey=eeb2d697583a3add8d4c7b38874a52bb';
        $Financials = json_decode(file_get_contents($url));

        return Inertia::render('MarketEarningActivityFinancials',[  'symbol' => $symbol,
                                                                    'Profile' => $Profile[0],
                                                                    'Financials' => $Financials ]);

    }

    public function news(Request $request , $symbol){
        $url = 'https://financialmodelingprep.com/api/v3/profile/' . $symbol . '?apikey=eeb2d697583a3add8d4c7b38874a52bb';
        $Profile = json_decode(file_get_contents($url));


        $url = 'https://financialmodelingprep.com/api/v3/stock_news?tickers=' . $symbol . '&limit=10&apikey=eeb2d697583a3add8d4c7b38874a52bb';
        $News = json_decode(file_get_contents($url));

        return Inertia::render('MarketEarningActivityNews',[  'symbol' => $symbol,
                                                                    'Profile' => $Profile[0],
                                                                    'News' => $News ]);

    }

    public function pressRelease(Request $request , $symbol){
        $url = 'https://financialmodelingprep.com/api/v3/profile/' . $symbol . '?apikey=eeb2d697583a3add8d4c7b38874a52bb';
        $Profile = json_decode(file_get_contents($url));


        $url = 'https://financialmodelingprep.com/api/v3/stock_news?tickers=' . $symbol . '&limit=10&apikey=eeb2d697583a3add8d4c7b38874a52bb';
        $PressRelease = json_decode(file_get_contents($url));

        return Inertia::render('MarketEarningActivityPressRelease',['symbol' => $symbol,
                                                                    'Profile' => $Profile[0],
                                                                    'pressReleases' => $PressRelease ]);

    }

}
