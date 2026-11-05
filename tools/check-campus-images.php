<?php
declare(strict_types=1);

$root=dirname(__DIR__);
$schools=json_decode((string)file_get_contents($root.'/content/schools.json'),true,512,JSON_THROW_ON_ERROR);
$galleries=json_decode((string)file_get_contents($root.'/content/campus-galleries.json'),true,512,JSON_THROW_ON_ERROR);
$expected=[
    'wagholi'=>'campus-wagholi-actual.webp',
    'kalyani-nagar'=>'campus-kalyani-actual.webp',
    'hadapsar'=>'campus-hadapsar-actual.webp',
];

if(!is_array($schools)||count($schools)!==3)throw new RuntimeException('Expected three school records');
$matched=[];
foreach($schools as $school){
    $slug=(string)($school['slug']??'');
    if(!isset($expected[$slug])||isset($matched[$slug]))throw new RuntimeException("Unexpected or duplicate campus: $slug");
    $matched[$slug]=true;
    $card=(string)($school['cardImage']??'');
    $expectedPath='/assets/images/campuses/'.$expected[$slug];
    if($card!==$expectedPath)throw new RuntimeException("Image mapping mismatch for $slug: $card");
    $asset=$root.'/public'.$card;
    if(!is_file($asset)||filesize($asset)<1024)throw new RuntimeException("Missing or empty campus photo for $slug");
    $size=@getimagesize($asset);
    if(!$size||$size[0]<280||$size[1]<120||($size['mime']??'')!=='image/webp'){
        throw new RuntimeException("Invalid campus WebP for $slug");
    }
    $photos=$galleries[$slug]??[];
    if(!is_array($photos)||!array_filter($photos,static fn($photo):bool=>is_array($photo)&&($photo['image']??'')===$expectedPath&&!empty($photo['approved'])&&!empty($photo['alt']))){
        throw new RuntimeException("School entrance absent from gallery for $slug");
    }
}
echo 'All three campus entrances, mappings and galleries verified'.PHP_EOL;
