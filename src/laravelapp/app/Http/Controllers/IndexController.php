<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\QiitaArticle;
use App\Models\UdemyVideo;
use App\Models\QiitaUdemy;

class IndexController extends Controller
{

    public function index(){
        $UdemyVideo = new UdemyVideo();
        $QiitaArticle = new QiitaArticle();
        $udemyRankingData = $UdemyVideo->getUdemyRankingData(20);
        for ($i = 0; $i<count($udemyRankingData); $i++){
            $udemyRankingData[$i]->qiita = $QiitaArticle->getQiitaArticleInfo($udemyRankingData[$i]->udemy_id);
        }
        return view('index',compact('udemyRankingData'));
    }
}
