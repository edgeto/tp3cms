
$(function(){
	var type = $('#type').val();
	var keyword = $('#keyword').val();
	$('.search .nav li').click(function(){
		type = $(this).attr('type');
		if(keyword != ''){
			var url = '?keyword='+keyword+'&type='+type;
			location.href = url;
		}else{
			$('.search .nav li').removeClass('active');
			$(this).addClass('active');
		}
	});
	$('.search-btn').click(function(){
		keyword = $('#keyword').val();
		var url = '?keyword='+keyword+'&type='+type;
		location.href = url;
	});
	$('.search form').submit(function(e){
		e.preventDefault();
		keyword = $('#keyword').val();
		var url = '?keyword='+keyword+'&type='+type;
		location.href = url;
		return;
	});
});