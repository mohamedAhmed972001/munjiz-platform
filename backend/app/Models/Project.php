<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
// دي الخطوة اللي ناقصة عشان تحل Error 500
protected $fillable = [
  'title',
  'description',
  'budget',
  'user_id',
  'status'
];
  public function owner() {
      return $this->belongsTo(User::class, 'user_id');
  }
}
