<?php

namespace App\Http\Controllers;

use App\Models\CursusUser;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
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


        $users = CursusUser::where('cursus_id', $cursusId)
			->join('users', 'users.user42_id', '=', 'cursus_users.user_id')
			->where('grade', 'Learner')
			->orderBy('level', 'desc')
        	->get();

        return view('users', compact(['users', 'cuser']));
    }
    
}

