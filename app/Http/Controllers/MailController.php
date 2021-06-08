<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller {

    public static function basic_email($to, $from, $subject, $content) {
       $data = array('name' => env('APP_NAME'));
    
       Mail::send([], [], function($message) use ($to, $from, $subject, $content) {
          $message->to($to, $content)->subject($subject);
          $message->from($from, env('APP_NAME'));
       });
    }

    public static function html_email($to, $from, $subject, $content) {
       $data = array('name' => env('APP_NAME'));
       Mail::send('mail', $data, function($message) use ($to, $from, $subject, $content) {
          $message->to($to, $content)->subject($subject);
          $message->from($from, env('APP_NAME'));
       });
    }

    public static function attachment_email($to, $from, $subject, $content) {
       $data = array('name' => env('APP_NAME'));
       Mail::send('mail', $data, function($message) use ($to, $from, $subject, $content) {
          $message->to($to, $content)->subject($subject);
          $message->attach('C:\laravel-master\laravel\public\uploads\image.png');
          $message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
          $message->from($from, env('APP_NAME'));
       });
    }

 }