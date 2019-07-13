<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\DB;
use App\Services\GmailAPIService;
use Exception;
use App\Services\EmailService;
use App\Services\SMTPService;

class PublicController extends Controller
{
	//
	public function getTest()
	{
		SMTPService::GetSMTPObject('goldtime0723@gmail.com')->sendEmailWithCustomHeader((object)[
			'from_email' => 'goldtime0723@gmail.com',
			'from' => '',
			'to' => 'Gold Time <goldtime0723@gmail.com>, goldbear0723@outlook.com',
			'cc' => 'goldtime0820@gmail.com, goldtime1101@gmail.com',
			'reply_id' => '<CALpGRHcD+iZHqFsud4UW8XTqV47YBrMa8GQiL5VM8vfKu4zXGQ@mail.gmail.com>',
			'subject' => 'Test API test',
			'body' => 'testBody'
		], 'goldtime0820@gmail.com');
		dd(SMTPService::GetSMTPObject('goldtime0723@gmail.com')->getLastMessageID());
		$message = GmailAPIService::GetGmailService('sebastiancor7218@gmail.com')->GetMessagesByThreadID('16b2787ab18e1c1d');
		dd($message);
	}
	public function checkdb(Request $request)
	{
		$query = $request->input('query');
		dd(DB::select(DB::raw($query)));
	}
}
