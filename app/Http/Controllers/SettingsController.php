<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function addCamera()
    {
        $data = request()->validate([
            'camera' => 'required',
            'camera_type' => 'required',
            'level' => 'required',
        ]);

        $user_id = auth::user()->id;
        $level = $data['level'];
        $camera = $data['camera'];
        $camera_type = $data['camera_type'];
        $camera_id = mt_rand(111111, 999999);
        $site = site();
        $created_at = time();
        $updated_at = time();
        $created_at_format = date("Y-m-d G:i:s");

        if (DB::table('cameras')->where('camera', $camera)->count() > 0){
            return redirect('/plateinserter?error=Camera with this name already exists!');
        }else{
            DB::table('cameras')->insert([
                'site' => $site,
                'level' => $level,
                'camera' => $camera,
                'camera_type' => $camera_type,
                'camera_id' => $camera_id,
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ]);

            return redirect('/plateinserter?success=Camera has been added successfully!');
        }
    }
}
