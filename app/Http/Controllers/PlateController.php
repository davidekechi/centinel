<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

class PlateController extends Controller
{
    public function plate()
    {
        $site = site();

        $user_id = auth::user()->id;

        if (isset($_GET['camera'])) {
            $camera = $_GET['camera'];
        }else{
            $camera = 'All';
        }

        $user_id = auth::user()->id;

        $exit_count = DB::table(site())->where('user_id', $user_id)->where('monthly', 'FALSE')->where('camera_type', 'exit')->count();

        $last_car = DB::table(site())->where('user_id', $user_id)->where('monthly', 'FALSE')->where('camera_type', 'exit')->orderBy('created_at', 'desc')->first();

        if ($camera == 'All') {
            $parking_info = DB::table(site())->where('user_id', $user_id)->where('monthly', 'FALSE')->orderBy('created_at', 'desc')->get();
        }else{
            $parking_info = DB::table(site())->where('user_id', $user_id)->where('monthly', 'FALSE')->where('camera', $camera)->orderBy('created_at', 'desc')->get();
        }

        $cameras = DB::table('cameras')->get();

        $current_month = date('m', time());

        $current_year = date('Y', time());

        $month_start = strtotime('01-'.$current_month.'-'.$current_year);

        $time = time();

        $mtd_income = DB::select("SELECT SUM(amount) AS amount FROM `$site` WHERE camera_type='exit' AND monthly='FALSE' AND status='closed' AND updated_at>='$month_start' AND updated_at<='$time' AND user_id='$user_id'");

        $mtd_audit_count = DB::select("SELECT COUNT(*) AS audit FROM `$site` WHERE camera_type='exit' AND monthly='FALSE' AND status='closed' AND updated_at>='$month_start' AND updated_at<='$time' AND user_id='$user_id'");

        $mtd_audit_value = DB::select("SELECT SUM(audit) AS audit FROM `$site` WHERE camera_type='exit' AND monthly='FALSE' AND status='closed' AND updated_at>='$month_start' AND updated_at<='$time' AND user_id='$user_id'");

        if ($mtd_audit_value[0]->audit == 0 || $mtd_audit_count[0]->audit < 1){
            $mtd_audit = 0;
        }else{
            $mtd_audit = ($mtd_audit_value[0]->audit/($mtd_audit_count[0]->audit * 100))*100;
        }

        return view('plateinserter', [
            'parked_items' => $parking_info,
            'last_car' => $last_car,
            'exit_count' => $exit_count,
            'cameras' => $cameras,
            'camera_name' => $camera,
            'mtd_income' => $mtd_income[0]->amount,
            'mtd_audit' => $mtd_audit,
        ]);
    }

    public function tenant()
    {
        $plates = DB::table('license')->get();

        $output = [];

        foreach ($plates as $plate){
            $output[] = array(
                'id' => $plate->id,
                'tenant_id' => $plate->tenant,
                'plate' => $plate->plate,
            );
        }

        return response()->json($output);
    }

    public function tariff()
    {
        $tariffs = DB::table('tariff')->where('site', site())->get();

        $output = [];

        foreach ($tariffs as $tariff){
            $output[] = array(
                'zero_thirty' => $tariff->zero_thirty,
                'thirty_one' => $tariff->thirty_one,
                'one_two' => $tariff->one_two,
                'two_three' => $tariff->two_three,
                'three_four' => $tariff->three_four,
                'four_five' => $tariff->four_five,
                'five_six' => $tariff->five_six,
                'six_seven' => $tariff->six_seven,
                'seven_eight' => $tariff->seven_eight,
                'eight_plus' => $tariff->eight_plus,
            );
        }

        return response()->json($output);
    }

