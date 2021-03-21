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
        $UdemyVideo = new UdemyVideo;
        $udemyRankingData = $UdemyVideo->getUdemyRankingData(20);

        return view('index',compact('udemyRankingData'));
    }
}
