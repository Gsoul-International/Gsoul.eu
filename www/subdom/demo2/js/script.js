$(function(){
	if($('html').height() < $(window).height()){
		$('.page-main').css('min-height', $(window).height() - $('.page-head').height() - $('.page-foot').height());
	}	
});
$(function(){
	function setCookie(cname, cvalue, exdays) {
		var d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		var expires = "expires="+ d.toUTCString();
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}
	function getCookie(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}
	
	if($('#cookies').length > 0){
		if(getCookie('cookies') != 'true'){
			$('#cookies').show();
		}else{
			$('#cookies').hide();
		}
		$('#cookies-accept').on('click', function(){
			setCookie('cookies', 'true', 14)
			$('#cookies').fadeOut();
		});
	}
});
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
  $(".rules-help").click(function(event){
    event.stopPropagation();
    var $message=$(this).data("message");
    alert($message);
  }); 
});
$(function(){
  $(".clickable-row").click(function() {
    window.location = $(this).data("href");
  });
});
$(function(){
	 $('.carousel-wrap.no-hover').bxSlider({
		pager: false,
		nextText: '',
		prevText: '',
		minSlides: 1,
		maxSlides: 5,
		slideWidth: 235,
		slideMargin: 1
	});
	
	$('.carousel-wrap.hover-bg').bxSlider({
		pager: false,
		nextText: '',
		prevText: '',
		minSlides: 1,
		maxSlides: 5,
		slideWidth: 235,
		slideMargin: 1,
		onSliderLoad: function(){
			$('.carousel-wrap.hover-bg').find('li').on('hover', function(){
				$('.page-head').css("background", "url('"+$(this).find('img').data('fullview-src')+"') no-repeat center center");
			});
		}
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
	var $fav = $('.favorite-icon');
	if($fav.length){		
		$('.favorite-icon').click(function(){
			$fav.parents('.favorite-games').toggleClass('hover');
		});
	}
});
$(function(){
	var $lang = $('.lang-shortcut');
	if($lang.length){		
		$(document).on('click', function(e){
			if($(e.target).is(".lang-shortcut, .lang-shortcut *")) return;
			$lang.next('.lang-content').removeClass('hover');
		});	
		$lang.on('click', function(){
			if($lang.next('.lang-content').hasClass('hover')){
				$lang.next('.lang-content').removeClass('hover');
			}else{
				$lang.next('.lang-content').addClass('hover');
			}
		});
	}
});

$(function(){
	var $user = $('.user-shortcut');
	if($user.length){	
		if($user.find('.user-name').text().trim().length > 10){
			$user.find('.user-name').text($user.find('.user-name').text().trim().substr(0,10)+'...');
		}
		$(document).on('click', function(e){
			if($(e.target).is(".user-shortcut, .user-shortcut *")) return;
			$user.next('.user-content').removeClass('hover');
		});	
		$user.on('click', function(){
			if($user.next('.user-content').hasClass('hover')){
				$user.next('.user-content').removeClass('hover');
			}else{
				$user.next('.user-content').addClass('hover');
			}
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

$(function(){
	var disable_click_flag = false;
	$(window).scroll(function() {
		disable_click_flag = true;

		clearTimeout($.data(this, 'scrollTimer'));

		$.data(this, 'scrollTimer', setTimeout(function() {
			disable_click_flag = false;
		}, 250));
	});
	$("body").on("click", "a", function(e) {
		if( disable_click_flag ){
			e.preventDefault();
		}
	});
});

$(function(){
	if($(".mCustomScrollbar").length){
		$(".mCustomScrollbar").mCustomScrollbar({
			axis: 'yx'
		});
	}
	if($(".mCustomScrollbarX").length){
		$(".mCustomScrollbarX").mCustomScrollbar({
			axis: 'x'
		});
	}
	if($(".mCustomScrollbarY").length){
		$(".mCustomScrollbarY").mCustomScrollbar({
			axis: 'y'
		});
	}
}); 

$(function(){
	if($('.window-matches-wrap').length){
		var $matchesWrap = $('.window-matches-wrap');
		
		// zoom init
		var $zoomableGrid=$matchesWrap.find('.grid');
		if($(window).width() < 1354){
			var currentScale = 0.5;
		}else{
			var currentScale = 1;
		}
			
        var cssPrefixesMap = [
            'scale',
            '-webkit-transform',
            '-moz-transform',
            '-ms-transform',
            '-o-transform',
            'transform'
        ];
		
		//$zoomableGrid.width($zoomableGrid.find('.col').width()*$zoomableGrid.find('.col').length);
		
			
		$zoomableGrid.width($zoomableGrid.find('.col').first().outerWidth(true)*$zoomableGrid.find('.col').length+24);
		setScale(currentScale);
		updateViewport();
		
		var $zoomControl = $matchesWrap.append('<div class="zoomControl"></div>').find('.zoomControl');
		var $zoomIn = $zoomControl.append('<div class="zoomIn">+</div>').find('.zoomIn');
		var $zoomOut = $zoomControl.append('<div class="zoomOut">-</div>').find('.zoomOut');
		
		$zoomIn.on('click', function(){
			if(currentScale < 2){	
				setScale(currentScale = currentScale + 0.1);
			}
		});
		$zoomOut.on('click', function(){
			if(currentScale > 0.5){	
				setScale(currentScale = currentScale - 0.1);
			}
		});
		

		function setScale(scale) {
			var scaleCss = {};
			cssPrefixesMap.forEach(function (prefix) {
				scaleCss[prefix] = 'scale(' + scale + ')';
			});
			$zoomableGrid.css(scaleCss);
			updateViewport();
		}

		function updateViewport(){
			var scaledHeight=$zoomableGrid[0].getBoundingClientRect().height;
			$zoomableGrid.parents(".mCSB_container").css({
				"height": $zoomableGrid.outerHeight()!==scaledHeight ? scaledHeight : "auto"
			});
			
			if($zoomableGrid[0].getBoundingClientRect().width < $matchesWrap.width()){
				$zoomableGrid.parents(".mCSB_container").css({
					'width':$matchesWrap.width(),
					'max-width':'1180px'
				});
			}else{
				$zoomableGrid.parents(".mCSB_container").css({
					'width':$zoomableGrid[0].getBoundingClientRect().width,
					'max-width':'none'
				});
			}
		}

		var $highlight = $zoomableGrid.find('.highlight-player');
		$highlight.on('click', function(e){
			e.preventDefault();
			if($(this).hasClass('active')){
				$zoomableGrid.find('.match.active').blur();
				$zoomableGrid.find('.active').removeClass('active');
			}else{
				$zoomableGrid.find('.active').removeClass('active');
				var classes = $(this).attr('class').split(' ');
				$.each(classes, function(e){
					if(classes[e].startsWith('player-')){
						$zoomableGrid.find($('.'+classes[e])).addClass('active');
						$.each($zoomableGrid.find('.highlight-player.active').parents('.match'), function(){
							$(this).addClass('active');
						});
					}
				});
				$(this).filter(function () {
					return this.className.match('/\bplayer/');
				}).addClass('active');
			}
		});
	}
});

$(function(){
	if($('.datatable').length){
		$('.datatable').DataTable({
			 paging: false,
			 ordering: false,
			 searching: false
		});
	}
});  
