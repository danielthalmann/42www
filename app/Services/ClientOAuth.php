<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ClientOAuth
{

	protected $Client_ID = '';
    protected $Client_secret = '';
    protected $redirect_uri = '';
	protected $token = null;

    public function __construct()
    {
        $this->Client_ID = env('OAUTH_UID');
        $this->Client_secret = env('OAUTH_SECRET');
        $this->redirect_uri = env('OAUTH_REDIRECT_URI');
		$this->token = Session::get('token');
    }

	public static function make()
	{
		return new ClientOAuth();
	}

	public function set_token($token)
	{
		$this->token = $token;
		Session::put('token', $token);
	}

	public function get_token()
	{
		return $this->token;
	}

	public function get($url, $data = [])
	{
		$this->init_token();
		          
        $response = Http::withHeaders([
			'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $this->token['access_token'],
        ])->get($url, $data);

		return $response->json();
		
	}

	public function init_token()
	{
		if (!$this->token)
		{
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
				throw new Exception($token['error']);
			}

			$this->set_token($token);

		}

	}


	public function callback($code)
	{
		$data = [
			'grant_type' => 'authorization_code',
			'client_id' => $this->Client_ID,
			'client_secret' => $this->Client_secret,
			'redirect_uri' => $this->redirect_uri,
			'code' => $code
		   ];
		
		$response = Http::asForm()/* ->withOptions([
			'debug' => true,
		]) */->post('https://api.intra.42.fr/oauth/token', $data);

		$token = $response->json();

		if (isset($token['error']))
		{
			throw new Exception($token['error']);
		}
		
		$this->set_token($token);

	}

	
    public function redirect()
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