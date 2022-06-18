<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

class SynchController extends Controller
{
    public function updateUsers()
    {
        $data = request()->all();

        $attendants = $data['new_data'];

        $attendants = json_decode($attendants);

        $old_attendants = DB::table('users')->get();

        foreach ($old_attendants as $old_attendant){
            if (myArrayContainsWord($attendants, 'email', $old_attendant->email) == false) {
                DB::table('users')->where('email', $old_attendant->email)->delete();
            }
        }

        foreach ($attendants as $attendant){
            $attendant_email = $attendant[0]->email;

            if(DB::table('users')->where('email', $attendant_email)->count() < 1){
                
                DB::table('users')->insert([
                    'id' => $attendant[0]->id,
                    'email' => $attendant[0]->email,
                    'refid' => $attendant[0]->refid,
                    'created_at' => $attendant[0]->created_at,
                    'updated_at' => $attendant[0]->updated_at,
                ]);
                echo $attendant_email.'<br/>';
            }
        }
    }

    public function updateAssignmentData()
    {
        $data = request()->all();

        $attendants = $data['new_data'];

        $attendants = json_decode($attendants);

        $old_attendants = DB::table('assignment')->get();

        foreach ($old_attendants as $old_attendant){
            if (myArrayContainsWord($attendants, 'user', $old_attendant->user) == false) {
                DB::table('assignment')->where('user', $old_attendant->user)->delete();
            }
        }

        foreach ($attendants as $attendant){
            $attendant_refid = $attendant[0]->user;

            if(DB::table('assignment')->where('user', $attendant_refid)->count() < 1){
                
                DB::table('assignment')->insert([
                    'id' => $attendant[0]->id,
                    'site' => $attendant[0]->site,
                    'user' => $attendant[0]->user,
                    'created_at' => $attendant[0]->created_at,
                    'updated_at' => $attendant[0]->updated_at,
                ]);
                echo $attendant_refid.'<br/>';
            }
        }
    }

    public function updateTenantData()
    {

        $data = request()->all();

        $tenant_licenses = $data['new_data'];

        $tenant_licenses = json_decode($tenant_licenses);

        $old_licenses = DB::table('license')->get();

        foreach ($old_licenses as $old_license){
            if (myArrayContainsWord($tenant_licenses, 'plate', $old_license->plate) == false) {
                DB::table('license')->where('plate', $old_license->plate)->delete();
            }
        }

        foreach ($tenant_licenses as $tenant_license){
            $tenant_license_plate = $tenant_license[0]->plate;

            if(DB::table('license')->where('plate', $tenant_license_plate)->count() < 1){
                
                DB::table('license')->insert([
                    'id' => $tenant_license[0]->id,
                    'tenant' => $tenant_license[0]->tenant,
                    'plate' => $tenant_license[0]->plate,
                    'created_at' => $tenant_license[0]->created_at,
                    'updated_at' => $tenant_license[0]->updated_at,
                ]);
                echo $tenant_license_plate.'<br/>';
            }
        }
    }

    public function updateTariffData()
    {
        $data = request()->all();

        $tariff_prices = $data['new_data'];

        $tariff_data = json_decode($tariff_prices);

        if (!empty($tariff_data)) {
            DB::table('tariff')->delete();
            
            DB::table('tariff')->insert([
                'id' => $tariff_data[0][0]->id,
                'site' => $tariff_data[0][0]->site,
                'zero_thirty' => $tariff_data[0][0]->zero_thirty,
                'thirty_one' => $tariff_data[0][0]->thirty_one,
                'one_two' => $tariff_data[0][0]->one_two,
                'two_three' => $tariff_data[0][0]->two_three,
                'three_four' => $tariff_data[0][0]->three_four,
                'four_five' => $tariff_data[0][0]->four_five,
                'five_six' => $tariff_data[0][0]->five_six,
                'six_seven' => $tariff_data[0][0]->six_seven,
                'seven_eight' => $tariff_data[0][0]->seven_eight,
                'eight_plus' => $tariff_data[0][0]->eight_plus,
                'created_at' => $tariff_data[0][0]->created_at,
                'updated_at' => $tariff_data[0][0]->updated_at,
            ]);
            echo $tariff_data[0][0]->id.'<br/>';
        }

        //dd(json_decode($tariff_prices));

        //return response()->json($output);
    }

    public function getParkData()
    {
        $parking_infos = DB::table(site())->get();

        $parking_data = [];

        foreach ($parking_infos as $parking_info){
            $parking_data[] = array([
                'id' => $parking_info->id,
                'user_id' => $parking_info->user_id,
                'amount' => $parking_info->amount,
                'duration' => $parking_info->duration,
                'plate' => $parking_info->plate,
                'monthly' => $parking_info->monthly,
                'image' => $parking_info->image,
                'audit' => $parking_info->audit,
                'comment' => $parking_info->comment,
                'make' => $parking_info->make,
                'model' => $parking_info->model,
                'color' => $parking_info->color,
                'status' => $parking_info->status,
                'camera' => $parking_info->camera,
                'camera_type' => $parking_info->camera_type,
                'parking_id' => $parking_info->parking_id,
                'created_at' => $parking_info->created_at,
                'updated_at' => $parking_info->updated_at,
            ]);
        }

        //dd(json_decode($tariff_prices));

        return response()->json($parking_data);
    }

    public function getCameraData()
    {
        $camera_infos = DB::table('cameras')->get();

        $camera_data = [];

        foreach ($camera_infos as $camera_info){
            $camera_data[] = array([
                'site' => $camera_info->site,
                'level' => $camera_info->level,
                'camera' => $camera_info->camera,
                'camera_type' => $camera_info->camera_type,
                'camera_id' => $camera_info->camera_id,
                'created_at' => $camera_info->created_at,
                'updated_at' => $camera_info->updated_at,
            ]);
        }

        //dd(json_decode($tariff_prices));

        return response()->json($camera_data);
    }
}
