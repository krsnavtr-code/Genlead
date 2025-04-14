<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageList extends Model
{
    use HasFactory;

    protected $table = "manage_lists";

    protected $fillable = [
        'list_name',
        'list_type',
        'description',

    ];
}
