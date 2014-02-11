$(document).ready(function() {
	
	$("body").delegate("tr[data-url]", 'click',function(){
        $.ajax({
            type: 'GET',
            url: $(this).attr('data-url'),
            global: false,
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
        $.ajax({
            type: 'GET',
            url: $("#formSuploFilter").attr('action') + moment($(this).val(), "DD-MM-YYYY").format("YYYY-MM-DD"),
            dataType: 'json',
            success: function(data) {
                $("title").html(data.header + " - Infos2");
                $("h2").html(data.header);
                $("#suploContainer").html(data.text);
            }
        });
    });

    $("#newEvent_time").change(function(e){
        e.preventDefault();
        if ($(this).val() == "custom") {
            $("#newEvent_startTime").prop('disabled', false);
            $("#newEvent_endTime").prop('disabled', false);
        }
        else {
            $("#newEvent_startTime").prop('disabled', true);
            $("#newEvent_endTime").prop('disabled', true);
        }
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

	$("#suploHistory_season").change(function(){
		var form = $(this).parent();
		$("#printSummary").attr("href", form.attr("data-action-url") + $(this).val());
		form.attr("action", form.attr("data-action-url") + $(this).val());
		form.submit();
	});

	$("#formSuploHistory").submit(function(e){
		e.preventDefault();
		$.ajax({
			type: 'GET',
			url: $(this).attr('action'),
			dataType: 'html',
			success: function(data) {
				$("#suploHistory").html(data);
			},
			error: function () {
				alert("Error occured while loading substitution records");
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

    $(document).ajaxStart(function() {
        $('#loader').foundation('reveal', 'open');
    });

    $(document).ajaxStop(function() {
        $('#loader').foundation('reveal', 'close');
    });
	
});

$(window).bind("load", function () {
	var footer = $("#footer");
	var pos = footer.position();
	var height = $(window).height();
	height = height - pos.top;
	height = height - footer.height();
	if (height > 0) {
		footer.css({
			'margin-top': height + 'px'
		});
	}
});

function updateClock() {
	$.ajax({
		type: 'POST',
		url: window.location.origin + '/infos2/default/time',
		dataType: 'json',
        global: false,
		success: function(data) {
			$('#serverTime').html(data.serverTimeFormated);
			$('#serverTime').attr('datetime', data.serverTime);
			$('#current').html(data.current);
			$('#next').html(data.next);
		}
	});
}