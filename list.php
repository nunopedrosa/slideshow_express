<?php

$w=in_array('w',$_GET)?$_GET['w']:1024;
$h=in_array('h',$_GET)?$_GET['h']:1024;
#file_put_contents("list.log","w=$w, h=$h\n");

$MAX_TO_SHOW=10;
function find_all_files($dir) { 
    $root = scandir($dir); 
    foreach($root as $value) { 
        if ($value === '.' || $value === '..') {continue;} 
        if (is_file("$dir/$value")) {
            if (preg_match("/.jp\w+/i", $value)) {$result[]="$dir/$value";continue;}
	} else foreach(find_all_files("$dir/$value") as $value) { $result[]=$value; } 
    } 
    return $result; 
} 

$seenPhotosFileName="photos_seen.lst";
$toSeePhotosFileName="photos_to_see.lst";
$toSeePhotosFileFlag="photos_to_see.flag";

$seenPhotos = array();
#if (file_exists($seenPhotosFileName)) {
#    $seenPhotos= file($seenPhotosFileName,FILE_IGNORE_NEW_LINES);
#}
$files=array();
if (file_exists($toSeePhotosFileName)
    && file_exists($toSeePhotosFileFlag)
    && (time()-filemtime($toSeePhotosFileFlag) < 168*3600)) {
    // file exists and is younger than 168 hours / 7 days
    $files= file($toSeePhotosFileName,FILE_IGNORE_NEW_LINES);
} else {
    $files= find_all_files('img');
    file_put_contents($toSeePhotosFileFlag,"Found ".count($files)." images\n");
}

$max_to_read= count($files);
#if ($max_to_read>500) $max_to_read=500;
#$random_keys=array_rand($files,count($files));

#print_r($files);
$totalToShow=0;
#foreach ($random_keys as $i) {
$remainingFiles=array();
$exifImgs="";
$imgAltInfo=array();
$outLines=array();
foreach ($files as $imgInfo) {
    #if ($totalToShow > $MAX_TO_SHOW) break;
    $img= $imgInfo;
    $info="";
    if (strpos($imgInfo,":")>0) {
        list ($img,$info) = explode(":",$imgInfo);
        $imgAltInfo[$img]=$info;
    }
    if (!in_array($img,$seenPhotos) && $totalToShow<$MAX_TO_SHOW) {
        $totalToShow++;
        $exifImgs.=" \"$img\" ";
        $seenPhotos[]=$img;
        $outLines[]=$img;
    } else {
        $remainingFiles[]=$img;
    }
}
$out="";
$exifOutput=array();
exec("exiftool -c \"%+.6f\" -GPSPosition $exifImgs | xargs",$exifOutput); $exifOutput= explode("========",$exifOutput[0]);
# WARNING: if images have no GPS Position, exiftool will simply return the name and the offset calculations will fail!!
foreach ($outLines as $i => $img) {
    if ($i>0) $out.= ";";
    $pos="";
    if ($i+1< count($exifOutput)) {
        preg_match('/GPS.*?([+-\.\d]+).*?,.*?([+-\.\d]+)/', $exifOutput[$i+1], $matches);
        if ($matches) $pos=", ".$matches[1].",".$matches[2];
    }
    #file_put_contents("gps.data",$pos);
    $altText=pathinfo($img)['filename'];
    $altInfo="".substr($altText,0,8)." ".substr($altText,9,2).":".substr($altText,11,2)."$pos";
    $imgAltInfo[$img]=$altInfo;
    $out.= "<img src='img.php?w=$w&h=$h&src=$img' alt='$altInfo'/>";
}
print $out;

if ($totalToShow < $MAX_TO_SHOW) unlink($toSeePhotosFileFlag);
$fp=fopen($toSeePhotosFileName,"w");
foreach ($remainingFiles as $img) {
    if (array_key_exists($img,$imgAltInfo)) fwrite($fp,"$img:".$imgAltInfo[$img]."\n");
    else fwrite($fp,"$img\n");
}
fclose($fp);
$fp=fopen($seenPhotosFileName,"w");
foreach ($seenPhotos as $img) fwrite($fp,"$img\n");
fclose($fp);
#exec("find cache -mmin +44640 -exec rm {} \;");
?>
