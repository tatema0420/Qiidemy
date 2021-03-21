<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UdemyVideo extends Model
{
    // テーブルの紐付け
    protected $table = 'udemy_video';
    protected $fillable = ['udemy_url', 'title', 'image_url', 'description'];

    public function getUdemyRankingData($limit){
        $arrUdemyRankingData = DB::select(" 
            SELECT 
                uv.id as udemy_id,
                uv.title as udemy_title,
                uv.udemy_url as udemy_url,
                count(qiita_id) as cnt_qiita_id,
                uv.udemy_image_url as udemy_image_url,
                uv.short_description as udemy_short_description
            FROM 
                udemy_video uv 
            left join 
                qiita_udemy qu on qu.udemy_id = uv.id 
            WHERE 
                udemy_id <> 0 
                AND
                uv.title <> '' 
            GROUP BY 
                (qu.udemy_id) 
            ORDER BY 
                cnt_qiita_id DESC
            LIMIT $limit
            ;
        
        ");
        return $arrUdemyRankingData;
    }
}
