<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Contact;

class ContactsController extends Controller
{

	public function addContact(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|max:191',
		]);

		if($validator->fails()) {
			return ['success' => 'false', 'errors' => $validator->errors()];
		} else {

			$contact = new Contact;

	    	$contact->user_id = Auth::id();
	    	$contact->name = $request['name'];
	    	$contact->email = $request['email'];
	    	$contact->mobile = $request['mobile'];

	    	$contact->save();

			return ['success' => 'true'];
		}

	}

	public function editContact($contact, Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|max:191',
		]);

		if($validator->fails()) {
			return ['success' => 'false', 'errors' => $validator->errors()];
		} else {

			$contact = Contact::where(['user_id' => Auth::id(), 'id' => $contact])->first();

			// dd($contact);

			if($contact) {
				$contact->name = $request['name'];
		    	$contact->email = ($request['email']) ?: '';
		    	$contact->mobile = ($request['mobile']) ?: '';

		    	$contact->save();

				return ['success' => 'true'];
			} else {
				return ['success' => 'false'];
			}

	    	
		}

	}


	public function deleteContact($contact)
	{
		$contact = Contact::where(['user_id' => Auth::id(), 'id' => $contact])->first();

		if($contact) {
			if($contact->delete()) return ['success' => 'true'];
			else return ['success' => 'false'];
		}
		else return ['success' => 'false'];

	}

	public function deleteContacts(Request $request)
    {
    	if(is_array($request['contacts'])) {
    		foreach($request['contacts'] as $contact) {

    		$userContact = \App\Contact::where(['user_id' => Auth::id(), 'id' => $contact])->first();

    		$userContact->delete();

    		// else return ['success' => 'false'];
    		}

    	}

    	
    	 return ['success' => 'true'];
    }

    public function getAllContacts()
    {
		return Contact::where('user_id', Auth::id())->get();
    }

    public function getContactById($contact)
    {
    	return Contact::where(['user_id' => Auth::id(), 'id' => $contact])->get();
    }
}
