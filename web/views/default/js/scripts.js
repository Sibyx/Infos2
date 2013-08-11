var sectionData = new Array();
var currentSection;
var prevSection = '';

$(document).ready(function() {
	
	$(".socialSites tr").hover(function() {
		$(this).animate({color : '#000000'}, '1500');
	});
	
	$("body").delegate("tr[data-redirect-url]", 'click',function(){
		window.open($(this).attr('data-redirect-url'),'_blank');
	});
	
	$(".skill div[data-value]").each(function() {
		$(this).progressbar({
			value: parseInt($(this).attr('data-value'))
		});
	});
	
	$("a[rel='external']:not(:has(> img))").each(function() {
		$(this).append('<img class="external-icon" src="images/external.png" alt="External icon" />');
	});
	
	$(".pleaseZoom").each(function() {
		$(this).append('<div class="zoom"></div>');
		$(this).fancybox({
			openEffect	: 'elastic',
			closeEffect	: 'elastic',
		});
	});
	
	$(".pleaseZoom").hover(function(){
		$(this).children(".zoom").fadeIn(600);
	},function(){
		$(this).children(".zoom").fadeOut(400);
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
			error: function (xhr, ajaxOptions, thrownError) {
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