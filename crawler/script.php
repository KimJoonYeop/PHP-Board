<?php

require 'vendor/autoload.php'; 

use Symfony\Component\DomCrawler\Crawler;

$url = "https://www.samsung.com/sec/smartphones/all-smartphones/";
$html = file_get_contents($url);


$crawler = new Crawler($html);

// $itm_info_title = $crawler -> filter('.line')->count();

    // $crawler -> filter('h1.itm-info-title')->first()->text()
 $prd_name = $crawler -> filter('.item')->count();
    var_dump($prd_name);
// foreach ($crawler as $domElement) {
//     var_dump($domElement->nodeName);
// }