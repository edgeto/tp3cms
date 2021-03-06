var video = angular.module('Video',[]);
video.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('<{');
    $interpolateProvider.endSymbol('}>');
});
video.controller('VideoCate',['$scope', '$http',function($scope,$http) {
	var collectElement = angular.element(document.querySelector('.glyphicon-heart'));
	var collectModalElement = angular.element(document.querySelector('#MyModal'));
	var collectModalElementTitle = angular.element(document.querySelector('#MyModal #myModalLabel'));
	var unCollectBtnElement = angular.element(document.querySelector('#unCollectBtn'));
	unCollectBtnElement.hide();
 	$scope.collect = function(e){
 		if($scope.isLogin()){
	 		if(collectElement.hasClass('in')){
	 			// 弹窗
	 			unCollectBtnElement.show();
	 			collectModalElementTitle.html('取消收藏');
	 			collectModalElement.modal('toggle');
	 		}else{
				$scope.doCollect(e);	
	 		}
 		}
 	};
 	$scope.doCollect = function(e){
 		if($scope.isLogin()){
	 		// Simple GET request example:
			$http({
			  method: 'GET',
			  url: '/video/collect/id/'+e
			}).then(function successCallback(response) {
			    // this callback will be called asynchronously
			    // when the response is available
			    collectModalElementTitle.html('收藏成功');
			    if(response.data.code == 1){
			    	collectModalElementTitle.html(response.data.msg);
			    }
			    collectModalElement.modal('toggle');
			    collectElement.addClass('in');
		  	}, function errorCallback(response) {
			    // called asynchronously if an error occurs
			    // or server returns response with an error status.
			    
		  	});
 		}
 	};
 	$scope.unCollect = function(e){
 		if($scope.isLogin()){
	 		// Simple GET request example:
			$http({
			  method: 'GET',
			  url: '/video/uncollect/id/'+e
			}).then(function successCallback(response) {
			    // this callback will be called asynchronously
			    // when the response is available
			    unCollectBtnElement.hide();
			    collectModalElementTitle.html('取消收藏成功');
			    if(response.data.code == 1){
			    	collectModalElementTitle.html(response.data.msg);
			    }
			    //collectModalElement.modal('toggle');
			    collectElement.removeClass('in');
		  	}, function errorCallback(response) {
			    // called asynchronously if an error occurs
			    // or server returns response with an error status.
		  	});
 		}
 	};
 	$scope.isLogin = function(){
 		if(isLogin == 0){
 			location.href = loginUrl;
 			return false;
 		}
 		return true;
 	};
 	// 评论列表
 	var commentListElement = angular.element(document.querySelector('.comment-list'));
 	$scope.commentList = [];
 	$scope.p = 0;
 	// 更多按钮
 	var moreCommentElement = angular.element(document.querySelector('.moreComment'));
	var id = angular.element(document.querySelector('#video_id')).val();
 	$scope.getCommentList = function(e){
 		$http({
			  method: 'GET',
			  url: '/video/getComment/id/'+id+'/p/'+$scope.p
			}).then(function successCallback(response) {
			    // this callback will be called asynchronously
			    // when the response is available
			    if(response.data.code == 0){
			    	commentListElement.show();
			    	moreCommentElement.show();
			    	angular.forEach(response.data.data, function(value, key) {
					  this.push(value);
					}, $scope.commentList);
			    	$scope.p++;
			    }else{
			    	// 没有评论
			    	if($scope.p  == 0){
			    		//moreCommentElement.hide();
			    	} 
			    	// 没有更多评论
			    	moreCommentElement.addClass('disabled');
			    	moreCommentElement.html('没有更多评论了！');
			    }
			    //console.log($scope.commentList);
		  	}, function errorCallback(response) {
			    // called asynchronously if an error occurs
			    // or server returns response with an error status.
		  	});
 	};
 	$scope.getCommentList($scope.p);
 	// 更多评论
 	$scope.moreComment = function(){
 		$scope.getCommentList($scope.p);
 	};
 	// 评论
 	var commentContentElement = angular.element(document.querySelector('.commentContent'));
 	var doCommentElement = angular.element(document.querySelector('.doComment'));
 	if(isLogin == 0){
 		// 没登陆则不给评论
 		commentContentElement.attr('disabled', 'disabled');
 		doCommentElement.html('登陆');
 	}
 	commentContentElement.val('');
 	$scope.doComment = function(e){
 		if($scope.isLogin()){
 			var commentContent = commentContentElement.val();
 			if(commentContent){
 				$http({
				  method: 'POST',
				  url: '/video/doComment',
				  data:{id:id,content:commentContent},
				}).then(function successCallback(response) {
				    // this callback will be called asynchronously
				    // when the response is available
				    commentContentElement.val('');
			    	collectModalElementTitle.html(response.data.msg);
					collectModalElement.modal('toggle');
				    //console.log($scope.commentList);
			  	}, function errorCallback(response) {
				    // called asynchronously if an error occurs
				    // or server returns response with an error status.
			  	});
 			}else{
				collectModalElementTitle.html('评论内容不能为空!');
 				collectModalElement.modal('toggle');
 			}
 		}
	}
}]);