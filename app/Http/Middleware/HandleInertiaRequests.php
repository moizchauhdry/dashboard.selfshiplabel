<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{

    protected $rootView = 'app';
    public function __construct()
    {
//        Log::info(\request()->route()->action['as']);
        if(isset(\request()->route()->action['as']) && in_array(\request()->route()->action['as'],['auctions.index','auctions.detail','auctions.bid'])){
            $this->rootView = 'frontend';
        }


    }

    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */


    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
    * Define the props that are shared by default.
    *
    * @param \Illuminate\Http\Request $request
    * @return array
    */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'notification_count' => ($request->user()) ? $request->user()->unreadNotifications()->count(): 0,
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error')
            ]
        ]);
    }
    
}
