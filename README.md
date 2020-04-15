# slideshow_express
A simple set of scripts to get a photo slideshow going in seconds.

# Why?
I wanted a simple way to display a slideshow of photos, without having to load photos into some web site or SD card. I also wanted to customize the display, showing a clock.

# How?
Using the PHP localserver, php -S slide.local:8000, I fire up a local server that will display all images inside the *img* folder. Inside *img*, there's a bunch of symlinks to the photo archive folders.

A simple javascript and CSS set of files displays the images using a few transistions (the kenburns style is my favorite transition). 