<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Pro;
use App\Att;
use App\Bid;
use DB;
use Auth;

class ProController extends Controller
{   
    protected $middleware = ['App\Http\Middleware\Authenticate' => []];
    public function jie(){
        return view('woyaojiekuan');
    }

    public function jiePost(Request $req){
       
        $this->validate($req,[
            'age' => 'required|in:15,40,80',
            'money' => 'required|integer|between:10,50000000',
            'mobile' => 'required|regex:/^1[3458]\d{9}$/'
            ],[
                'required' => '请填写:attribute!',
                'in' => '请选择年龄!',
                'integer' => '请输入整数!',
                'between' => '请输入10到5000000',
                'mobile.required' => '请填写手机号码!!!',
                'mobile.regex' => '请填写正确的电话号码!!!'

              ]
            );

        $pro = new Pro();
        $att  = new Att();
        //收到的借款金额转为分
        $pro->money = intval($req->money) * 100;
        $pro->mobile = $req->mobile;
        $pro->uid = $req->user()->uid;
        $pro->name = $req->user()->name;
        $pro->save();//此时$pro对应表中刚刚新增出来的哪一行

        //附表
        $att->pid = $pro->pid;
        $att->age = $req->age;
        $att->uid = $req->user()->uid;
        $att->save();
        echo 'ok';
    }

    public function pro($pid){
        $pro = Pro::find($pid);
        return view('pro',['pro'=>$pro]);
    }

    public function touzi(Request $req,$pid){

        $md5 = $req->v_oid . $req->v_pstatus . $req->v_amount . $req->v_moneytype . env('PAY_KEY');
        $md5 = strtoupper(md5($md5));

        if($md5 !== $req->v_md5str){
            return '签名错误';
        }

        $bid = new Bid();
        $pro = Pro::find($pid);

        $user = $req->user();
        $bid->uid = $user->uid;
        $bid->pid = $pid;
        $bid->title = $pro->title;
        $bid->money = $req->v_amount*100;
        $bid->pubtime = time();
        $bid->save();
        
        $pro->revice += $bid->money;
        $pro->save();
        if($pro->revice == $pro->money){
            $this->touziDone($pid);
        }

        return '购买成功！';
    }


    protected function touziDone($pid){
        //1.修改项目状态为2,还款/收益中
        $pro = Pro::find($pid);
        $pro->status = 2;
        $pro->save();
        //2.为投资者，生成收益记录
        //按月循环生成还款记录
        $amount = ($pro->money * $pro->rate / 1200) + ($pro->money / $pro->hrange);//算出每月还款 年利率
        
             
        $row = ['uid'=>$pro->uid,'pid'=>$pro->pid,'title'=>$pro->title];
        $row['amount'] = $amount;
        $row['status'] = 0;

        $today = date('y-m-d');
        for($i=1;$i<=$pro->hrange;$i++){
            $paydate = date('Y-m-d' , strtotime("+ $i months"));
            $row['paydate'] = $paydate;
            DB::table('hks')->insert($row);
        }
        //3.为借款者，生成还款记录
        $bids = Bid::where('pid',$pid)->get();
        $row = [];
        $row['pid'] = $pid;
        $row['title'] = $pro->title;
        $row['enddate'] = date('y-m-d',strtotime("+ {$pro->hrange} months"));
        foreach($bids as $bid){
            $row['uid'] = $bid->uid;
            $row['amount'] = $bid->money * $pro->rate / 36500;
            DB::table('tasks')->insert($row);
        }
    }
    //我的账单
    public function myzd(){
        $user = Auth::user();
        $hks = DB::table('hks')->where('uid',$user->uid)->paginate(2);
        return view('myzd',['hks'=>$hks]);
    }
    //我的投资
    public function mytz(){
        $user = Auth::user();
        $bids = Bid::where('bids.uid',$user->uid)->whereIn('status',[1,2])->join('projects','bids.pid','=','projects.pid')->get();
        return view('mytz',['bids'=>$bids]);
    }
    //我的收益
    public function mysy(){
        $user = Auth::user();
        $sys = DB::table('grows')->where('uid',$user->uid)->orderBy('gid','desc')->get();
        return view('mysy',['sys'=>$sys]);
    }

    public function pay(Request $req){
        $row =[];
        $row['v_amount'] = sprintf('%.2f' ,$req->money);//价格
        $row['v_moneytype'] = 'CNY';//人民币
        $row['v_oid']  = date('Ymd').mt_rand(1000,9999);//订单号
        $row['v_mid'] = '1009001';//商户号
        $row['v_url'] = 'http://localhost/laravel/touzi/'.$req->pid;
        $row['key'] = '#(%#WU)(UFGDKJGNDFG';//商户key
        $row['v_md5info'] = strtoupper( md5(implode('', $row)) );
        return view('pay' , $row);
    }
}
