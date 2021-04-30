<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QiitaArticle extends Model
{
    // テーブルの紐付け
    protected $table = 'qiita_article';

    protected $fillable = ['qiita_url', 'title'];

    public function getQiitaArticleInfo($udemyId){
        $arrQiitaArticleInfo = DB::select(" 
            SELECT
                qiita_url,
                title
            FROM
                qiita_udemy qu 
            left join
                qiita_article qa on qa.id = qu.qiita_id 
            WHERE 
                qu.udemy_id = $udemyId
        ");
        return $arrQiitaArticleInfo;
    }
}
