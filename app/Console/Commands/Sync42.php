<?php

namespace App\Console\Commands;

use App\Models\Campus;
use App\Services\Api42;
use App\Services\ClientOAuth;
use Illuminate\Console\Command;


class Sync42 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync42:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        

        $clientApi = new Api42(ClientOAuth::make());
        $this->sync_campus($clientApi->campus());

        return 0;
    }

    /**
     * sync campus
     *
     * @param App\Services\Api42\Campus $campusApi
     * @return void
     */
    public function sync_campus($campusApi)
    {

        $page = 1;

        $camps = $campusApi->all($page);

        while ( $page != $camps->lastPage() )
        {
            foreach($camps as $c)
            {
                $campus = Campus::where('campus_id', $c['id'])->first();

                if (!$campus)
                {
                    $campus = new Campus();

                    $campus['campus_id']             = $c['id'];
                    $campus['name']                  = $c['name'];
                    $campus['time_zone']             = $c['time_zone'];
                    $campus['users_count']           = $c['users_count'];
                    $campus['vogsphere_id']          = $c['vogsphere_id'];
                    $campus['country']               = $c['country'];
                    $campus['address']               = $c['address'];
                    $campus['zip']                   = $c['zip'];
                    $campus['city']                  = $c['city'];
                    $campus['website']               = $c['website'];
                    $campus['facebook']              = $c['facebook'];
                    $campus['twitter']               = $c['twitter'];
                    $campus['active']                = $c['active'];
                    $campus['public']                = $c['public'];
                    $campus['email_extension']       = $c['email_extension'];
                    $campus['default_hidden_phone']  = $c['default_hidden_phone'];
                    
                    $campus->save();
                }
            }

            if ( $page != $camps->lastPage() )
            {
                $page++;
                $camps = $campusApi->all($page);
            }

        } 
    }

    public function sync_campus($userApi)
    {

        $user = User::where('user42_id', $user42['id'])->first();

        if(!$user)
        {
            $user = new User();

            $user->user42_id   = $user42['id'];
            $user->name        = $user42['displayname'];
            $user->login       = $user42['login'];
            $user->email       = $user42['email'];
            $user->first_name  = $user42['first_name'];
            $user->last_name   = $user42['last_name'];
            $user->url         = $user42['url'];
            $user->phone       = $user42['phone'];
            $user->image_url   = $user42['image_url'];
            $user->pool_month  = $user42['pool_month'];
            $user->pool_year   = $user42['pool_year'];

            $user->password    = ''; // Hash::make();

            $user->save();
        }    

    }
}
