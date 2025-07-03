<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ContactListImport;

class MailerContact extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $table = 'mailer_contact';
  protected $guarded = [];

  public function importedContacts()
  {
    return $this->hasMany(ContactListImport::class, 'list_id');
  }
}
