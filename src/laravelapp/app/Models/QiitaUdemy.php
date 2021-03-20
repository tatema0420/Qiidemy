<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QiitaUdemy extends Model
{
    protected $table = 'qiita_udemy';
    protected $fillable = ['udemy_id', 'qiita_id'];
}
