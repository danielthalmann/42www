<?php

namespace App\Services;

use App\Services\ClientOAuth;

class Api 
{
	/**
	 * client OAuth
	 *
	 * @var App\Services\ClientOAuth
	 */
	protected $client;

	/**
	 * Undocumented function
	 *
	 * @param App\Services\ClientOAuth $client
	 */
	public function __construct($client)
	{
		$this->client = $client;
	}

	/**
	 * Return all the accreditations
	 * 
	 * @see https://api.intra.42.fr/apidoc/2.0/accreditations/index.html
	 * 
	 * @param integer $id
	 * @return void
	 */
	public function accreditations(int $id = null)
	{
		return $this->client->get('/v2/accreditations/' . $id);
	}

}