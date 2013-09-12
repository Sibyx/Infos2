$(document).ready(function() {
	
	$("body").delegate("tr[data-redirect-url]", 'click',function(){
		window.open($(this).attr('data-redirect-url'),'_blank');
	});
	
	
	$("#formContact").submit(function(e){
		e.preventDefault();
		var dataString = $(this).serialize();
		console.log(dataString);
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: dataString,
			dataType: 'json',
			success: function(data) {
				if (data.error != true) {
					$("#contactThanks").append('<span style="margin-top: 20px; display: inline-block; font-size: 25pt; font-family:BigNoodleTitling">My email robot successfully processed your message. Now, you can wait for my response.</span>');
					$('#contactThanks span').addClass("green");
				}
				else {
					$("#contactThanks").append('<span style="margin-top: 20px; display: inline-block; font-size: 25pt; font-family:BigNoodleTitling">I am so sorry but my e-mail robot has depressions and could not process your request. Please, try it later..</span>');
					$('#contactThanks span').addClass("green");
				}
			},
			error: function () {
				$("#contactThanks").append('<span style="margin-top: 20px; display: inline-block; font-size: 25pt; font-family:BigNoodleTitling">I am so sorry but my e-mail robot has depressions and refused your request. Please, try it later..</span>');
				$('#contactThanks span').addClass("red");
			}
		});
		$(this).parent().fadeOut("slow", function(){
			$("#contactThanks").css("height", $(this).parent().height());	
			$("#contactThanks").fadeIn("slow");	
		});
	});
	
});

function updateClock() {
	$.ajax({
		type: 'POST',
		url: window.location.origin + '/default/time',
		dataType: 'json',
		success: function(data) {
			$('#serverTime').html(data.serverTimeFormated);
			$('#serverTime').attr('datetime', data.serverTime);
			$('#current').html(data.current);
			$('#next').html(data.next);
		}
	});
}