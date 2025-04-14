<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPage extends Model
{

    use HasFactory;

    protected $table = 'adminpages';
    protected $fillable = ['admin_page_title', 'admin_page_url', 'page_group', 'can_display'];

    public function group()
    {
        return $this->belongsTo(AdminPageGroup::class, 'page_group');
    }
}
