<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Weidner\Goutte\GoutteFacade as GoutteFacade;
use Illuminate\Support\Facades\DB;
use App\Models\QiitaArticle;
use App\Models\UdemyVideo;

class QiitaUdemy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:qiita_udemy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the link to the Udemy video from the Qiita article, and get the title and description from that link.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   date_default_timezone_set('Asia/Tokyo');
        echo 'getQiitaArticles開始時間'. date('Y-m-d H:i:s') . "\n";
        $qiitaArticles = $this->getQiitaArticles();
        echo 'getQiitaArticles終了時間'. date('Y-m-d H:i:s'). "\n";

        echo 'getUrlUdemyFromQiita開始時間'. date('Y-m-d H:i:s'). "\n";
        $arrQiitaUdemy = $this->getUrlUdemyFromQiita($qiitaArticles);
        echo 'getUrlUdemyFromQiita終了時間'. date('Y-m-d H:i:s'). "\n";

        echo 'scrapingUdemy開始時間' . date('Y-m-d H:i:s'). "\n";
        $scrapingUdemy = $this->scrapingUdemy($arrQiitaUdemy);
        echo 'scrapingUdemy終了時間' . date('Y-m-d H:i:s'). "\n";
    }

    public function getQiitaArticles()
    {
        $qiitaArticles = [];
        for($i = 1; $i <= 9; $i++){
            $url = "https://qiita.com/api/v2/items?page=" . $i . "&per_page=100&query=body:https://udemy.com/";
            $ch = curl_init(); // はじめ
            $headers = array('Authorization: Bearer 461605b7d06b982422af1fea59a5be69063f0226');
            //オプション
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $html =  curl_exec($ch);
            curl_close($ch); 
            $qiitaArticles[$i] = json_decode($html,true);
        }
        $recover = array_values($qiitaArticles);
        return $recover;
    }
    
    
    
    public function getUrlUdemyFromQiita($qiitaArticles)
    {
        $qiitaUdemy =[];
        $j = 0;
        foreach($qiitaArticles as $key=>$qiitaArticle){
            for($i = 0; $i < count($qiitaArticles[$key]); $i++){
                preg_match_all('(https?://[\w/:%#\$&\?\(\)~\.=\+\-]+)', $qiitaArticle[$i]['rendered_body'], $arrdata);
                $arr = array_unique($arrdata[0]);
                foreach($arr as $value){
                   if (preg_match("/udemy.com/", $value) && $value != 'https://www.udemy.com' && $value != 'https://www.udemy.com/' && !preg_match("/user/", $value)) {

                    $qiitaUdemy[$j]['qiita_id'] = $qiitaArticle[$i]['id'];
                    $qiitaUdemy[$j]['qiita_title'] =$qiitaArticle[$i]['title'];
                    $qiitaUdemy[$j]['qiita_url'] = $qiitaArticle[$i]['url'];
                    $qiitaUdemy[$j]['udemy_url'] = $value;
                    $j++;
                   }
                }
            }
        }
        return $qiitaUdemy;
    }

    public function scrapingUdemy($arrQiitaUdemy)
    {
        date_default_timezone_set('Asia/Tokyo');
        foreach($arrQiitaUdemy as $key=>$QiitaUdemy){
            $arrQiitaUdemy[$key]['udemy_title'] = '';
            $arrQiitaUdemy[$key]['udemy_image'] = '';
            $arrQiitaUdemy[$key]['udemy_description'] = '';
            $arrQiitaUdemy[$key]['udemy_id'] = 0;
            $goutte = GoutteFacade::request('GET', $QiitaUdemy['udemy_url']);
            $goutte->filter('.clp-lead__title')->each(function ($node) use (&$arrQiitaUdemy,&$key) {
                $arrQiitaUdemy[$key]['udemy_title'] = $node->text();
            });
            $goutte->filter('.intro-asset--img-aspect--1UbeZ img')->each(function ($node) use (&$arrQiitaUdemy,&$key) {
                $arrQiitaUdemy[$key]['udemy_image'] = $node->attr('src');
            });
            $goutte->filter('div[data-purpose="safely-set-inner-html:description:description"]')->each(function ($node) use (&$arrQiitaUdemy,&$key) {
                $arrQiitaUdemy[$key]['udemy_description'] = $node->html();
            });
            $goutte->filter('body')->each(function ($node) use (&$arrQiitaUdemy,&$key) {
                $arrQiitaUdemy[$key]['udemy_id'] = $node->attr('data-clp-course-id');
            });

            if(empty(QiitaArticle::find($arrQiitaUdemy[$key]['qiita_id']))){
                $qiita_article = [
                    'id' => $arrQiitaUdemy[$key]['qiita_id'],
                    'qiita_url' => $arrQiitaUdemy[$key]['qiita_url'],
                    'title' => $arrQiitaUdemy[$key]['qiita_title'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                DB::table('qiita_article')->insert($qiita_article);
            }
            if(empty(UdemyVideo::find($arrQiitaUdemy[$key]['udemy_id']) && is_null($arrQiitaUdemy[$key]['udemy_id'])) === false){
                $udemy_video = [
                    'id' => $arrQiitaUdemy[$key]['udemy_id'],
                    'title' => $arrQiitaUdemy[$key]['udemy_title'],
                    'udemy_url' => $arrQiitaUdemy[$key]['udemy_url'],
                    'udemy_image_url' => $arrQiitaUdemy[$key]['udemy_image'],
                    'description' => $arrQiitaUdemy[$key]['udemy_description'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                DB::table('udemy_video')->insert($udemy_video);
            }
            if(is_null($arrQiitaUdemy[$key]['udemy_id']) === false){
                $qiita_udemy = [
                    'udemy_id' => $arrQiitaUdemy[$key]['udemy_id'],
                    'qiita_id' => $arrQiitaUdemy[$key]['qiita_id']
                ];
                DB::table('qiita_udemy')->insert($qiita_udemy);
            }
        }
    }
    
}
