<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'address', 'salary_discussed','salary_amount',
        'company_pan_number', 'company_pan_file',
        'personal_aadhar_number', 'personal_aadhar_file',
        'personal_pan_number', 'personal_pan_file', 'additional_documents','is_verified',
    ];

    protected $casts = [
        'additional_documents' => 'array',
    ];
}

