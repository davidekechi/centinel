<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $site = site();

        $user_id = auth::user()->id;

        if (isset($_GET['camera'])) {
            $camera = $_GET['camera'];
        }else{
            $camera = 'All';
        }

        $user_id = auth::user()->id;

        $today_start = strtotime(date('d-m-Y').' 12:00 AM');

        $tomorrow_start = strtotime(date('d-m-Y', strtotime('+24 hours', time())).' 12:00 AM');

        $time = time();

        //$exit_count = DB::table(site())->where('user_id', $user_id)->where('monthly', 'FALSE')->where('camera_type', 'exit')->count();

        $exit_count = DB::table(site())->where('monthly', 'FALSE')->where('camera_type', 'exit')->where([
                ['created_at', '>', $today_start],
                ['created_at', '<', $tomorrow_start],
            ])->count();

        //$last_car = DB::table(site())->where('user_id', $user_id)->where('monthly', 'FALSE')->where('camera_type', 'exit')->orderBy('created_at', 'desc')->first();

        $last_car = DB::table(site())->where('monthly', 'FALSE')->where('camera_type', 'exit')->where([
                ['created_at', '>', $today_start],
                ['created_at', '<', $tomorrow_start],
            ])->orderBy('created_at', 'desc')->first();

        if ($camera == 'All') {
            //$parking_info = DB::table(site())->where('user_id', $user_id)->where('monthly', 'FALSE')->orderBy('created_at', 'desc')->get();

            $parking_info = DB::table(site())->where('monthly', 'FALSE')->where([
                ['created_at', '>', $today_start],
                ['created_at', '<', $tomorrow_start],
            ])->orderBy('created_at', 'desc')->get();
        }else{
            /*
            $parking_info = DB::table(site())->where('monthly', 'FALSE')->where('camera', $camera)->where([
                ['created_at', '>', $today_start],
                ['created_at', '<', $tomorrow_start],
            ])->orWhere('status', 'open')->orderBy('created_at', 'desc')->get();
            */

            $parking_info = DB::table(site())->where('monthly', 'FALSE')->where('camera', $camera)->where([
                ['created_at', '>', $today_start],
                ['created_at', '<', $tomorrow_start],
            ])->orderBy('created_at', 'desc')->get();
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

        return view('home', [
            'parked_items' => $parking_info,
            'last_car' => $last_car,
            'exit_count' => $exit_count,
            'cameras' => $cameras,
            'camera_name' => $camera,
            'mtd_income' => $mtd_income[0]->amount,
            'mtd_audit' => $mtd_audit,
        ]);
    }

    public function getLatestData()
    {
        $data = request()->all();

        //$last_car = DB::table(site())->where('user_id', $user_id)->where('monthly', 'FALSE')->where('camera_type', 'exit')->orderBy('created_at', 'desc')->first();

        $last_car = DB::table(site())->where('monthly', 'FALSE')->where('camera_type', 'exit')->orderBy('created_at', 'desc')->first();

        if ($data['latest_parking_id'] == "") {
            $latest_audit = TRUE;
        }else{
            $last_car_audit = DB::table(site())->where('parking_id', $data['latest_parking_id'])->value('audit');

            if (is_numeric($last_car_audit)){
                $latest_audit = TRUE;
            }else{
                $latest_audit = FALSE;
            }
        }

        if ($last_car == "") {
            $latest_parking_id = '';
        }else{
            $latest_parking_id = $last_car->parking_id;
        }

        $latest_data = array([
            'parking_id' => $latest_parking_id,
            'audit' => $latest_audit,
        ]);


        //dd(json_decode($tariff_prices));

        return response()->json($latest_data);
    }
}
