<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use Session;

class UserController extends Controller
{
    function getSignup(){
        return view('user.signup');
    }

    function postSignup(Request $req){
        $this -> validate($req, [
            'email' => 'email | required | unique:users',
            'password' => 'required | min:6'
        ]);
        $user = new User([
            'email' => $req -> input('email'),
            'password' => bcrypt($req -> input('password'))
        ]);
        $user -> save();
        Auth::login($user);

        if (Session::has('olderUrl')){
            $olderUrl = Session::get('olderUrl');
            Session::forget('oldUrl');
            return redirect() -> to($olderUrl);
        }
        return redirect('profile');
    }

    function getSignin(){
        return view('user.signin');
    }

    function postSignin(Request $req){
        $this -> validate($req, [
            'email' => 'email | required',
            'password' => 'required'
        ]);
        if (Auth::attempt(['email' => $req ->input('email'), 'password' => $req ->input('password')])){
            if (Session::has('olderUrl')){
                $olderUrl = Session::get('olderUrl');
                Session::forget('oldUrl');
                return redirect() -> to($olderUrl);
            }
            return redirect('/profile');
        }
        return redirect() -> back();
    }

    function getProfile(){
        $orders = Auth::user() -> orders;
        $orders -> transform(function ($order, $key){
            $order -> cart = unserialize($order -> cart);
            return $order;
        });
        return view('user.profile', ['orders' => $orders]);
    }

    function getLogout(){
        Auth::logout();
        return redirect('signin');
    }
}
