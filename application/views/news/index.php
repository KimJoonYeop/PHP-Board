<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>뉴스</title>
</head>
<body>
    <h2><?php echo $title ?></h2>

    <?php foreach ($news as $news_item): ?>
        <h3><?= $news_item['title']?></h3>
        <!-- <div class="main">
            <?= $news_item['text']?>
        </div> -->
        <p><a href='/news/view/<?php echo $news_item['id'] ?>'>상세보기</a></p>

    <?php endforeach ?>
    <?php
     var_dump( $prices['apple'] ?? 300 ); # int(300)
     var_dump( $prices['banana'] ?? 100 ); # int(100)
     var_dump( $prices['lemon'] ?? 100 ); # int(200)
     ?>
</body>
</html>