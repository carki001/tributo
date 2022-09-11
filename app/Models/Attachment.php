<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'name',
        'path',
        'file_extension',
        'file_type',
        'file_size',
        'record_id',
        'model_name',
        'description',
    ];
}
