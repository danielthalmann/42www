<?php

namespace App\Services;

use App\Services\ClientOAuth;
use Illuminate\Pagination\LengthAwarePaginator;

class Api42
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


	public function get(string $fn, $data = null)
	{
		return $this->client->get($this->host . $fn, $data);
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
	 * Return campus
	 *
	 * @return Api42\Campus
	 */
	public function campus()
	{
        return new Api42\Campus($this);
	}

	/**
	 * return users
	 *
	 * @return Api42\Users
	 */
	public function users()
	{
		return new Api42\Users($this);
	}
	
	/**
	 * return cursuses
	 *
	 * @return Api42\Cursus
	 */
	public function cursus()
	{
		return new Api42\Cursus($this);
	}

		
	/**
	 * return cursuses
	 *
	 * @return Api42\Skills
	 */
	public function skills()
	{
		return new Api42\Skills($this);
	}

		
	/**
	 * return cursuses
	 *
	 * @return Api42\Coalitions
	 */
	public function coalitions()
	{
		return new Api42\Coalitions($this);
	}
		
	/**
	 * return cursuses
	 *
	 * @return Api42\Projects
	 */
	public function projects()
	{
		return new Api42\Projects($this);
	}	

    /**
	 * convert response from api to paginator
	 *
	 * @param array $datas
	 * @return void
	 */
    public function toPaginator($datas)
    {
		$total = $datas['total'] ? $datas['total'] : 0;
		$perPage = $datas['perPage'] ? $datas['perPage'] : 1;
		$page = $datas['page'] ? $datas['page'] : 1;
        return new LengthAwarePaginator($datas['datas'], $total, $perPage, $page);
    }

}
