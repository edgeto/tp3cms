/*var region = angular.module('region', []);
console.log(region);
region.controller('regionCtrl', function($scope) {
		$scope.x = 0;
    $scope.getCity = function(x){
    	alert(x);
    };
});*/

$(function(){
	
	
	

	// 选择省份
	function selectProvince(){
		$('#province').change(function(event) {
		var id = $(this).val();
		$.ajax({
			url: '/index/ajaxGetRegion',
			type: 'POST',
			data:{id:id},
			dataType: 'json',
		})
		.done(function(data) {
				var city = '';
				for (x in data) {
					city += '<option value="'+data[x]["id"]+'">'+data[x]["name"]+'</option>';
					// console.log(city);
				}
				$('#city').html(city);
				selectCity($('#city').val());
			});
		});
	}
	// 选择省份后默认地区
	function selectCity(c){
		var id = c;
		$.ajax({
			url: '/index/ajaxGetRegion',
			type: 'POST',
			data:{id:id},
			dataType: 'json',
		})
		.done(function(data) {
			var area = '';
			for (x in data) {
				area += '<option value="'+data[x]["id"]+'">'+data[x]["name"]+'</option>';
				// console.log(area);
			}
			$('#area').html(area);
		});
	}
	// 选择城市
	function changeCity(){
		$('#city').change(function(event) {
			var id = $(this).val();
			$.ajax({
				url: '/index/ajaxGetRegion',
				type: 'POST',
				data:{id:id},
				dataType: 'json',
			})
			.done(function(data) {
				var area = '';
				for (x in data) {
					area += '<option value="'+data[x]["id"]+'">'+data[x]["name"]+'</option>';
					// console.log(area);
				}
				$('#area').html(area);
			});
		});
	}
	selectProvince();
	changeCity();
});