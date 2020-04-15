$(function () {
    function random(array) {
        return array[Math.floor(Math.random() * array.length)]
    }
    
    function setImages(a,z) {
	    if (!imagesWaiting) return;
        for (c=a;c<z;c++) listItems.eq(c).html(imagesWaiting[c%imagesWaiting.length]);
	}
    
    /* SET PARAMETERS */
    var change_slide_time 	= 7000;	
    var transition_time	= 1000;
    // var fxArr = ['slideLeft', 'slideRight', 'fadeIn', 'bigEntrance', 'pullUp', 'pullDown', 'stretchLeft', 'stretchRight'];
    var fxArr = ['kenburnsTop', 'kenburnsLeft', 'kenburnsBottom', 'kenburnsRight'];
    var specialfx = 'rand';
    var simple_slideshow= $("#slider");
    var listItems= simple_slideshow.children('li');
    var pipeline_size= listItems.length;
    var pipeline_middle = Math.floor(pipeline_size/2);
    var imagesWaiting;
    var i= 0;
    listItems.not(":first").hide();
    
    var updatePipe = function ( doSet ) {
          var w= $( "#slider" ).width(), h=$("#slider").height();
          //alert("Size: "+w+" x "+h);
          $.get( "list.php?w="+w+"&h="+h, function( data ) {
            $( "#cache" ).html(data);
            imagesWaiting = data.split(";");
            if (doSet) setImages(0,pipeline_size);
          });
        },
        changeList = function () {
	        listItems.eq(i).fadeOut(transition_time);
	        i += 1;
            if (i === pipeline_size) i = 0;
            $( "#debug" ).html("#"+i);//+" "+pipeline_middle);
            var anImg=listItems.eq(i).children('img');
            var info= anImg.attr('alt')?anImg.attr('alt'):"";
            infoBites= info.split(",");
            $("#title").html(infoBites.length>1?infoBites[0]:info);
	    $("#location").html(infoBites.length>2?infoBites[1].substr(0,6)+","+infoBites[2].substr(0,5):'');
            listItems.eq(i).fadeIn(transition_time).attr('class', '').addClass(specialfx == 'rand'?random(fxArr):specialfx);
            if (i == pipeline_middle-1) updatePipe();
            if (i == 1) setImages(pipeline_middle,pipeline_size);
            if (i == pipeline_size-2) setImages(0,pipeline_middle);
            //$( "#debug" ).html("#"+i);
        };
    updatePipe(1);
    setInterval(changeList, change_slide_time);
});


