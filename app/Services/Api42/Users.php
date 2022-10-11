<?php

namespace App\Services\Api42;

use App\Services\ClientOAuth;
use Illuminate\Pagination\Paginator;

class Users
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
	 * Return the user id
	 *
	 * @param integer $id
	 * @return void
	 */
	public function get(int $id)
	{
	    return $this->clientApi->get('/v2/users/' . $id)['datas'];
	}

	/**
	 * Return all the users
	 *
	 * @param integer $page
	 * @return void
	 */
	public function all(int $page = 1)
	{
        return $this->clientApi->toPaginator($this->clientApi->get('/v2/users', ['page' => $page]));
	}

	/**
	 * Return all the users
	 *
	 * @param integer $campuId
	 * @param integer $page
	 * @return void
	 */
	public function ofCampus(int $campuId, int $page = 1)
	{
        return $this->clientApi->toPaginator($this->clientApi->get('/v2/campus/' . $campuId . '/users', ['page' => $page, 'perPage' => 100]));
	}


	/**
	 * Return all cursus of user id
	 *
	 * @param integer $campuId
	 * @param integer $page
	 * @return void
	 */
	public function cursus(int $id, int $page = 1)
	{
        return $this->clientApi->toPaginator($this->clientApi->get('/v2/users/' . $id . '/cursus_users', ['page' => $page]));
	}	
}
