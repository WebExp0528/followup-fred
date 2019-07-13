<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Followup;
use App\Models\Email;

class MemberController extends Controller
{

	/*
	 * Get dashboard
	 */
	public function getDashboard(Request $request)
	{

		$email = $request->input('email');

		$followups = Followup::where('sender_email', $email)->get();

		// If followups found for that email, load user, email and logs too
		if ($followups) {
			$followups->load('user', 'email', 'logs');
		}

		return view('dashboard', ['followups' => $followups]);
	}

}
