<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
   protected $table = 'technician';
   protected $primaryKey = 'id';
   public $timestamps = false;

   protected $fillable = ['tech_name', 'company'];
}
