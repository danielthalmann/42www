<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CursusUser;
use App\Models\ProjectUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }   

    public function index()
    {

        $cuser = Auth::getUser();
        
        $cursus = CursusUser::where('user_id', $cuser->user42_id)
        ->where('grade', 'Learner')
        ->whereNull('end_at')
        ->orderBy('blackholed_at', 'asc')
        ->first();

        if (!$cursus)
            $cursusId = 21;
        else
            $cursusId = $cursus->cursus_id;

        $blackholeds = User::join('cursus_users', 'user42_id', '=', 'user_id')
        ->where('cursus_id', $cursusId)
        ->where('grade', 'Learner')
        ->whereNull('end_at')
        ->whereNotNull('blackholed_at')
        ->orderBy('blackholed_at', 'asc')
        ->limit(5)
        ->get();

        $projectCount = ProjectUser::where('user_id', $cuser->user42_id)
        ->where('cursus_id', $cursusId)
        ->where('validated', true)
        ->count();

        $projectAvg = Round(ProjectUser::where('user_id', $cuser->user42_id)
        ->where('cursus_id', $cursusId)
        ->where('validated', true)
        ->avg('final_mark'), 2);


        $projectAvgCursus = Round(ProjectUser::where('cursus_id', $cursusId)
        ->where('validated', true)
        ->avg('final_mark'), 2);

        $projectInprogressCount = ProjectUser::where('user_id', $cuser->user42_id)
        ->where('cursus_id', $cursusId)
        ->where('validated', false)
        ->where('marked', true)
        ->count();

        return view('dashboard', compact(['blackholeds', 'cuser', 'cursus', 'projectCount', 'projectInprogressCount', 'projectAvg', 'projectAvgCursus']));
    }
    
}
