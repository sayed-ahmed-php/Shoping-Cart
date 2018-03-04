<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Order;
use App\Product;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use League\Flysystem\Exception;
use Session;
use Stripe\Charge;
use Stripe\Stripe;

class ProductController extends Controller
{
    function index(){
        $product = Product::all();
        return view('shop.index', ['product' => $product]);
    }

    function getAddToCart(Request $req, $id){
        $product = Product::find($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart -> add($product, $product -> id);

        $req -> session() -> put('cart', $cart);
        return redirect('/');
    }

    function getReduceByOne($id){
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart -> reduceByOne($id);
        if (count($cart -> items) > 0){
            Session::put('cart', $cart);
        } else {
            Session::forget('cart');
        }
        return redirect('shoppingCart');
    }

    function getRemoveAll($id){
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart -> removeAll($id);

        if (count($cart -> items) > 0){
            Session::put('cart', $cart);
        } else {
            Session::forget('cart');
        }
        return redirect('shoppingCart');
    }

    function getShoppingCart(){
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        return view('shop.cart', ['product' => $cart -> items, 'totalPrice' => $cart -> totalPrice]);
    }

    function getCheckout(){
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $total = $cart ->totalPrice;
        return view('shop.checkout', ['total' => $total]);
    }

    function postCheckout(Request $req){
        $cart = new Cart(Session::get('cart'));
        Stripe::setApiKey('sk_test_9yOLuUtGqQ3ZplvnHM364Y6z');
        try {
            $charge = Charge::create(array(
                'amount' => $cart -> totalPrice * 100,
                'currency' => 'usd',
                'source' => $req -> input('stripeToken'),
                'description' => 'Test Charge'
            ));
            $order = new Order();
            $order -> cart = serialize($cart);
            $order -> address = $req -> input('address');
            $order -> name = $req -> input('name');
            $order -> payment_id = $charge -> id;

            Auth::user() -> orders() -> save($order);
        } catch (Exception $e){
            return redirect('checkout') -> with('error', $e ->getMessage());
        }
        Session::forget('cart');
        return redirect('/') -> with('success', 'Successfully Purchased Products' );
    }
}
