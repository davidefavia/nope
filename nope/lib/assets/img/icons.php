<?php

$d = 200;
$file = "icon-";
$default = ['ddd','999'];
$colors = [
    'zip' => ['a59072','857054'],
    'gzip' => ['a59072','857054'],
    'gz' => ['a59072','857054'],
    'tgz' => ['a59072','857054'],
    'md' => ['C8D88F','86AE6F'],
    'pdf' => ['F03C02','fff'],
    'mp3' => ['4DB3B3','0B486B'],
    'mpg' => ['4DB3B3','0B486B'],
    'avi' => ['EFCD76','FC913A'],
    'mov' => ['EFCD76','FC913A'],
    'mpeg' => ['EFCD76','FC913A'],
    'latex' => ['ddbb99','9a7856'],
    'xls' => ['007233','fff'],
    'ppt' => ['dd5900','fff'],
    'pps' => ['dd5900','fff'],
    'doc' => ['00188f','fff'],
    'docx' => ['00188f','fff'],
    'php' => ['9999cc','262633'],
    'perl' => ['004065','F0F0F0'],
    'Blip.tv' => ['fff','f00'],
    'Dailymotion' => ['006B99','D29811'],
    'YouTube' => ['f00','fff'],
];

$m = [
    'default' => 'Default',
    'application/pdf' => 'pdf',
    'application/zip' => 'zip',
    'text/x-markdown' => 'md',
    'application/x-php' => 'php',
    'video/avi' => 'avi',
    'video/msvideo' => 'avi',
    'video/x-msvideo' => 'avi',
    'text/plain' => 'txt',
    'application/msword' => 'doc',
    'application/x-compressed' => 'gz',
    'application/x-gzip' => 'gzip',
    'multipart/x-gzip' => 'gzip',
    'image/x-icon' => 'ico',
    'application/x-latex' => 'latex',
    'video/quicktime' => 'mov',
    'audio/mpeg3' => 'mp3',
    'audio/x-mpeg-3' => 'mp3',
    'video/mpeg' => 'mp3',
    'video/x-mpeg' => 'mp3',
    'video/mpeg' => 'mpeg',
    'audio/mpeg' => 'mpg',
    'text/x-script.perl' => 'perl',
    'application/mspowerpoint' => 'ppt',
    'application/vnd.ms-powerpoint' => 'pps',
    'application/gnutar' => 'tgz',
    'application/excel' => 'xls',
    'application/vnd.ms-excel' => 'xls',
    'application/x-excel' => 'xls',
    'application/x-msexcel' => 'xls',
    'application/x-zip-compressed' => 'zip',
    'multipart/x-zip' => 'zip',
    // providers
    'Blip.tv' => 'Blip.tv',
    'Dailymotion' => 'Dailymotion',
    'Deviantart' => 'Deviantart',
    'Flickr' => 'Flickr',
    'Hulu' => 'Hulu',
    'Imgur' => 'Imgur',
    'Instagram' => 'Instagram',
    'Polldaddy' => 'Polldaddy',
    'Prezi' => 'Prezi',
    'Slideshare' => 'Slideshare',
    'SoundCloud' => 'SoundCloud',
    'Ted' => 'Ted',
    'Twitter' => 'Twitter',
    'Vimeo' => 'Vimeo',
    'YouTube' => 'YouTube',
];


foreach($m as $k => $v) {
    $k = str_replace('/','-',$k);
    $color = $colors[$v]?$colors[$v]:$default;
    #var_dump($color);
    $colorUrl = "http://placehold.it/$d/{$color[0]}/{$color[1]}.png&text=".($v);
    $c = file_get_contents($colorUrl);
    file_put_contents($file.$k.'.png',$c);
    echo $k . ' - '. $colorUrl . '<br/>';
}


?>
