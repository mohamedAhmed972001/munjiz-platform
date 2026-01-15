<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
  protected $fillable = ['user_id', 'project_id', 'amount', 'message', 'status'];

  public function user() { return $this->belongsTo(User::class); }
}
