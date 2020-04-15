<?php
$PIPELINE_SIZE=10;
?>
<html><head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Galeria</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.0.js"></script>
    <link rel="stylesheet" type="text/css" href="anim.css">
    <!-- Clock settings -->
    <link rel="stylesheet" href="flip-clock.min.css" media="screen" charset="utf-8">
    <link rel="stylesheet" href="clock.css" media="screen" charset="utf-8">
    <link href='https://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js" charset="utf-8"></script>
    <script src="flip-clock.js" charset="utf-8"></script>
    <script type="text/javascript">
      $(function() {
        // flipclock-small
        new FlipClockManager('#clock', 'flipclock-blue flipclock-linear flip-bottom-animate').currentTime();
      });
    </script>
</head>
<body>
	<div id="title"></div>
    <div id="clock"></div>
    <ul id="slider">
	    <?php
	    for ($i=0;$i<$PIPELINE_SIZE;$i++)
	        print "<li></li>";
	    ?>
	</ul>
	<div id="cache"></div>
	<div id="location"></div>
    <div id="debug"></div>
</script>
<script type="text/javascript" src="slide.js"></script>
</body></html> 

