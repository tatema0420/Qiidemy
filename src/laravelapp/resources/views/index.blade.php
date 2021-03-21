<!-- Stored in resources/views/index.blade.php -->

@extends('parts.main')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
 
<div class="chart-container" style="position: relative; width: 100%; height: 300px;">
	<canvas id="myChart"></canvas>
</div>

<div class="video_list">
    @foreach ($udemyRankingData as $value)
    <div class="ogp_card clearfix">
        <img src="{{$value->udemy_image_url}}" alt="{{$value->udemy_title}}" width="100" height="100">
        <p class="title">記事数：{{$value->cnt_qiita_id}}　
            <a href="/detail/{{$value->udemy_id}}" >{{$value->udemy_title}}</a>
        </p>{{$value->udemy_short_description}}
        <p class="card_bt">
            <a class="card_link" href="{{$value->udemy_url}}" >コースを確認する</a>
            <a class="card_link" href="サイトURL" >詳細を見る</a>
        </p>
    </div>
    @endforeach
</div>

<script>
    var ctx = document.getElementById('myChart').getContext('2d');

	var chart = new Chart(ctx, {
		type: 'horizontalBar',
		data: {
			labels: ['{{$udemyRankingData[0]->udemy_title}}', '{{$udemyRankingData[1]->udemy_title}}', '{{$udemyRankingData[2]->udemy_title}}', '{{$udemyRankingData[3]->udemy_title}}', '{{$udemyRankingData[4]->udemy_title}}'],
			datasets: [{
				label: 'Udemyの動画紹介記事数',
				data: ['{{$udemyRankingData[0]->cnt_qiita_id}}', '{{$udemyRankingData[1]->cnt_qiita_id}}', '{{$udemyRankingData[2]->cnt_qiita_id}}', '{{$udemyRankingData[3]->cnt_qiita_id}}', '{{$udemyRankingData[4]->cnt_qiita_id}}'],
				backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                fontSize: 5
			}]
		},
		options: {
            maintainAspectRatio: false,
            scales: {
      yAxes: [
        //y軸
        {
          ticks: {
            //軸のメモリ
            beginAtZero: true, //0から始まる
            fontSize: 10
          },
          gridLines: {
            //y軸の網線
            display: false //表示するか否か
          },
          scaleLabel: {
            //表示されるy軸の名称について
            display: true, //表示するか否か
            labelString: "Udemyの動画タイトル",
            fontSize: 15
          }
        }
      ],
      xAxes: [
        //x軸
        {
          ticks: {
            autoSkip: false, //横幅が狭くなったときに表示を間引くか否か
            maxRotation: 90, //下のと合わせて表示される角度を決める
            minRoation: 90 //横幅を最小にしたときに縦に表示される
          },
          gridLines: {
            //x軸の網線
            display: false
          },
          scaleLabel: {
            //表示されるx軸の名称について
            display: true,
            labelString: "Qiitaの記事数",
            fontSize: 15
          }
        }
      ]
    },
		}
	});
</script>
@endsection