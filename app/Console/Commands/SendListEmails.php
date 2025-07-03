<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MailerContact;
use App\Mail\CustomEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendListEmails extends Command
{
    protected $signature = 'emails:send-list {--listId=}';
    protected $description = 'Send emails to all contacts in a specific contact list or all lists';

    public function handle()
    {
        $listId = $this->option('listId');

        if ($listId) {
            // Load only the specific list with its contacts
            $lists = MailerContact::with('importedContacts')->where('id', $listId)->get();

            if ($lists->isEmpty()) {
                $this->error("No contact list found with ID {$listId}.");
                return;
            }

            $this->info("Sending emails to list ID: {$listId}");
        } else {
            // Load all lists that have content
            $lists = MailerContact::with('importedContacts')->whereNotNull('email_content')->get();

            if ($lists->isEmpty()) {
                $this->error("No contact lists with email content found.");
                return;
            }

            $this->info("Sending emails to all contact lists with content.");
        }

        foreach ($lists as $mailerContact) {
            $emailContent = $mailerContact->email_content;
            $contacts = $mailerContact->importedContacts;

            if ($contacts->isEmpty()) {
                $this->warn("List '{$mailerContact->name}' has no contacts.");
                continue;
            }

            $this->info("Sending emails to list: {$mailerContact->name} ({$contacts->count()} contacts)");

            $bar = $this->output->createProgressBar($contacts->count());
            $bar->start();

            foreach ($contacts as $contact) {
                $this->info($contact->name);
                // Make sure the contact has a valid email
                if (!empty($contact->email)) {
                    //Log::info("Email sent to {$contact->email} from list {$mailerContact->id} ({$mailerContact->name})");
                   Mail::to($contact->email)->queue(new CustomEmail($contact, $emailContent));
                }
                $bar->advance();
            }

            $bar->finish();
            $this->line("\n✅ Finished sending emails to '{$mailerContact->name}'");
        }

        $this->info("\n🎉 All emails have been queued.");
    }
}
