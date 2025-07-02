<?php

namespace App\Imports;

use App\Models\ContactListImport;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\MailerContact;


class ContactImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $list = MailerContact::where('name', $row['list_name'])->first();
        return new ContactListImport([
            'name'    => $row['name'],
            'email'   => $row['email'],
            'state'   => $row['state']??'',
            'company' => $row['company']??'',
            'address' => $row['address']??'',
            'list_id' => $list?->id ?? null, // set null if list not found
        ]);
    }
}
