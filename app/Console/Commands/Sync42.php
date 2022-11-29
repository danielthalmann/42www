<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Skill;
use App\Models\Campus;
use App\Models\Cursus;
use App\Models\Project;
use App\Services\Api42;
use App\Models\Coalition;
use App\Models\CursusUser;
use App\Models\ProjectUser;
use App\Models\CampusProject;
use App\Models\CursusProject;
use App\Services\ClientOAuth;
use Carbon\Carbon;
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
        $this->sync_cursus($clientApi->cursus());
        $this->sync_skills($clientApi->skills());
        $this->sync_coalitions($clientApi->coalitions());
        $this->sync_projects($clientApi->projects());
        $this->sync_users($clientApi->users(), $clientApi->campus());

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
     * @param App\Services\Api42\Campus $campusApi
     * @return void
     */
    public function sync_users($userApi, $campusApi)
    {

        $page = 1;
        $campuId = 47; // 42Lausanne

        $us = $campusApi->users($campuId, $page);

        while ( $page <= $us->lastPage() )
        {

            foreach($us as $user42)
            {
                $update_user = false;
                
                $user = User::where('user42_id', $user42['id'])->first();

                if (!$user) {
                    $update_user = true;
                }
                else {
                    if (Carbon::parse($user->updated_at)->startOfDay()->lt(Carbon::parse($user42['updated_at'])->startOfDay())) {
                        $update_user = true; 
                    }
                }

                if ($update_user)
                {
                    $this->line($user42['login'] . ' update' . Carbon::parse($user->updated_at) . ' ' . Carbon::parse($user42['updated_at']));

                    $user42 = $userApi->get($user42['id']);

                    if(!$user)
                    {
                        $user = new User();

                        $user->user42_id   = $user42['id'];
                        $user->name        = $user42['displayname'];
                        $user->password    = ''; // Hash::make();
                        $user->email       = $user42['email'];
                    }

                    $user->login       = $user42['login'];
                    $user->first_name  = $user42['first_name'];
                    $user->last_name   = $user42['last_name'];
                    $user->url         = $user42['url'];
                    $user->phone       = $user42['phone'];
                    if (key_exists('image_url', $user42)) {
                        $user->image_url   = $user42['image_url'];
                    }
                    if (key_exists('image', $user42)) {
                        if (key_exists('link', $user42['image'])) {
                            $user->image_url   = $user42['image']['link'];
                        }
                        $user->image_url_large  = $user42['image']['versions']['large'];
                        $user->image_url_medium = $user42['image']['versions']['medium'];
                        $user->image_url_small  = $user42['image']['versions']['small'];
                        $user->image_url_micro  = $user42['image']['versions']['micro'];
                    }

                    $user->correction_point = $user42['correction_point'];
                    $user->pool_month  = $user42['pool_month'];
                    $user->pool_year   = $user42['pool_year'];
                    $user->correction_point = $user42['correction_point'];
                    $user->wallet           = $user42['wallet'];
                    $user->alumni           = $user42['alumni?'];
                    $user->alumnized_at     = $user42['alumnized_at'];

                    $user->created_at       = $user42['created_at'];
                    $user->updated_at       = $user42['updated_at'];

                    $user->save();

                    foreach($user42['projects_users'] as $project)
                    {

                        $projectusers = ProjectUser::where('user_id', $user->user42_id)
                            ->where('project_id', $project['project']['id'])
                            ->first();

                        if (!$projectusers)
                        {
                            $projectusers = new ProjectUser();
                            $projectusers->project_id   = $project['project']['id'];
                            $projectusers->user_id     = $user->user42_id;
                        };

                        if ($project['cursus_ids'])
                            $projectusers->cursus_id        = $project['cursus_ids'][0];
                        $projectusers->occurrence       = $project['occurrence'];
                        $projectusers->final_mark       = $project['final_mark'];
                        $projectusers->status           = $project['status'];
                        $projectusers->validated        = $project['validated?'];
                        $projectusers->current_team_id  = $project['current_team_id'];
                        $projectusers->marked_at        = $project['marked_at'];
                        $projectusers->marked           = $project['marked'];
                        $projectusers->retriable_at     = $project['retriable_at'];
                        $projectusers->save();
                    
                    }

                    foreach($user42['cursus_users'] as $cursus)
                    {
                        $cursususer = CursusUser::where('user_id', $user->user42_id)
                            ->where('cursus_id', $cursus['cursus']['id'])
                            ->first();

                        if (!$cursususer)
                        {
                            $cursususer = new CursusUser();
                            $cursususer->cursus_id   = $cursus['cursus']['id'];
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

                    usleep(100000);
                } else{
                    $this->line($user42['login'] . ' no need update');

                }
              
            }

            if ( $page < $us->lastPage() )
            {
                $page++;
                $us = $userApi->ofCampus($campuId, $page);
            }
            else
                break;

        }
    }

    public function sync_cursus($cursusApi)
    {
        
        $page = 1;

        $cs = $cursusApi->all($page);

        while ( $page <= $cs->lastPage() )
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

            if ( $page < $cs->lastPage() )
            {
                $page++;
                $cs = $cursusApi->all($page);
            }
            else
                break;

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
    
    public function sync_coalitions($coalitionsApi)
    {
        
        $page = 1;

        $coas = $coalitionsApi->all($page);

        while ( $page <= $coas->lastPage() )
        {
            foreach($coas as $s)
            {

                $coalition = Coalition::where('coalition_id', $s['id'])->first();

                if(!$coalition)
                {
                    $coalition = new Coalition();

                    $coalition->coalition_id        = $s['id'];
                    $coalition->name                = $s['name'];
                    $coalition->slug                = $s['slug'];
                    $coalition->image_url           = $s['image_url'];
                    $coalition->color               = $s['color'];
                    $coalition->score               = $s['score'];
                    $coalition->coalition_user_id   = $s['user_id'];

                    $coalition->save();
                }
            }

            if ( $page < $coas->lastPage() )
            {
                $page++;
                $coas = $coalitionsApi->all($page);
            } 
            else
                break;

        }

    }

    public function sync_projects($projectsApi)
    {
        
        $page = 1;

        $prjs = $projectsApi->all($page);

        while ( $page <= $prjs->lastPage() )
        {
            foreach($prjs as $prj)
            {

                $project = Project::where('project_id', $prj['id'])->first();

                if(!$project)
                {
                    $project = new Project();
                    

                    $project->project_id         = $prj['id'];
                    $project->name               = $prj['name'];
                    $project->slug               = $prj['slug'];
                    if( $prj['parent'] != null )
                        $project->parent             = $prj['parent']['id'];
                    $project->exam               = $prj['exam'];
                    $project->repository         = $prj['repository'];

                    $project->save();

                }

                foreach($prj['cursus'] as $cur)
                {
                    if (!CursusProject::where('cursus_id', $cur['id'])->where('project_id', $prj['id'])->first())
                    {
                        $cursus = new CursusProject();
                        $cursus->cursus_id = $cur['id'];
                        $cursus->project_id = $prj['id'];
                        $cursus->save();
                    }
                }

                foreach($prj['campus'] as $cur)
                {
                    if (!CampusProject::where('campus_id', $cur['id'])->where('project_id', $prj['id'])->first())
                    {
                        $campus = new CampusProject();
                        $campus->campus_id = $cur['id'];
                        $campus->project_id = $prj['id'];
                        $campus->save();
                    }
                }      
            }

            if ( $page < $prjs->lastPage() )
            {
                $page++;
                $prjs = $projectsApi->all($page);
            } 
            else
                break;

        }

    }

}
