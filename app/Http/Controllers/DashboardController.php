<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }   

    public function index()
    {
        $blackholeds = User::join('cursus_users', 'user42_id', '=', 'user_id')
        ->orderBy('blackholed_at', 'asc')
        ->limit(5)
        ->get();        
        return view('dashboard', compact(['blackholeds']));
    }
    
}
