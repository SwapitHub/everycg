<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailOpen;
use App\Models\MailerContact;
use Illuminate\Support\Facades\DB;

class EmailTrackingController extends Controller
{
    public function track(Request $request)
    {
        $contactId = $request->query('contact');
        $listId = $request->query('list');
        $IP = request()->ip();
        $email = $request->query('email');
        EmailOpen::updateOrCreate(
            ['contact_id' => $contactId, 'mailer_contact_id' => $listId],
            ['contact_id' => $contactId, 'mailer_contact_id' => $listId, 'email' => $email, 'ip' => $IP, 'opened_at' => now()]
        );
        $pixel = base64_decode(
            'R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=='
        );
        return response($pixel)->header('Content-Type', 'image/gif');
    }


    public function getOpenRate($listId)
    {
        // Total contacts in the list
        $totalContacts = DB::table('imported_contacts')
            ->where('list_id', $listId)
            ->count();

        // Total opens for contacts in the list
        $opened = DB::table('email_opens')
            ->join('imported_contacts', 'email_opens.mailer_contact_id', '=', 'imported_contacts.id')
            ->where('imported_contacts.list_id', $listId)
            ->count();

        return round(($opened / max($totalContacts, 1)) * 100, 2);
    }
}
