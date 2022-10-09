<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Campus;
use App\Models\Cursus;
use App\Models\Skill;
use App\Services\Api42;
use App\Models\CursusUser;
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
    //    $this->sync_campus($clientApi->campus());
    //    $this->sync_cursus($clientApi->cursus());
        $this->sync_skills($clientApi->skills());
    //    $this->sync_users($clientApi->users());

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

    /**
     * sync users
     *
     * @param App\Services\Api42\Users $userApi
     * @return void
     */
    public function sync_users($userApi)
    {

        $page = 1;
        $campuId = 47; // 42Lausanne

        $us = $userApi->ofCampus($campuId, $page);

        while ( $page != $us->lastPage() )
        {

            foreach($us as $user42)
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

                if (!CursusUser::where('user_id', $user->user42_id)->first())
                {
                    $cursuses = $userApi->cursus($user->user42_id);

                    foreach($cursuses as $cursus)
                    {
                        $cursususer = CursusUser::where('cursus_id', $cursus['cursus_id'])
                            ->where('user_id', $user->user42_id)
                            ->first();
                        
                        if (!$cursususer)
                        {
                            $cursususer = new CursusUser();
                            $cursususer->cursus_id   = $cursus['cursus_id'];
                            $cursususer->user_id     = $user->user42_id;
                        };
    
                        $cursususer->cursus_name = $cursus['cursus']['name'];
                        $cursususer->user_name   = $user->name;
                    
                        $cursususer->grade         = $cursus['grade'];
                        $cursususer->level         = $cursus['level'];
                        $cursususer->begin_at      = $cursus['begin_at'];
                        $cursususer->end_at        = $cursus['end_at'];
                        $cursususer->blackholed_at = $cursus['blackholed_at'];
                        $cursususer->has_coalition = $cursus['has_coalition'];
                        $cursususer->save();
    
                    }

                    usleep(400000);

                }
              
            }

            if ( $page != $us->lastPage() )
            {
                $page++;
                $us = $userApi->ofCampus($campuId, $page);
            }

        }
    }

    public function sync_cursus($cursusApi)
    {
        
        $page = 1;

        $cs = $cursusApi->all($page);

        while ( $page != $cs->lastPage() )
        {

            foreach($cs as $c)
            {

                $cursus = Cursus::where('Cursus_id', $c['id'])->first();

                if(!$cursus)
                {
                    $cursus = new Cursus();

                    $cursus->cursus_id   = $c['id'];
                    $cursus->name        = $c['name'];
                    $cursus->slug        = $c['slug'];

                    $cursus->save();
                }
            }

            if ( $page != $cs->lastPage() )
            {
                $page++;
                $cs = $cursusApi->all($page);
            }

        }

    }

    public function sync_skills($skillsApi)
    {
        
        $page = 1;

        $sks = $skillsApi->all($page);

        while ( $page <= $sks->lastPage() )
        {
            foreach($sks as $s)
            {

                $skill = Skill::where('skill_id', $s['id'])->first();

                if(!$skill)
                {
                    $skill = new Skill();

                    $skill->skill_id   = $s['id'];
                    $skill->name        = $s['name'];
                    $skill->slug        = $s['slug'];

                    $skill->save();
                }
            }

            if ( $page < $sks->lastPage() )
            {
                $page++;
                $sks = $skillsApi->all($page);
            } 
            else
                break;

        }

    }
}
