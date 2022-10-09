<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ClientOAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class Auth42Controller extends Controller
{

    public function mytoken()
    {
        return ClientOAuth::make()->get_token();
    }
    
    /**
     * app thiers
     */
    public function query(Request $request)
    {
    
        $client = ClientOAuth::make();

        dd($client->get('https://api.intra.42.fr' . $request->input('q'), ['page' => $request->input('page'), 'filter' => $request->input('filter') ]));
        dd($client->set_page($request->input('page') ? $request->input('page') : 1)->get('https://api.intra.42.fr' . $request->input('q')));
        dd($client->get('https://api.intra.42.fr/v2/campus'));
    
    }
       
    /**
     * app thiers
     */
    public function callback(Request $request)
    {
        $client = ClientOAuth::make();
        
        $client->callback($request->code);

        $user42 = $client->get('https://api.intra.42.fr/v2/me')['datas'];

        $user = User::where('user42_id', $user42['id'])->first();

        if(!$user)
        {
            $user = new User();

            $user->user42_id   = $user42['id'];
            $user->name        = $user42['displayname'];
            $user->login       = $user42['login'];
            $user->email       = $user42['email'];
            $user->first_name  = $user42['first_name'];
            $user->last_name   = $user42['last_name'];
            $user->url         = $user42['url'];
            $user->phone       = $user42['phone'];
            $user->image_url   = $user42['image_url'];
            $user->pool_month  = $user42['pool_month'];
            $user->pool_year   = $user42['pool_year'];

            $user->password    = ''; // Hash::make();

            $user->save();
        }
        
        Auth::login($user);
 
        return redirect('/dashboard');

    }
        
    /**
     * redirect to 42 api
     *
     * @param Request $request
     * @return void
     */
    public function redirect(Request $request)
    {
        return ClientOAuth::make()->redirect();
    }
        
}
