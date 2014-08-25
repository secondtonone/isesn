$(document).ready(function () {
	
	var	site=window.location.hostname;
	
	$('.exit').click(function(){
		
		var	site=window.location.hostname;
							
		$.ajax({
			type: "POST",
			url: "/app/scripts/auth/exit.php",
			success: function(msg){
					window.location = 'http://'+site+'/';		
			}
       });
	});
	
	$('.panel').click(function(){
		
		var	site=window.location.hostname;
							
		window.location = 'http://'+site+'/panel';			
	});
	
	$('.journal').click(function(){
		
		var	site=window.location.hostname;
							
		window.location = 'http://'+site+'/journal';			
	});
	
	$('.stats').click(function(){
		
		var	site=window.location.hostname;
							
		window.location = 'http://'+site+'/stats';			
	});
	
	$('.help').click(function(){
		
		var	site=window.location.hostname;
							
		window.location = 'http://'+site+'/help';			
	});
	
	$('.sidebar-menu li a').click (function(){
		$('.sidebar-menu li a').removeClass('active');
		$(this).addClass('active');
	});
	
	$( ".sidebar-menu" ).accordion({
		active: false,	
    	collapsible: true,
		heightStyle: "content",
    });
	
	 $( ".sidebar-menu li a" ).removeClass( 'ui-corner-all');
	 $( ".sidebar-menu li a" ).removeClass( 'ui-state-default');
	 $( ".sidebar-menu li a" ).removeClass( 'ui-accordion-icons');
	 $( ".sidebar-menu li a" ).removeClass( 'ui-state-hover');
	 $( ".sidebar-menu li a" ).removeClass( 'ui-state-focus');
	 
	 $('a[href^="#"]').on('click',function (e) {
	    e.preventDefault();

	    var target = this.hash,
	    $target = $(target);

	    $('html, body').stop().animate({
	        'scrollTop': $target.offset().top
	    }, 900, 'swing', function () {
	        window.location.hash = target;
	    });
	});
	
	 $('.user-list').slimScroll({
        height: '720px'
    });
	
	function activityTime () {
		$.ajax({
			type: "POST",
			url: "/app/scripts/journal/journal_modify.php",
			data:"q=1",
			async: false
			});
	}
	activityTime();
	setInterval(activityTime,300000);
	
});
