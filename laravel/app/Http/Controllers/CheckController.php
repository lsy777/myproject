<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Pro;
use App\Att;
class CheckController extends Controller
{
    //取所有项目
   public function proList(){
        $pros = Pro::orderBy('pid','desc')->get();
        return view('prolist',['pros'=>$pros]);
   }

   //审核项目,修改projects和atts表
   public function check($pid){
        $pro = Pro::find($pid);
        $att = Att::where('pid',$pid)->first();

        if(empty($pro)){
            return redirect('/prolist');
        }

        return view('shenhe',['pro'=>$pro,'att'=>$pid]);
   }

   public function checkPost(Request $req,$pid){
        $pro = Pro::find($pid);
        $att = Att::where('pid',$pid)->first();
        if(empty($pro)){
            return redirect('/prolist');
        }

        $pro->title = $req->title;
        $pro->hrange = $req->hrange;
        $pro->rate = $req ->rate;//百分比    
        $pro->status = $req->status;

        $att->realname = $req->realname;
        $att->gender = $req->gender;
        $att->udesc = $req->udesc;
        if($pro->save() && $att->save()){
            return redirect('/prolist');
        }else{
            return 'error';
        }
    }
}
