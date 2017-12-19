<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
use App\Meeting;

class MeetingsController extends Controller
{



/////////APIs

   public function addMeeting(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|max:191',
			'description' => 'max:191',
			'meeting_time' => 'required|date_format:Y-m-d H:i:s',
		]);

		if($validator->fails()) {
			return ['success' => 'false', 'errors' => $validator->errors()];
		} else {

			$meeting = new Meeting;

	    	$meeting->name = $request['name'];
	    	$meeting->description = $request['description'];
	    	$meeting->meeting_time = $request['meeting_time'];
	    	$meeting->user_id = Auth::id();
	    	$meeting->save();

	    	if(is_array($request['contacts'])) {
	    		$this->addContacts($meeting->id, $request['contacts']);
	    	}

			return ['success' => 'true'];
		}

	}


	public function addContacts($meetingId, array $contacts)
	{
		if(is_array($contacts)) {
			foreach($contacts as $contact) {
			
			$meetingContact = new \App\MeetingContact;

			$meetingContact->user_id = Auth::id();
			$meetingContact->meeting_id = $meetingId;
			$meetingContact->contact_id = $contact;

			$meetingContact->save();
			}
		}

		
	}

	public function editMeeting($meeting, Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|max:191',
			'description' => 'max:191',
			'meeting_time' => 'required|date_format:YYYY-MM-DD H:i:s',
		]);

		if($validator->fails()) {
			return ['success' => 'false'];
		} else {

			$meeting = Meeting::where(['user_id' => Auth::id(), 'id' => $meeting])->first();

			// dd($meeting);

			if($meeting) {
				$meeting->name = $request['name'];
		    	$meeting->description = ($request['description']) ?: '';
		    	$meeting->meeting_time = $request['meeting_time'];


		    	$meeting->save();

				return ['success' => 'true'];
			} else {
				return ['success' => 'false'];
			}

	    	
		}

	}


	public function deleteMeeting($meeting)
	{
		$meeting = Meeting::where(['user_id' => Auth::id(), 'id' => $meeting])->first();

		if($meeting) {
			if($meeting->delete()) return ['success' => 'true'];
			else return ['success' => 'false'];
		}
		else return ['success' => 'false'];

	}


	public function deleteMeetings(Request $request)
    {
    	if(is_array($request['meetings'])) {
    		foreach($request['meetings'] as $meeting) {

    		$userMeeting = Meeting::where(['user_id' => Auth::id(), 'id' => $meeting])->first();

    		$userMeeting->delete();

    		// else return ['success' => 'false'];
    		}

    	}
	
    	 return ['success' => 'true'];
    }

    public function getAllMeetings()
    {
		return Meeting::where('user_id', Auth::id())->get();
    }

    public function getMeetingById($meeting)
    {
    	return Meeting::where(['user_id' => Auth::id(), 'id' => $meeting])->with('meetingContacts', 'meetingContacts.contact')->first();
    }




    public function addMeetingContacts($meeting, Request $request)
    {

    	if(is_array($request['contacts'])) {

			foreach($request['contacts'] as $contact) {

			    		$meetingContact = new \App\MeetingContact;


			    		$meetingContact->user_id = Auth::id();

			    		$meetingContact->meeting_id = $meeting;

			    		$meetingContact->contact_id = $contact;

			    		$meetingContact->save();



			    	}

    	}

    	return ['success' => 'true'];

    }


    public function deleteMeetingContacts($meeting, Request $request)
    {

    	if(is_array($request['contacts'])) {

    		foreach($request['contacts'] as $contact) {

    		$meetingContact = \App\MeetingContact::where(['user_id' => Auth::id(), 'meeting_id' => $meeting, 'contact_id' => $contact])->first();

    		$meetingContact->delete();

    		
    		}
    	}
    		else return ['success' => 'false', 'message' => 'No contact selected'];
    	
    }

}
