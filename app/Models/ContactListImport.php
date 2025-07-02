<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MailerContact;

class ContactListImport extends Model
{
    use HasFactory;
    protected $table = 'imported_contacts';
    protected $guarded = [];

    public function list()
    {
        return $this->belongsTo(MailerContact::class, 'list_id', 'id');
    }
}
