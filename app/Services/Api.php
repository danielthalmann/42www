<?php

namespace App\Services;

use App\Services\ClientOAuth;
use Illuminate\Pagination\Paginator;

class Api
{
	/**
	 * client OAuth
	 *
	 * @var App\Services\ClientOAuth
	 */
	protected $client;

	protected $host;

	/**
	 * Undocumented function
	 *
	 * @param App\Services\ClientOAuth $client
	 */
	public function __construct($client)
	{
		$this->client = $client;
		$this->host = 'https://api.intra.42.fr';
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
        if ($id)
		    return $this->client->get($this->host . '/v2/accreditations/' . $id)->datas;
        else
            return $this->toPaginator($this->client->get($this->host . '/v2/accreditations/' . $id));
	}


    /**
     * Undocumented function
     *
     * @see https://api.intra.42.fr/apidoc/2.0/achievements/index.html
     *
     * @param integer|null $id
     * @return void
     */
	public function achievements(int $id = null)
	{
        if ($id)
		    return $this->client->get($this->host . '/v2/achievements/' . $id)->datas;
        else
            return $this->toPaginator($this->client->get($this->host . '/v2/achievements/' . $id));
	}

    /**
	 * convert response from api to paginator
	 *
	 * @param array $datas
	 * @return void
	 */
    private function toPaginator($datas)
    {
        return new Paginator($datas['datas'], $datas['perPage'], $datas['page']);
    }

}
