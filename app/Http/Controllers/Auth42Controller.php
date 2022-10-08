<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class Auth42Controller extends Controller
{

    protected $Client_ID = '';
    protected $Client_secret = '';
    protected $redirect_uri = '';

    public function __construct()
    {
        $this->Client_ID = env('OAUTH_UID');
        $this->Client_secret = env('OAUTH_SECRET');
        $this->redirect_uri = env('OAUTH_REDIRECT_URI');
    }

    public function mytoken(Request $request)
    {
        return dd(Session::get("token"));
    }
    
    /**
     * app thiers
     */
    public function token(Request $request)
    {
    
       // $state = Session::pull('state');
    
       // throw_unless(
       //     strlen($state) > 0 && $state === $request->state,
       //     InvalidArgumentException::class
       // );
       $data = [
        'grant_type' => 'client_credentials',
        'client_id' => $this->Client_ID,
        'client_secret' => $this->Client_secret,
       ];
    
        $response = Http::asForm()/* ->withOptions([
            'debug' => true,
        ]) */->post('https://api.intra.42.fr/oauth/token', $data);
    
        $token = $response->json();
    
        if (isset($token['error']))
        {
            return $token['error'];
        }
    
        $request->session()->put('token', $token );

        dd($token);
    
    }
       

    
    /**
     * app thiers
     */
    public function callback(Request $request)
    {
    
       // $state = $request->session()->pull('state');
    
       // throw_unless(
       //     strlen($state) > 0 && $state === $request->state,
       //     InvalidArgumentException::class
       // );

       $data = [
        'grant_type' => 'authorization_code',
        'client_id' => $this->Client_ID,
        'client_secret' => $this->Client_secret,
        'redirect_uri' => $this->redirect_uri,
        'code' => $request->code
       ];
    
        $response = Http::asForm()/* ->withOptions([
            'debug' => true,
        ]) */->post('https://api.intra.42.fr/oauth/token', $data);

        $token = $response->json();
    
        if (isset($token['error']))
        {
            return $token['error'];
        }
    
        Session::put('token', $token );

    }
        
    /**
     * redirect to 42 ai
     *
     * @param Request $request
     * @return void
     */
    public function redirect(Request $request)
    {
    
        Session::put('state', $state = Str::random(40));
    
        $query = http_build_query([
            'client_id' => $this->Client_ID,
            'client_secret' => $this->Client_secret,
            'redirect_uri' => $this->redirect_uri,
            'response_type' => 'code',
            'scope' => '',
            'state' => $state,
        ]);
    
        return redirect('https://api.intra.42.fr/oauth/authorize?'.$query);
    }
        
}
