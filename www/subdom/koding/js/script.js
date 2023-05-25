$(function() {
	var lightbox = {
		init: function(){
			$('.lightbox').swipebox({
				hideBarsDelay:0,
				loopAtEnd: true
			});
		}
	}
	lightbox.init();
});

 $(function(){
	 $('.carousel-wrap').bxSlider({
		pager: false,
		nextText: '',
		prevText: '',
		minSlides: 1,
		maxSlides: 5,
		slideWidth: 235,
		slideMargin: 1
	});
});

$(function(){
	var $autoscroll = $('.autoscroll:last');
	if($autoscroll.length){
		$('html, body').animate({scrollTop: ($autoscroll.offset().top) - 25}, 1200);
	}
});

$(function(){
	var $popup = $('.show-popup');
	if($popup.length){
		$.each($popup, function(){
			$(this).click(function(e){
				e.stopPropagation();
				$('#'+$(this).data('show')).fadeIn();
			});
		});
	}
});

$(function(){
	var $dropdown = $('.dropdown');
	if($dropdown.length){
		$.each($dropdown, function(){
			if($(this).hasClass('active')){
				$(this).find('.dropdown-content').slideDown();
			}else{
				$(this).find('.dropdown-content').hide();
			}
			$(this).click(function(e){
				$(this).find('.dropdown-content').slideToggle();
				if($(this).hasClass('active')){
					$(this).removeClass('active');
				}else{
					$(this).addClass('active');
				}
				
			});
		});
	}
});

$(function(){
	if($('.countdown').length > 0){
		$.each($('.countdown'), function(){
			var $cntdwn = $(this);
			var now = new Date();
			var cntdwnsec = ((((+parseInt($cntdwn.find('.hours').text()))*60)+parseInt($cntdwn.find('.minutes').text()))*60)+parseInt($cntdwn.find('.seconds').text());
			var countDownDate = new Date(now.getTime() + (cntdwnsec * 1000));
			var x = setInterval(function() {
				now = new Date();
				var distance = countDownDate - now;
				var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
				if (hours.toString().length == 1) {hours = "0" + hours;}
				var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
				if (minutes.toString().length == 1) {minutes = "0" + minutes;}
				var seconds = Math.floor((distance % (1000 * 60)) / 1000);
				if (seconds.toString().length == 1) {seconds = "0" + seconds;}
				$cntdwn.find('.hours').html(hours+' h');
				$cntdwn.find('.minutes').html(' '+minutes+' m');
				$cntdwn.find('.seconds').html(' '+seconds+' s');
				if (distance < 0) {
					clearInterval(x);
					$cntdwn.remove();
				}
			},1000);
		});
	}
});

$(function(){
	$('.datatable').DataTable({
		 paging: false,
		 ordering: true,
		 searching: false
	});
});

$(function(){
	$(".datepicker").flatpickr({
		dateFormat: "d.m.Y",
		"locale": "cs"
	});
});

$(function(){
	$(".timepicker").flatpickr({
		enableTime: true,
		noCalendar: true,
		dateFormat: "H:i",
		time_24hr: true,
		"locale": "cs"
	});
});