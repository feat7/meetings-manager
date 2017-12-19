<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GroupsController extends Controller
{

	public function addGroup()
	{

	}

	public function editGroup()
	{

	}

    public function addGroupContacts($group, Request $request)
    {
    	$groupContact = new \App\GroupContact;

    	foreach($request['contacts'] as $contact) {

    		$groupContact->user_id = Auth::id();

    		$groupContact->group_id = $group;

    		$groupContact->contact_id = $contact;

    		$groupContact->save();


    	}

    	return ['success' => 'true'];

    }


    public function deleteGroupContacts($group, Request $request)
    {

    	foreach($request['contacts'] as $contact) {

    		$groupContact = \App\GroupContact::where(['user_id' => Auth::id(), 'group_id' => $group, 'contact_id' => $contact])->first();

    		if($groupContact->delete()) return ['success' => 'true'];

    		else return ['success' => 'false'];
    	}
    }

}
