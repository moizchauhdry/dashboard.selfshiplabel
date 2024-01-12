<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use Inertia\Inertia;
use GuzzleHttp\Client;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\AdminUserRegistered;
use App\Notifications\ParcelReceived;
use App\Notifications\UserWelcomeEmail;
use Illuminate\Support\Facades\Notification;

class EmailController extends Controller
{


    public function index(){


      // $user = User::find(19);
      // $user->notify(new UserWelcomeEmail());

      // $admins = User::where(['type' => 'admin'])->get();
      // Notification::send($admins, new AdminUserRegistered($user));


      // $order = Order::find(6);
      // if(is_object($order)){
      //     $order->notify(new ParcelReceived($order));
      // }

      // echo "End"; exit;

  }

}