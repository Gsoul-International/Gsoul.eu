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
	var $autoscroll = $('.autoscroll:last');
	if($autoscroll.length){
		$('html, body').animate({scrollTop: ($autoscroll.offset().top) - 25}, 1200);
	}
});
$(function(){
	var $pageMain = $('.page-main');
	var $pageLeft = $('.page-left');
	var $pageRight = $('.page-right');
	$(window).on('resize load', function(){
		$pageMain.css({'padding-left':$pageLeft.width(), 'padding-right':$pageRight.width()});		
		$pageLeft.find('.carousel-wrap').css('height',$pageLeft.height()-$pageLeft.find('.content').height()-6);
		$pageRight.find('.carousel-wrap').css('height',$pageRight.height()-$pageRight.find('.content').height()-6);
		$pageMain.fadeIn(100);
	});
	$pageLeft.append('<div class="page-left-control"><em class="fa fa-chevron-right"></em></div>');
	$pageRight.append('<div class="page-right-control"><em class="fa fa-chevron-left"></em></div>');
	var $leftControl = $pageLeft.children('.page-left-control');
	var $rightControl = $pageRight.children('.page-right-control');
	$leftControl.on('click', function(){
		$pageLeft.toggleClass('active');
		setTimeout(function(){$(window).trigger('resize')}, 240);
	});
	$rightControl.on('click', function(){
		$pageRight.toggleClass('active');
		setTimeout(function(){$(window).trigger('resize')}, 240);
	});
});
$(function(){
	$('.nav-mobile').click(function(){
		$('.left-mobile').removeClass('active');
		$(this).toggleClass('active');
	});
	$('.nav-mobile').click(function(e){
		e.stopPropagation();
	});
	$(document).click(function(){
		$('.nav-mobile, .left-mobile').removeClass('active');
	});
	$('.left-mobile').click(function(){
		$('.nav-mobile').removeClass('active');
		$(this).toggleClass('active');
	});
	$('.left-mobile').click(function(e){
		e.stopPropagation();
	});
	$(document).click(function(){
		$('.left-mobile, .nav-mobile').removeClass('active');
	});
});
$(function(){
	if($('.countdown').length > 0){
		var $cntdwn = $('.countdown').first();
		var now = new Date();
		var cntdwnsec = (((((parseInt($cntdwn.find('.days').text())*24)+parseInt($cntdwn.find('.hours').text()))*60)+parseInt($cntdwn.find('.minutes').text()))*60)+parseInt($cntdwn.find('.seconds').text());
		var countDownDate = new Date(now.getTime() + (cntdwnsec * 1000));
		var x = setInterval(function() {
			now = new Date();
			var distance = countDownDate - now;
			var days = Math.floor(distance / (1000 * 60 * 60 * 24));
			var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
			if (hours.toString().length == 1) {hours = "0" + hours;}
			var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
			if (minutes.toString().length == 1) {minutes = "0" + minutes;}
			var seconds = Math.floor((distance % (1000 * 60)) / 1000);
			if (seconds.toString().length == 1) {seconds = "0" + seconds;}
			$cntdwn.find('.days').html(days);
			$cntdwn.find('.hours').html(' '+hours);
			$cntdwn.find('.minutes').html(' '+minutes);
			$cntdwn.find('.seconds').html(' '+seconds);
			if (distance < 0) {
				clearInterval(x);
				$cntdwn.remove();
			}
		},1000);
	}
});