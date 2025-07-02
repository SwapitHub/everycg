<?php

namespace App\Imports;

use App\Models\ContactListImport;
use App\Models\MailerContact;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContactImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip row if list_name is missing or empty
        if (empty(trim($row['list_name'] ?? ''))) {
            return null;
        }
        $listId = $this->checkContactList($row['list_name']);

        // Normalize email for consistency
        $email = strtolower(trim($row['email']));

        // Try to find an existing record by email
        $existing = ContactListImport::where('email', $email)->first();

        if ($existing) {
            // Update existing record
            $existing->update([
                'name'    => $row['name'],
                'state'   => $row['state'] ?? '',
                'company' => $row['company'] ?? '',
                'address' => $row['address'] ?? '',
                'list_id' => $listId,
            ]);
            return null; // Returning null because update() doesn't need to create a new model
        }

        // Insert new record if email doesn't exist
        return new ContactListImport([
            'name'    => $row['name'],
            'email'   => $email,
            'state'   => $row['state'] ?? '',
            'company' => $row['company'] ?? '',
            'address' => $row['address'] ?? '',
            'list_id' => $listId,
        ]);
    }

    public function checkContactList($name)
    {
        $contact = MailerContact::whereRaw('LOWER(name) = ?', [strtolower($name)])->first();

        if ($contact) {
            return $contact->id;
        }

        $contact = new MailerContact;
        $contact->name = $name;
        $contact->save();

        return $contact->id;
    }
}
