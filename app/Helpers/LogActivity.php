<?php
namespace App\Helpers;
use App\Models\Audit as LogActivityModel;

use Request;

class LogActivity
{


    public static function add($subject, $adtDataOld, $adtDataNew)
    {
  	/* 	$log = [];
    	$log['subject'] = $subject;
    	$log['url'] = Request::fullUrl();
    	$log['method'] = Request::method();
    	$log['ip'] = Request::ip();
    	$log['agent'] = Request::header('user-agent');
    	$log['user_id'] = auth()->check() ? auth()->user()->id : 1;
	*/

      	$log = [];
    	$log['adtSubject'] = $subject;
    	$log['adtURL'] = Request::fullUrl();
    	$log['adtMethod'] = Request::method();
    	$log['adtIP'] = Request::ip();
    	$log['adtUserAgent'] = Request::header('user-agent');
		$log['adtDataOld'] = $adtDataOld;
		$log['adtDataNew'] = $adtDataNew;
    	$log['created_by'] = auth()->check() ? auth()->user()->id : 1;

    	LogActivityModel::create($log);
        //$request->user()->id
    }

	

	

    public static function logActivityLists()
    {
    	return LogActivityModel::latest()->get();
    }


}