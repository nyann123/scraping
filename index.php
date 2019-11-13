<?php
// phpQueryの読み込み
require_once("phpQuery-onefile.php");

if($_POST['inputword']){
  $keyword = urlencode($_POST['inputword']);
}

// HTMLの取得
$html = file_get_contents("https://itp.ne.jp/keyword/?keyword=${keyword}&sort=01&sbmap=false");
 
$datas = phpQuery::newDocument($html)->find(".m-article-card__body");

foreach ($datas as $data){

  //会社名
  $company = pq($data)->find("h1")->text();
  //最寄り駅
  $station = str_replace('【最寄駅】', '', pq($data)->find(".m-article-card__lead__caption:eq(0)")->text());
  //電話番号
  $tel = str_replace('【電話番号】', '', pq($data)->find(".m-article-card__lead__caption:eq(1)")->text());
  //住所
  $address = str_replace('【住所】', '', pq($data)->find(".m-article-card__lead__caption:eq(2)")->text());
  //URL
  $url = pq($data)->find("a:eq(2)")->attr('href');

  $array[] = ['title' => $company,
              'station' => $station,
              'tel' => $tel,
              'address' => $address,
              'url' => $url,
            ];

}


?>

<!DOCTYPE html>
<html lang="ja">

  <head>
    <meta charset="utf-8">
    <title>scraping</title>
  </head>
  <body>
    
  <form action="#" method="post">
    <input type="text" name="inputword">
    <button>探す</button>
  </form>

  <?php foreach ($array as $hoge):?>
    <p><?= $hoge['title']?><?= $hoge['station']?><?= $hoge['tel']?><?= $hoge['address']?><?= $hoge['url']?></p>
  <?php endforeach?>

  </body>
</html>