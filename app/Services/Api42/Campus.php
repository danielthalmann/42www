<?php

namespace App\Services\Api42;

use App\Services\ClientOAuth;
use Illuminate\Pagination\Paginator;

class Campus
{
	/**
	 * client OAuth
	 *
	 * @var App\Services\Api42
	 */
	protected $clientApi;

	/**
	 * Undocumented function
	 *
	 * @param App\Services\ClientOAuth $client
	 */
	public function __construct($clientApi)
	{
		$this->clientApi = $clientApi;
	}

	/**
	 * Return the campus id
	 *
	 * @param integer $id
	 * @return void
	 */
	public function get(int $id)
	{
	    return $this->clientApi->get('/v2/campus/' . $id)->datas;
	}

	/**
	 * Return all the campus
	 *
	 * @param integer $page
	 * @return void
	 */
	public function all(int $page = 1)
	{
        return $this->clientApi->toPaginator($this->clientApi->get('/v2/campus', ['page' => $page]));
	}
	
}
