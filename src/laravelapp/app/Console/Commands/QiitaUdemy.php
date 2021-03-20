<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
    {
        $qiitaArticles = $this->getQiitaArticles();
        $genUdemy      = $this->genUrlUdemy($qiitaArticles);
        print_r($genUdemy);
    }

    public function getQiitaArticles(){
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
    
    
    
    public function genUrlUdemy($qiitaArticles){
        $qiitaUdemy =[];
        $j = 0;
        foreach($qiitaArticles as $key=>$qiitaArticle){
            for($i = 0; $i < count($qiitaArticles[$key]); $i++){
                preg_match_all('(https?://[\w/:%#\$&\?\(\)~\.=\+\-]+)', $qiitaArticle[$i]['rendered_body'], $arrdata);
                $arr = array_unique($arrdata[0]);
                foreach($arr as $value){
                   if (preg_match("/udemy.com/", $value) && $value != 'https://www.udemy.com' && $value != 'https://www.udemy.com/') {
                    $qiitaUdemy[$j]['id'] = $qiitaArticle[$i]['id'];
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
    
}
