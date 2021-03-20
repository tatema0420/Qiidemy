<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QiitaArticle extends Model
{
    // テーブルの紐付け
    protected $table = 'qiita_article';

    protected $fillable = ['qiita_url', 'title'];
}
