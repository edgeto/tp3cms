/**
 * Created by Administrator on 2016/4/13.
 */
angular.module('starter.controllers', [])
    .controller('HomeTabCtrl', function ($scope,Lists,superCache,$ionicLoading,$timeout,$interval,$ionicSlideBoxDelegate,$ionicScrollDelegate,$http) {
        /*** ionic 轮播 ion-slide 异步不可以repeat
        $scope.Carousel = Carousel;
        $http.jsonp(API_URL + '/ad/getWapCarousel?callback=JSON_CALLBACK')
            .success(function(data) {
                if(data.code == 0){
                    $scope.Carousel = data.data;
                }
            })
            .then(function(res){
                //console.log(res);
                //console.log(res.data.data);
                //$scope.Carousel = res.data.data;
            });
        ***/
        $scope.myActiveSlide = 0;
        $interval(function(){
            //重启轮播图
            $ionicSlideBoxDelegate.start();
        },5000);
        /*$scope.page = 2;//从第二页开始
        $scope.loadMore = function(){
            console.log($scope.page);
            $scope.page++;
            //$scope.$broadcast('scroll.infiniteScrollComplete');
        };
        $scope.doRefresh = function(){
            console.log($scope.page);
            $scope.page++;
            $scope.$broadcast('scroll.refreshComplete');
        };*/
        //列表名称和图标
        $scope.Lists = Lists;
    /*
        //全部分类
        $scope.categoryArticle = '';
        $http.jsonp(API_URL + '/artCategory/getAllWapRecommend?callback=JSON_CALLBACK')
            .success(function(data) {
                if(data.code == 0){
                    $scope.categoryArticle = data.data;
                }
            });
        //图片
        $scope.pic = '';
        $http.jsonp(API_URL + '/pic/getWapRecommend?callback=JSON_CALLBACK')
            .success(function(data) {
                if(data.code == 0){
                    $scope.pic = data.data;
                }
            });
        //问答
        $scope.ask = '';
        $http.jsonp(API_URL + '/ask/getWapRecommend?callback=JSON_CALLBACK')
            .success(function(data) {
                if(data.code == 0){
                    $scope.ask = data.data;
                }
            });
  */
    })
    .controller('FoundCtrl', function($scope, $stateParams, Lists,$http,$ionicLoading,$timeout,$ionicModal,$ionicHistory) {
        $scope.p = 1;
        $scope.list = [];
        $scope.loadMoreRes = true;
        $scope.loadMore = function() {
            $scope.$broadcast('scroll.infiniteScrollComplete');
            if($scope.loadMoreRes){
                $http({
                      method: 'GET',
                      url: '/index/found/p/'+$scope.p,
                    }).then(function successCallback(response) {
                        // this callback will be called asynchronously
                        // when the response is available
                        console.log(response);
                        if(response.data.code == 0){
                            angular.forEach(response.data.data, function(value, key) {
                                this.push(value);
                            }, $scope.list);
                            $scope.p++;
                        }else{
                           $scope.loadMoreRes = false; 
                        }
                        $scope.$broadcast('scroll.infiniteScrollComplete');
                        console.log($scope.list);
                    }, function errorCallback(response) {
                        // called asynchronously if an error occurs
                        // or server returns response with an error status.
                    });
            }
        };
    })
    .controller('listsCtrl', function($scope, $stateParams, Lists,$http,$ionicLoading,$timeout,$ionicModal,$ionicHistory) {
        // var backView = $ionicHistory.backView();
        // $scope.indexBtn = true;
        // if(!backView){
        //     //直接打开页面的,则提供一个按钮跳到首页
        //     $scope.indexBtn = false;
        // }
        // $scope.goIndex = function(){
        //     $ionicLoading.show({
        //         content: 'Loading',
        //         animation: 'fade-in',
        //         showBackdrop: true,
        //         maxWidth: 200,
        //         showDelay: 0
        //     });
        //     $timeout(function () {
        //         location.href = '/';
        //     }, 1000);
        // };
        // var ListID = $stateParams.Id;
        // $scope.list = 1;
        $ionicLoading.show({
            template: "Loading..."
        });
        $timeout(function(){
            //隐藏载入指示器
            $ionicLoading.hide();
        },1000);
        /*$scope.page = 2;//从第二页开始
        $scope.loadMore = function(){
            console.log($scope.page);
            $scope.page++;
            $scope.$broadcast('scroll.infiniteScrollComplete');
         };*/
        /*$scope.p = 1;
        $scope.list = [];
        $scope.loadMoreRes = true;
        $scope.loadMore = function() {
            $scope.$broadcast('scroll.infiniteScrollComplete');
            if($scope.loadMoreRes){
                $http({
                      method: 'GET',
                      url: '/article/cateLists/p/'+$scope.p,
                    }).then(function successCallback(response) {
                        // this callback will be called asynchronously
                        // when the response is available
                        console.log(response);
                        if(response.data.code == 0){
                            angular.forEach(response.data.data, function(value, key) {
                                this.push(value);
                            }, $scope.list);
                            $scope.p++;
                        }else{
                           $scope.loadMoreRes = false; 
                        }
                        $scope.$broadcast('scroll.infiniteScrollComplete');
                        console.log($scope.list);
                    }, function errorCallback(response) {
                        // called asynchronously if an error occurs
                        // or server returns response with an error status.
                    });
            }
        };*/
    })
    .controller('PicListsCtrl', function($scope, $stateParams, Lists,$http,$ionicLoading,$timeout,$ionicModal,$ionicHistory) {
        /*var backView = $ionicHistory.backView();
        $scope.indexBtn = true;
        if(!backView){
            //直接打开页面的,则提供一个按钮跳到首页
            $scope.indexBtn = false;
        }
        $scope.goIndex = function(){
            $ionicLoading.show({
                content: 'Loading',
                animation: 'fade-in',
                showBackdrop: true,
                maxWidth: 200,
                showDelay: 0
            });
            $timeout(function () {
                location.href = '/';
            }, 1000);
        };
        var ListID = $stateParams.Id;
        $scope.list = 1;*/
        $ionicLoading.show({
            template: "Loading..."
        });
        $timeout(function(){
            //隐藏载入指示器
            $ionicLoading.hide();
        },1000);
        $scope.p = 1;
        $scope.list = [];
        $scope.loadMoreRes = true;
        $scope.id = document.getElementById('id').value;
        $scope.loadMore = function() {
            $scope.$broadcast('scroll.infiniteScrollComplete');
            if($scope.loadMoreRes){
                $http({
                      method: 'GET',
                      url: '/pic/lists/id/'+$scope.id+'/p/'+$scope.p,
                    }).then(function successCallback(response) {
                        // this callback will be called asynchronously
                        // when the response is available
                        // console.log(response);
                        if(response.data.code == 0){
                            angular.forEach(response.data.data, function(value, key) {
                                this.push(value);
                            }, $scope.list);
                            $scope.p++;
                        }else{
                           $scope.loadMoreRes = false; 
                        }
                        $scope.$broadcast('scroll.infiniteScrollComplete');
                        console.log($scope.list);
                    }, function errorCallback(response) {
                        // called asynchronously if an error occurs
                        // or server returns response with an error status.
                    });
            }
        };
    })
    .controller('ArticleListsCtrl', function($scope, $stateParams, Lists,$http,$ionicLoading,$timeout,$ionicModal,$ionicHistory) {
        $ionicLoading.show({
            template: "Loading..."
        });
        $timeout(function(){
            //隐藏载入指示器
            $ionicLoading.hide();
        },1000);
        $scope.p = 1;
        $scope.list = [];
        $scope.loadMoreRes = true;
        $scope.id = document.getElementById('id').value;
        $scope.loadMore = function() {
            $scope.$broadcast('scroll.infiniteScrollComplete');
            if($scope.loadMoreRes){
                $http({
                      method: 'GET',
                      url: '/article/lists/id/'+$scope.id+'/p/'+$scope.p,
                    }).then(function successCallback(response) {
                        // this callback will be called asynchronously
                        // when the response is available
                        // console.log(response);
                        if(response.data.code == 0){
                            angular.forEach(response.data.data, function(value, key) {
                                this.push(value);
                            }, $scope.list);
                            $scope.p++;
                        }else{
                           $scope.loadMoreRes = false; 
                        }
                        $scope.$broadcast('scroll.infiniteScrollComplete');
                        console.log($scope.list);
                    }, function errorCallback(response) {
                        // called asynchronously if an error occurs
                        // or server returns response with an error status.
                    });
            }
        };
    })
    .controller('VideoListsCtrl', function($scope, $stateParams, Lists,$http,$ionicLoading,$timeout,$ionicModal,$ionicHistory) {
        $ionicLoading.show({
            template: "Loading..."
        });
        $timeout(function(){
            //隐藏载入指示器
            $ionicLoading.hide();
        },1000);
        $scope.p = 1;
        $scope.list = [];
        $scope.loadMoreRes = true;
        $scope.id = document.getElementById('id').value;
        $scope.loadMore = function() {
            $scope.$broadcast('scroll.infiniteScrollComplete');
            if($scope.loadMoreRes){
                $http({
                      method: 'GET',
                      url: '/video/lists/id/'+$scope.id+'/p/'+$scope.p,
                    }).then(function successCallback(response) {
                        // this callback will be called asynchronously
                        // when the response is available
                        // console.log(response);
                        if(response.data.code == 0){
                            angular.forEach(response.data.data, function(value, key) {
                                this.push(value);
                            }, $scope.list);
                            $scope.p++;
                        }else{
                           $scope.loadMoreRes = false; 
                        }
                        $scope.$broadcast('scroll.infiniteScrollComplete');
                        console.log($scope.list);
                    }, function errorCallback(response) {
                        // called asynchronously if an error occurs
                        // or server returns response with an error status.
                    });
            }
        };
    })
    .controller('VideoDetail', function($scope,$stateParams, Lists,$http,$ionicLoading,$timeout,$ionicHistory,$ionicNavBarDelegate,$ionicModal) {
//        $http.get("http://localhost/test.php").success(function(response) {console.log(response);});
        var backView = $ionicHistory.backView();
        $scope.indexBtn = true;
        if(!backView){
            //直接打开页面的,则提供一个按钮跳到首页
            $scope.indexBtn = false;
        }
        $scope.goIndex = function(){
            $ionicLoading.show({
                content: 'Loading',
                animation: 'fade-in',
                showBackdrop: true,
                maxWidth: 200,
                showDelay: 0
            });
            $timeout(function () {
                location.href = '/';
            }, 1000);
        };
        //显示载入指示器
        $ionicLoading.show({
            template: "Loading..."
        });
        //延时2000ms来模拟载入的耗时行为
        $timeout(function(){
            //隐藏载入指示器
            $ionicLoading.hide();
        },1000);
        var myImg = angular.element( document.querySelectorAll('img'));
        myImg.addClass('full-image');
        //$scope.title =  $stateParams.Id;
        //弹窗登陆
        /*
        $ionicModal.fromTemplateUrl('login.html', {
            scope: $scope,
            animation: 'slide-in-up'
        }).then(function(modal) {
            $scope.modal = modal;
        });
        $scope.login = function(){
            //这里执行登陆操作
            $scope.modal.hide();
        };
        $scope.canceLogin = function(){
            //取消登陆则关闭窗口
            $scope.modal.hide();
        };
        */
        /*$scope.openModal = function() {
            $scope.modal.show();
        };
        $scope.closeModal = function() {
            $scope.modal.hide();
        };*/
        $scope.replay = function(){
            //这里判断登陆状态
            if($scope.is_login == 0){
                //没有登陆则弹窗
                $scope.openLoginView();
            }
        };
    })
    .controller('ArticleDetail', function($scope,$stateParams, Lists,$http,$ionicLoading,$timeout,$ionicHistory,$ionicNavBarDelegate,$ionicModal) {
//        $http.get("http://localhost/test.php").success(function(response) {console.log(response);});
        var backView = $ionicHistory.backView();
        $scope.indexBtn = true;
        if(!backView){
            //直接打开页面的,则提供一个按钮跳到首页
            $scope.indexBtn = false;
        }
        $scope.goIndex = function(){
            $ionicLoading.show({
                content: 'Loading',
                animation: 'fade-in',
                showBackdrop: true,
                maxWidth: 200,
                showDelay: 0
            });
            $timeout(function () {
                location.href = '/';
            }, 1000);
        };
        //显示载入指示器
        $ionicLoading.show({
            template: "Loading..."
        });
        //延时2000ms来模拟载入的耗时行为
        $timeout(function(){
            //隐藏载入指示器
            $ionicLoading.hide();
        },1000);
        var myImg = angular.element( document.querySelectorAll('img'));
        myImg.addClass('full-image');
        //$scope.title =  $stateParams.Id;
        //弹窗登陆
        /*
        $ionicModal.fromTemplateUrl('login.html', {
            scope: $scope,
            animation: 'slide-in-up'
        }).then(function(modal) {
            $scope.modal = modal;
        });
        $scope.login = function(){
            //这里执行登陆操作
            $scope.modal.hide();
        };
        $scope.canceLogin = function(){
            //取消登陆则关闭窗口
            $scope.modal.hide();
        };
        */
        /*$scope.openModal = function() {
            $scope.modal.show();
        };
        $scope.closeModal = function() {
            $scope.modal.hide();
        };*/
        $scope.replay = function(){
            //这里判断登陆状态
            if($scope.is_login == 0){
                //没有登陆则弹窗
                $scope.openLoginView();
            }
        };
    })
    .controller('PicDetail', function($scope,$stateParams,$http,$ionicLoading,$timeout,$ionicHistory,$ionicNavBarDelegate,$ionicModal,$ionicActionSheet) {
        var backView = $ionicHistory.backView();
        $scope.indexBtn = true;
        if(!backView){
            //直接打开页面的,则提供一个按钮跳到首页
            $scope.indexBtn = false;
        }
        $scope.goIndex = function(){
            $ionicLoading.show({
                content: 'Loading',
                animation: 'fade-in',
                showBackdrop: true,
                maxWidth: 200,
                showDelay: 0
            });
            $timeout(function () {
                location.href = '/';
            }, 1000);
        };
        //显示载入指示器
        $ionicLoading.show({
            template: "Loading..."
        });
        //延时2000ms来模拟载入的耗时行为
        $timeout(function(){
            //隐藏载入指示器
            $ionicLoading.hide();
        },1000);
        var myImg = angular.element( document.querySelectorAll('img'));
        myImg.addClass('full-image');
        //$scope.title =  $stateParams.Id;
        //弹窗登陆
        /*
        $ionicModal.fromTemplateUrl('login.html', {
            scope: $scope,
            animation: 'slide-in-up'
        }).then(function(modal) {
            $scope.modal = modal;
        });
        $scope.login = function(){
            //这里执行登陆操作
            $scope.modal.hide();
        };
        $scope.canceLogin = function(){
            //取消登陆则关闭窗口
            $scope.modal.hide();
        };
        */
        $scope.replay = function(){
            //这里判断登陆状态
            if($scope.is_login == 0){
                //没有登陆则弹窗
                $scope.openLoginView();
            }
        };
        //长按图片
        $scope.goTodown = function(url){
            // Show the action sheet
            var hideSheet = $ionicActionSheet.show({
                buttons: [
                    { text: '保存' }
                ],
                titleText: '下载图片',
                cancelText: 'Cancel',
                cancel: function() {
                    // add cancel code..
                },
                buttonClicked: function(index) {
                    return true;
                }
            });
        };
    })
    .controller('MeCtrl', function(superCache,$rootScope,$scope,$stateParams,$http,$ionicLoading,$timeout,$ionicHistory,$ionicNavBarDelegate,$ionicModal,$ionicActionSheet) {
        $rootScope.is_login = is_login;
        console.log($rootScope.is_login);
        $scope.is_login = is_login;
        $scope.meCollect = true; // 收藏
        if(!$scope.is_login){
            $scope.meCollect = false;
        }
    })
    .controller('meLoginCtrl', function(superCache,$rootScope,$scope,$stateParams,$http,$ionicLoading,$timeout,$ionicHistory,$ionicNavBarDelegate,$ionicModal,$ionicActionSheet) {
        console.log($rootScope);
        console.log('meLoginCtrl');
        is_login = 1;
        console.log(is_login);
        superCache.put('is_login',is_login);
        $rootScope.is_login = 1;
    })
    .controller('MainCtrl', function($scope, $ionicSideMenuDelegate,$ionicModal,$ionicLoading,$timeout,$http) {
        $scope.is_login = is_login;
        //弹窗登陆
        $ionicModal.fromTemplateUrl('login.html', {
            scope: $scope
        }).then(function(modal) {
            $scope.loginModal = modal;
        });
        //打开登陆窗口
        $scope.openLoginView = function(){
            $scope.loginModal.show();
        };
        //关闭登陆窗口
        $scope.colseLoginView = function(){
            $scope.loginModal.hide();
        };
        $scope.login = function(){
            //这里是登陆操作
            if($scope.is_login == 0){
                /*$ionicLoading.show({
                    content: 'Loading',
                    animation: 'fade-in',
                    showBackdrop: true,
                    maxWidth: 200,
                    showDelay: 0
                });*/
                /*$http({
                    method:'jsonp',
                    url:API_URL + '/user/ajaxLogin?callback=JSON_CALLBACK',
                    params:{data:22,data1:223}
                }).success(function(response){
                        $ionicLoading.hide();
                        $scope.loginModal.hide();
                    });*/
            }
        };
        $scope.jump = function(url){
            /*$ionicLoading.show({
                template: "Loading..."
            });
            $timeout(function(){
                //隐藏载入指示器
                $ionicLoading.hide();
            },1000);*/
            location.href = url;
        };
    });
