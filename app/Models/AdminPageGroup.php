<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPageGroup extends Model
{

    use HasFactory;

    protected $table = 'adminpagegroup';
    protected $fillable = ['group_title', 'group_index'];
}
