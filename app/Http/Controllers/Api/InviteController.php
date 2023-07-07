<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InviteController extends Controller
{
    public function getFriendsNotMembers(Request $request, FriendController $friendController)
    {
        $friends = $friendController->showFriends($request);

        $not_members_or_admins = [];
        foreach ($friends as $friend) {
            $is_admin = $friend->pages()->find($request->page_id);
            $is_member = $friend->memberPages()->find($request->page_id);

            if ($is_admin == null && $is_member == null)
                $not_members_or_admins[] = $friend;
        }

        return response()->json([
            'Message' => 'success',
            'Not Members or Admins' => $not_members_or_admins
        ]);
    }

    public function send(Request $request)
    {
        $request->user()->inviters()->attach($request->id, ['page_id' => $request->page_id]);

        // TODO : Send Notification to Receiver

        return response()->json(['Message' => 'Success']);
    }

    public function cancel(Request $request)
    {
        $request->user()->inviters()
            ->where([
                ['invites.page_id', $request->page_id],
                ['invites.receiver_id', $request->receiver_id]
            ])
            ->detach($request->receiver_id);

        // TODO : Delete Notification From Database.
        // TODO : Cancel Notification to Receiver.

        return response()->json([
            'Message' => 'success'
        ]);
    }

    public function accept(Request $request)
    {
        $request->user()->inviteesOne()
            ->where('page_id', $request->id)
            ->delete();

        FollowPageController::follow($request);

        // TODO : Send Notification to All Senders

        return response()->json(['Message' => 'Success']);
    }

    public function reject(Request $request)
    {
        $request->user()->inviteesOne()
            ->where('page_id', $request->id)
            ->delete();

        return response()->json([
            'Message' => 'success'
        ]);
    }

    public function showInviteeRequest(Request $request)
    {
        $invitee_request = $request->user()->invitees()->get();

        // TODO : Delete Notification from Database

        return response()->json([
            'Message' => 'success',
            'Requests' => $invitee_request
        ]);
    }

    public function showInviterRequest(Request $request)
    {
        $inviter_request = $request->user()->inviters()->get();

        return response()->json([
            'Message' => 'success',
            'Requests' => $inviter_request
        ]);
    }
}
