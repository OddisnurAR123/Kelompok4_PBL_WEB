<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\PenggunaModel;

use Hash;

class ProfileController extends Controller
{
    public function index()
    {
        // Mendapatkan data pengguna yang sedang login
        return view('profile.index', compact('user'));
    }
}
