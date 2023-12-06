<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function show_form()
    {
        return view('index');
    }

    public function add_input(Request $request)
    {
        // get input
        $input = strtoupper($request['input']);
        $arr_input = explode(" ", $input);
        $num_position = 0;
        $name = [];
        $age  = 0;
        $city = [];

        // get age
        for ($i = 0; $i < count($arr_input); $i++) {
            if (is_numeric($arr_input[$i]) || (strstr($arr_input[$i], 'TH') !== false) || (strstr($arr_input[$i], 'TAHUN') !== false) || (strstr($arr_input[$i], 'THN') !== false)) {
                $num_position = $i;
                if (is_numeric($arr_input[$i])) {
                    $age = $arr_input[$i];
                }

                if (strstr($arr_input[$i], 'TH') !== false) {
                    $age = explode("TH", $arr_input[$i])[0];
                }

                if (strstr($arr_input[$i], 'THN') !== false) {
                    $age = explode("THN", $arr_input[$i])[0];
                }

                if (strstr($arr_input[$i], 'TAHUN') !== false) {
                    $age = explode("TAHUN", $arr_input[$i])[0];
                }

                break;
            }
        }

        // get name
        for ($i = 0; $i < $num_position; $i++) {
            array_push($name, $arr_input[$i]);
        }

        $post_after_age = $arr_input[$num_position + 1];

        // get city
        if ($post_after_age == 'TAHUN' || $post_after_age == 'THN' || $post_after_age == 'TH') {
            for ($i = $num_position + 2; $i < count($arr_input); $i++) {
                array_push($city, $arr_input[$i]);
            }
        } else {
            for ($i = $num_position + 1; $i < count($arr_input); $i++) {
                array_push($city, $arr_input[$i]);
            }
        }

        $nameDb = implode(" ", $name);
        $ageDb = $age;
        $cityDb = implode(" ", $city);

        // input data ke database
        DB::table('users')->insert([
            'name' => $nameDb,
            'age' => $ageDb,
            'city' => $cityDb,
            'created_at' => Carbon::now(),
        ]);
        return redirect('/');
    }
}
