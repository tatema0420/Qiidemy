<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UdemyVideo extends Model
{
        // テーブルの紐付け
        protected $table = 'udemy_video';

        protected $fillable = ['udemy_url', 'title', 'image_url', 'description'];
}
