<?php

namespace App\Http\Middleware;

use Closure;
use Nette\Mail\Message;
use Nette\Mail\SmtpMailer;
class EmailMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $rs = $next($request);
        if($request->user()){
            $mail = new Message;
            $mail->setFrom('laowang <superkeysir@163.com>')
            ->addTo($request->user()->email)
            ->setSubject('测试')
            ->setBody("飞天小邮件");

        $mailer = new SmtpMailer(array(
            'host' => 'smtp.163.com',
            'username' => 'superkeysir',
            'password' => 'admins'
            ));
        $mailer->send($mail);
        }
        return $rs;
    }

}
