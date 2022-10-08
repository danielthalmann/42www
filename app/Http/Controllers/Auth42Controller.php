<?php

namespace App\Http\Controllers;

use App\Services\ClientOAuth;
use Illuminate\Http\Request;
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

        dd($client->get('https://api.intra.42.fr' . $request->input('q') . "?page=" . $request->input('page')));
        dd($client->set_page($request->input('page') ? $request->input('page') : 1)->get('https://api.intra.42.fr' . $request->input('q')));
        dd($client->get('https://api.intra.42.fr/v2/campus'));
    
    }
       
    /**
     * app thiers
     */
    public function callback(Request $request)
    {
        ClientOAuth::make()->callback($request->code);

        return "ok";
    }
        
    /**
     * redirect to 42 ai
     *
     * @param Request $request
     * @return void
     */
    public function redirect(Request $request)
    {
        return ClientOAuth::make()->redirect();
    }
        
}
