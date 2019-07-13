<?php

namespace App\Http\Controllers;

use App\Http\Requests\FollowUp\CreateFollowUpRequest;
use App\Services \ {
FollowUpService, EmailService,GmailAPIService
};
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\GeneralException;

class FollowupController extends Controller
{
	/**
	 * @param followUpdata
	 *{
	 *	"email":"",
	 *	"followup_type" : "",
	 *	"thread_id": "",
	 *	"message_id" : "",
	 *}
	 */
	public function postFollowup(CreateFollowUpRequest $request)
	{
		$emailAddress = $request->input('email');
		$followUpData = $request->only(['followup_type', 'message_id']);

		//Get Email
		$email = GmailAPIService::GetGmailService($emailAddress)->GetMessageByMessageID($followUpData['message_id']);
		$email->save();

		$toEmails = ($email["to"] ? $email["to"] . ', ' : '') . ($email["cc"] ? $email["cc"] . ', ' : '') . ($email["bcc"] ? $email["bcc"] . ', ' : '');

		//Create FollowUp
		$followUpData['sender_email'] = $emailAddress;
		$followUpData['to_emails'] = EmailService::getService()->getRealEmail($toEmails);
		$followUpData['thread_id'] = $email['thread_id'];
		$followUp = FollowUpService::getService()->createFollowUp($followUpData);
		$followUp->initFollowUp();

		//Sacve FollowUp
		$email->followup()->save($followUp);
		return response()->json(['error' => 'false'], Response::HTTP_OK);
	}
}
