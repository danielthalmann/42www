<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    
    /**
     * app thiers
     */
    public function token(Request $request)
    {
    
       // $state = $request->session()->pull('state');
    
       // throw_unless(
       //     strlen($state) > 0 && $state === $request->state,
       //     InvalidArgumentException::class
       // );
    
        $response = Http::asForm()/* ->withOptions([
            'debug' => true,
        ]) */->post('http://api.intra.42.fr/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->Client_ID,
            'client_secret' => $this->Client_secret,
            'redirect_uri' => $this->redirect_uri,
            'code' => $request->code
        ]);
    
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
    
        $response = Http::asForm()/* ->withOptions([
            'debug' => true,
        ]) */->post('http://api.intra.42.fr/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => $this->Client_ID,
            'client_secret' => $this->Client_secret,
            'redirect_uri' => $this->redirect_uri,
            'code' => $request->code
        ]);
    
        $token = $response->json();
    
        if (isset($token['error']))
        {
            return $token['error'];
        }
    
        $request->session()->put('token', $token );

        dd($token);
    
    }
        
    /**
     * redirect to 42 ai
     *
     * @param Request $request
     * @return void
     */
    public function redirect(Request $request)
    {
    
        $request->session()->put('state', $state = Str::random(40));
    
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
