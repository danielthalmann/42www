<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ClientOAuth
{

	protected $Client_ID = '';
    protected $Client_secret = '';
    protected $redirect_uri = '';
	protected $token = null;
    protected $host = '';

    public function __construct()
    {
        $this->Client_ID = env('OAUTH_UID');
        $this->Client_secret = env('OAUTH_SECRET');
        $this->redirect_uri = env('OAUTH_REDIRECT_URI');
		$this->token = Session::get('token');
		$this->host = 'https://api.intra.42.fr';
    }

	public static function make()
	{
		return new ClientOAuth();
	}

	public function set_token($token)
	{
		$this->token = $token;
		Session::put('token', $token);
		return $this;
	}

	public function get_token()
	{
		return $this->token;
	}

	public function set_page($page)
	{
		$this->page = $page;
		return $this;
	}

	public function get($url, $data = [])
	{
		$this->init_token();

        $response = Http::withHeaders([
			'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $this->token['access_token'],
        ])->get($url, $data);

	//	Log::info(json_encode($response->json()));
        return [
            'perPage' => (int)$response->header('X-Per-Page'),
            'page'    => (int)$response->header('X-Page'),
            'total'   => (int)$response->header('X-Total'),
            'datas'   => $response->json(),
        ];

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
			]) */->post($this->host . '/oauth/token', $data);

			$token = $response->json();

			if (isset($token['error']))
			{
				throw new Exception($token['error']);
			}

			$this->set_token($token);

		} else {

			$expire = \Carbon\Carbon::createFromTimestamp($this->token['created_at'])
			->addSeconds($this->token['expires_in']);

			if ($expire < \Carbon\Carbon::now())
			{
				if(isset($this->token['refresh_token']))
				{
					$data = [
						'grant_type' => 'refresh_token',
						'refresh_token' => $this->token['refresh_token'],
						'client_id' => $this->Client_ID,
						'client_secret' => $this->Client_secret,
						];
				} else {
					$data = [
						'grant_type' => 'client_credentials',
						'client_id' => $this->Client_ID,
						'client_secret' => $this->Client_secret,
						];
				}

				$response = Http::asForm()
				->post($this->host . '/oauth/token', $data);

				$token = $response->json();

				if (isset($token['error']))
				{
					throw new Exception($token['error']);
				}

				$this->set_token($token);

			}

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
		]) */->post($this->host . '/oauth/token', $data);

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

        return redirect($this->host . '/oauth/authorize?'.$query);
    }


}