    public function store()
    {
        $data = request()->validate([
            'plate' => 'required',
            'make' => 'required',
            'model' => 'required',
            'color' => 'required',
            'camera' => 'required',
            'camera_type' => 'required',
        ]);

        $user_id = auth::user()->id;
        $plate = $data['plate'];
        $make = $data['make'];
        $model = $data['model'];
        $color = $data['color'];
        $camera = $data['camera'];
        $camera_type = $data['camera_type'];
        $site = site();
        //$imagePath = request('image')->store('image', 'public');
        $parking_id = mt_rand(111111,999999);
        $created_at = time();
        $updated_at = time();
        $created_at_format = date("Y-m-d G:i:s");

        $tenant_count = DB::table('license')->where('plate', $plate)->count();

        if ($tenant_count > 0) {
            $monthly = 'TRUE';
        }else{
            $monthly = 'FALSE';
        }
        if ($camera_type == "index") {
            DB::table($site)->where('plate', $plate)->where('camera_type', 'index')->where('status', 'open')->delete();

            DB::table($site)->insert([
                'user_id' => $user_id,
                'amount' => 'NA',
                'duration' => 'NA',
                'plate' => $plate,
                'monthly' => $monthly,
                'image' => 'NA',
                'audit' => 'NA',
                'comment' => 'NA',
                'make' => $make,
                'model' => $model,
                'color' => $color,
                'status' => 'open',
                'camera' => $camera,
                'camera_type' => $camera_type,
                'parking_id' => $parking_id,
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ]);
        }else{
            $plate_count = DB::table($site)->where('plate', $plate)->where('camera_type', 'index')->where('status', 'open')->count();

            if ($plate_count > 0) {
                $time = strtotime('+2 hours', time()); //time();

                $plate_index_created = DB::table($site)->where('plate', $plate)->where('camera_type', 'index')->where('status', 'open')->value('created_at');

                $remainder = $time - $plate_index_created;

                $duration = convert($remainder);

                if($duration[0]['hour'] > 0){
                    $hour_words = spellOut($duration[0]['hour']);
                    $next_hour = $duration[0]['hour'] + 1;
                    $next_hour_words = spellOut($next_hour);
                    $next_tariff_point = $hour_words.'_'.$next_hour_words;

                    if ($duration[0]['hour'] >= 8){
                        $amount = DB::table('tariff')->value('eight_plus');
                    }else{
                        $amount = DB::table('tariff')->value($next_tariff_point);
                    }

                }else{
                    if ($duration[0]['minafter'] <= 30){
                        $amount = DB::table('tariff')->value('zero_thirty');
                    }else{
                        $amount = DB::table('tariff')->value('thirty_one');
                    }
                }

                $duration_dec = round(($remainder/3600), 1);

                DB::table($site)->insert([
                    'user_id' => $user_id,
                    'amount' => $amount,
                    'duration' => $duration_dec,
                    'plate' => $plate,
                    'monthly' => $monthly,
                    'image' => 'NA',
                    'audit' => 'PENDING',
                    'comment' => '',
                    'make' => $make,
                    'model' => $model,
                    'color' => $color,
                    'status' => 'closed',
                    'camera' => $camera,
                    'camera_type' => $camera_type,
                    'parking_id' => $parking_id,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ]);

                DB::table($site)->where('plate', $plate)->where('camera_type', 'index')->where('status', 'open')->update([
                    'status' => 'closed',
                ]);                                    
            }else{
                DB::table($site)->insert([
                    'user_id' => $user_id,
                    'amount' => 0,
                    'duration' => '',
                    'plate' => $plate,
                    'monthly' => $monthly,
                    'image' => 'NA',
                    'audit' => 'PENDING',
                    'comment' => '',
                    'make' => $make,
                    'model' => $model,
                    'color' => $color,
                    'status' => 'open',
                    'camera' => $camera,
                    'camera_type' => $camera_type,
                    'parking_id' => $parking_id,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ]);
            }
        }
        return redirect('/plateinserter?success=Car has been added successfully');
    }

    public function update()
    {

        if (request('plate')) {
            $data = request()->validate([
                'amount' => 'required|numeric',
                'comment' => 'required',
                'parking_id' => 'required',
                'plate' => 'required',
            ]);

            $plate = $data['plate'];
        }else{
            $data = request()->validate([
                'amount' => 'required|numeric',
                'comment' => 'required',
                'parking_id' => 'required',
                'replate' => 'required',
            ]);

            $plate = $data['replate'];
        }

        $user_id = auth::user()->id;
        $amount = $data['amount'];
        $comment = $data['comment'];
        $parking_id = $data['parking_id'];

        $old_amount = DB::table(site())->where('parking_id', $parking_id)->value('amount');

        if ($old_amount == $amount) {
            $audit_score = 100;
        }else{
            if (($old_amount > 0) && ($old_amount >= $amount)) {
                $audit_score = ($amount/$old_amount)*100;
            }else{
                $audit_score = 0; 
            }
        }

        $audit = round($audit_score);

        DB::table(site())->where('parking_id', $parking_id)->update([
            'amount' => $amount,
            'audit' => $audit,
            'comment' => $comment,
            'status' => 'closed',
        ]);    

        DB::table(site())->where('plate', $plate)->where('camera_type', 'index')->where('status', 'open')->update([
            'status' => 'closed',
        ]);

        return redirect('/?success=Parking record has been updated successfully');     
    }
}
