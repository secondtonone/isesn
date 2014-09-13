$(document).ready(function () {
	
	 $('.login').attr('style', '');
		$.backstretch(["/img/page-1.jpg","/img/page-2.jpg","/img/page-3.jpg"],
		{fade: 1000,duration: 3000});
	
	
	$('#enter-form').submit(function(){
		
		var	formData=$(this).serialize(),
			site=window.location.hostname;
			
		$('#entering').hide();
		$(".enter-preloader").css({'display' : 'inline-block'});
		$("#execute").css({'display' : 'inline-block'});
								
		$.ajax({
			type: "POST",
			url: "/app/scripts/auth/auth.php",
			data: formData,
			success: function(msg){
				if (msg.length)
				{
					$(".enter-preloader").hide();
					$("#execute").hide();
					$('#entering').css({'display' : 'inline-block'});
					$(".display-error").show();
					$(".message").html(msg);
				}
				else
				{
					window.location = 'http://'+site+'/panel';
				}
				
			}
       });
	   return false;
	});
	
	$('.login').on('click','.close-button', function() {
		$(".display-error").hide();
	});
	
	$('#exit').submit(function(){
		
		var	site=window.location.hostname;
							
		$.ajax({
			type: "POST",
			url: "/app/scripts/auth/exit.php",
			success: function(msg){
					window.location = 'http://'+site+'/main';		
			}
       });
	   return false;
	});
	
	$('.text-box').click(function(){
		
		$(this).html('ИС ЕСН &copy; Кузнецов Максим 2014');
		
		setTimeout(function() { 
						$('.text-box').html('ИС ЕСН &copy; ООО Статус 2014');
					}, 7000);
		});
	
});