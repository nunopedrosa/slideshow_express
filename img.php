<?php

$w=$_GET['w'];
$h=$_GET['h'];
$filename=$_GET['src'];

function image_fix_orientation($image, $exif) {
    if (!empty($exif['Orientation'])) {
        switch ($exif['Orientation']) {
            case 3: $image = imagerotate($image, 180, 0);
                break;
            case 6: $image = imagerotate($image, -90, 0);
                break;
            case 8: $image = imagerotate($image, 90, 0);
                break;
        }
    }
    return $image;
}


function file_fix_orientation($filename) {
    $exif = exif_read_data($filename);
    file_put_contents("exif.data",$exif);
    $image = imagecreatefromjpeg($filename);
    if (!empty($exif['Orientation'])) {
        return image_fix_orientation($image, $exif);
    }
    return $image;
}


function resize($filename, $cachefilename, $width, $height) {
    #print "".pathinfo($file)['extension']."\n";
    $sampleMethod=IMG_NEAREST_NEIGHBOUR; # IMG_NEAREST_NEIGHBOUR, IMG_BILINEAR_FIXED, IMG_BICUBIC, IMG_BICUBIC_FIXED
    switch(pathinfo($filename)['extension']) {
        case "png": imagepng(imagescale(imagecreatefrompng($filename), $width), $cachefilename);
        case "gif": imagegif(imagescale(imagecreatefromgif($filename), $width), $cachefilename);
        default : {
            #$pos= exec("exiftool -c \"%+.6f\" -GPSPosition $filename");// -webkit-transform-origin
            #file_put_contents("gps.data",$pos);
            $exif = exif_read_data($filename);
            $image = imagecreatefromjpeg($filename);
            $image= imagescale($image, $width, -1, $sampleMethod);
            imagejpeg(image_fix_orientation($image, $exif),$cachefilename, 70);
        }
    }
}
$width=$w>0?$w:1024;
$height=768;
#print_r("cache/".pathinfo($filename)['basename']);
$cachefilename="cache/".pathinfo($filename)['basename'];

if (!file_exists($cachefilename))
    resize($filename, $cachefilename,$width, $height);

$mimetype= mime_content_type($cachefilename);
ob_start();
header('Content-Type: '.$mimetype);
ob_end_clean();
$fp = fopen($cachefilename, 'rb');
fpassthru($fp);

?>
