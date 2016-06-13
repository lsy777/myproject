<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
class GrowController extends Controller
{	
	//运行产生每天收益
    public function run(){
    	$today = date('Y-m-d');
    	$tasks = DB::table('tasks')->where('enddate','>=',$today)->get();
    	foreach($tasks as $t){
    		$t->paytime = $today;
    		$t = (array)$t;
    		unset($t['tid']);
    		unset($t['enddate']);
    		DB::table('grows')->insert($t);
    	}
    }
}
