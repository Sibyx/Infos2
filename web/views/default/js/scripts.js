$(document).ready(function() {
	
	$("body").delegate("tr[data-record-url]", 'click',function(){
        $.ajax({
            type: 'GET',
            url: $(this).attr('data-record-url'),
            dataType: 'html',
            success: function(data) {
                $('#myModal').html(data);
            },
            error: function () {
                $('#myModal').html('<span style="margin-top: 20px; display: inline-block; font-size: 25pt; font-family:BigNoodleTitling">I am so sorry but my e-mail robot has depressions and refused your request. Please, try it later..</span>');
            }
        });
        $('#myModal').foundation('reveal', 'open');
	});

    $("#newSuplo_date").change(function(){
        $.ajax({
            type: 'GET',
            url: $(this).attr('data-suplo-url') + $(this).val(),
            dataType: 'json',
            success: function(data) {
                $('#suploExists').html(data.text);
            }
        });
    });

    $("#suploFilter_date").change(function(e){
        e.preventDefault();
        console.log($(this).val());

        var datum = new Date($(this).val());
        console.log(dateToYMD(datum));
        $.ajax({
            type: 'GET',
            url: $("#formSuploFilter").attr('action') + dateToYMD(new Date($(this).val())),
            dataType: 'json',
            success: function(data) {
                $("h2").html(data.header);
                $("#suploContainer").html(data.text);
            }
        });
    });

    $("#formSuploFilter").submit(function(e){
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: $(this).attr('action'),
            dataType: 'json',
            success: function(data) {
                $("h2").html(data.header);
                $("#suploContainer").html(data.text);
            }
        });
    });


    $('body').delegate('.vote', 'click', function(e) {
        e.preventDefault();
        var element = $(this);
        $.ajax({
            type: 'GET',
            url: $(this).attr('href'),
            dataType: 'json',
            success: function(data) {
                element.children('small').html(data.numLikes);
            }
        });
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
		url: window.location.origin + '/infos2/default/time',
		dataType: 'json',
		success: function(data) {
			$('#serverTime').html(data.serverTimeFormated);
			$('#serverTime').attr('datetime', data.serverTime);
			$('#current').html(data.current);
			$('#next').html(data.next);
		}
	});
}

function dateToYMD(date) {
    var d = date.getDate();
    var m = date.getMonth() + 1;
    var y = date.getFullYear();
    return '' + y + '-' + (m<=9 ? '0' + m : m) + '-' + (d <= 9 ? '0' + d : d);
}