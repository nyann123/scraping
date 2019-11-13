<?php
if($_POST){
  //検索ワードをエンコード
  $keyword = urlencode($_POST['inputword']);

  for($offset = 0;$offset < 100; $offset+=20){

    $json = file_get_contents("https://search-api.itp.ne.jp/search?size=20&from=${offset}&sortby=01&media=pc&kw=${keyword}");
    $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
    $arr = json_decode($json,true);
  
    foreach ($arr['hits']['hits'] as $datas){
  
      // //会社名
      $company = $datas['_source']['ki']['name'];
      // //電話番号
      $tel = $datas['_source']['ki']['tel1'];
      // //住所
      $address = $datas['_source']['ki']['jusyo'].$datas['_source']['ki']['jyusyo_banti'];
      // //最寄り駅
      $station = $datas['_source']['ki']['n_station'][0]['name']."/".$datas['_source']['ki']['n_station'][1]['name'];
      // //URL
      $url = $datas['_source']['ki']['url_official'];
  
      $array[] = ['title' => $company,
                  'tel' => $tel,
                  'address' => $address,
                  'station' => $station,
                  'url' => $url,
                ];
  
    }
  }
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

  <?php if($_POST): ?>
    <table border="1">
      <tr>
        <th></th>
        <th>会社名</th>
        <th>電話番号</th>
        <th>住所</th>
        <th>最寄り駅</th>
        <th>URL</th>
      </tr>
      <?php foreach ($array as $index => $data):?>
        <tr>
          <th><?= $index +1 ?></th>
          <th><?= $data['title']?></th>
          <th><?= $data['tel']?></th>
          <th><?= $data['address']?></th>
          <th><?= $data['station'] ?></th>
          <th><a href="<?= $data['url']?>"><?= $data['url']?></a></th>
        </tr>
      <?php endforeach?>
    </table>
  <?php endif?>

  </body>
</html>