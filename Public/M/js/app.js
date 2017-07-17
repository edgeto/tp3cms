/**
 * Created by Administrator on 2016/4/13.
 */
    //App 首页
var App = angular.module('App', ['ionic','starter.controllers', 'starter.services']);
App.config(function($ionicConfigProvider,$stateProvider,$urlRouterProvider,$interpolateProvider) {
    //替换边界
    $interpolateProvider.startSymbol('<{');
    $interpolateProvider.endSymbol('}>');
    //默认返回按钮
    $ionicConfigProvider.backButton.text('返回').icon('ion-ios-arrow-back');
    //初始化：android标题居中
    $ionicConfigProvider.platform.android.navBar.alignTitle('center');
    $ionicConfigProvider.platform.android.tabs.position('bottom');
    $stateProvider
        .state('app', {
            url: "/app",
            abstract: true,
            templateUrl: "app.html"
        })
        .state('app.home', {
            url: "/home",
            views: {
                'home': {
                    templateUrl: "home.html",
                    controller: 'HomeTabCtrl'
                }
            }
        });
    $urlRouterProvider.otherwise("/app/home");
});
App.directive('hideTabs',function($rootScope){
    return {
        restrict:'AE',
        link:function($scope){
            $rootScope.hideTabs = 'tabs-item-hide';
            $scope.$on('$destroy',function(){
                $rootScope.hideTabs = ' ';
            });
        }
    };
});
    //Lists 列表页
var Lists = angular.module('Lists', ['ionic','starter.controllers', 'starter.services']);
Lists.config(function($ionicConfigProvider,$stateProvider,$urlRouterProvider,$interpolateProvider) {
    //替换边界
    $interpolateProvider.startSymbol('<{');
    $interpolateProvider.endSymbol('}>');
    //默认返回按钮
    $ionicConfigProvider.backButton.text('返回').icon('ion-ios-arrow-back');
    //初始化：android标题居中
    $ionicConfigProvider.platform.android.navBar.alignTitle('center');
    $ionicConfigProvider.platform.android.tabs.position('bottom');
    $stateProvider
        .state('app', {
            url: "/app",
            abstract: true,
            templateUrl: "app.html"
        })
        .state('app.lists', {
            url: "/lists",
            views: {
                'lists': {
                    templateUrl: "lists.html",
                    controller: 'listsCtrl'
                }
            }
        })
        .state('app.login', {
            url: "/login",
            views: {
                'login': {
                    templateUrl: "login.html"
                }
            }
        });
    $urlRouterProvider.otherwise("/app/lists");
});
    //Lists 列表页
var PicLists = angular.module('PicLists', ['ionic','starter.controllers', 'starter.services']);
PicLists.config(function($ionicConfigProvider,$stateProvider,$urlRouterProvider,$interpolateProvider) {
    //替换边界
    $interpolateProvider.startSymbol('<{');
    $interpolateProvider.endSymbol('}>');
    //默认返回按钮
    $ionicConfigProvider.backButton.text('返回').icon('ion-ios-arrow-back');
    //初始化：android标题居中
    $ionicConfigProvider.platform.android.navBar.alignTitle('center');
    $ionicConfigProvider.platform.android.tabs.position('bottom');
    $stateProvider
        .state('app', {
            url: "/app",
            abstract: true,
            templateUrl: "app.html"
        })
        .state('app.lists', {
            url: "/lists",
            views: {
                'lists': {
                    templateUrl: "lists.html",
                    controller: 'PicListsCtrl'
                }
            }
        })
        .state('app.login', {
            url: "/login",
            views: {
                'login': {
                    templateUrl: "login.html"
                }
            }
        });
    $urlRouterProvider.otherwise("/app/lists");
});
    //Lists 列表页
var ArticleLists = angular.module('ArticleLists', ['ionic','starter.controllers', 'starter.services']);
ArticleLists.config(function($ionicConfigProvider,$stateProvider,$urlRouterProvider,$interpolateProvider) {
    //替换边界
    $interpolateProvider.startSymbol('<{');
    $interpolateProvider.endSymbol('}>');
    //默认返回按钮
    $ionicConfigProvider.backButton.text('返回').icon('ion-ios-arrow-back');
    //初始化：android标题居中
    $ionicConfigProvider.platform.android.navBar.alignTitle('center');
    $ionicConfigProvider.platform.android.tabs.position('bottom');
    $stateProvider
        .state('app', {
            url: "/app",
            abstract: true,
            templateUrl: "app.html"
        })
        .state('app.lists', {
            url: "/lists",
            views: {
                'lists': {
                    templateUrl: "lists.html",
                    controller: 'ArticleListsCtrl'
                }
            }
        })
        .state('app.login', {
            url: "/login",
            views: {
                'login': {
                    templateUrl: "login.html"
                }
            }
        });
    $urlRouterProvider.otherwise("/app/lists");
});
    //ArticleDetail 详情页面
var ArticleDetail = angular.module('ArticleDetail', ['ionic','starter.controllers', 'starter.services']);
ArticleDetail.config(function($ionicConfigProvider,$stateProvider,$urlRouterProvider,$interpolateProvider) {
    //替换边界
    $interpolateProvider.startSymbol('<{');
    $interpolateProvider.endSymbol('}>');
    //默认返回按钮
    $ionicConfigProvider.backButton.text('返回').icon('ion-ios-arrow-back');
    //初始化：android标题居中
    $ionicConfigProvider.platform.android.navBar.alignTitle('center');
    $ionicConfigProvider.platform.android.tabs.position('bottom');
    $stateProvider
        .state('app', {
            url: "/app",
            abstract: true,
            templateUrl: "app.html"
        })
        .state('app.detail', {
            url: "/detail",
            views: {
                'detail': {
                    templateUrl: "detail.html",
                    controller: 'ArticleDetail'
                }
            }
        })
        .state('app.login', {
            url: "/login",
            views: {
                'detail': {
                    templateUrl: "login.html"
                }
            }
        });
    $urlRouterProvider.otherwise("/app/detail");
});
    //Lists 列表页
var VideoLists = angular.module('VideoLists', ['ionic','starter.controllers', 'starter.services']);
VideoLists.config(function($ionicConfigProvider,$stateProvider,$urlRouterProvider,$interpolateProvider) {
    //替换边界
    $interpolateProvider.startSymbol('<{');
    $interpolateProvider.endSymbol('}>');
    //默认返回按钮
    $ionicConfigProvider.backButton.text('返回').icon('ion-ios-arrow-back');
    //初始化：android标题居中
    $ionicConfigProvider.platform.android.navBar.alignTitle('center');
    $ionicConfigProvider.platform.android.tabs.position('bottom');
    $stateProvider
        .state('app', {
            url: "/app",
            abstract: true,
            templateUrl: "app.html"
        })
        .state('app.lists', {
            url: "/lists",
            views: {
                'lists': {
                    templateUrl: "lists.html",
                    controller: 'VideoListsCtrl'
                }
            }
        })
        .state('app.login', {
            url: "/login",
            views: {
                'login': {
                    templateUrl: "login.html"
                }
            }
        });
    $urlRouterProvider.otherwise("/app/lists");
});
    //VideoDetail 详情页面
var VideoDetail = angular.module('VideoDetail', ['ionic','starter.controllers', 'starter.services']);
VideoDetail.config(function($ionicConfigProvider,$stateProvider,$urlRouterProvider,$interpolateProvider) {
    //替换边界
    $interpolateProvider.startSymbol('<{');
    $interpolateProvider.endSymbol('}>');
    //默认返回按钮
    $ionicConfigProvider.backButton.text('返回').icon('ion-ios-arrow-back');
    //初始化：android标题居中
    $ionicConfigProvider.platform.android.navBar.alignTitle('center');
    $ionicConfigProvider.platform.android.tabs.position('bottom');
    $stateProvider
        .state('app', {
            url: "/app",
            abstract: true,
            templateUrl: "app.html"
        })
        .state('app.detail', {
            url: "/detail",
            views: {
                'detail': {
                    templateUrl: "detail.html",
                    controller: 'VideoDetail'
                }
            }
        })
        .state('app.login', {
            url: "/login",
            views: {
                'detail': {
                    templateUrl: "login.html"
                }
            }
        });
    $urlRouterProvider.otherwise("/app/detail");
});
    //PicDetail 详情页面
var PicDetail = angular.module('PicDetail', ['ionic','starter.controllers', 'starter.services']);
PicDetail.config(function($ionicConfigProvider,$stateProvider,$urlRouterProvider,$interpolateProvider) {
    //替换边界
    $interpolateProvider.startSymbol('<{');
    $interpolateProvider.endSymbol('}>');
    //默认返回按钮
    $ionicConfigProvider.backButton.text('返回').icon('ion-ios-arrow-back');
    //初始化：android标题居中
    $ionicConfigProvider.platform.android.navBar.alignTitle('center');
    $ionicConfigProvider.platform.android.tabs.position('bottom');
    $stateProvider
        .state('app', {
            url: "/app",
            abstract: true,
            templateUrl: "app.html"
        })
        .state('app.detail', {
            url: "/detail",
            views: {
                'detail': {
                    templateUrl: "detail.html",
                    controller: 'PicDetail'
                }
            }
        })
        .state('app.login', {
            url: "/login",
            views: {
                'detail': {
                    templateUrl: "login.html"
                }
            }
        });
    $urlRouterProvider.otherwise("/app/detail");
});
    //Found 发现
var Found = angular.module('Found', ['ionic','starter.controllers', 'starter.services']);
Found.config(function($ionicConfigProvider,$stateProvider,$urlRouterProvider,$interpolateProvider) {
    //替换边界
    $interpolateProvider.startSymbol('<{');
    $interpolateProvider.endSymbol('}>');
    //默认返回按钮
    $ionicConfigProvider.backButton.text('返回').icon('ion-ios-arrow-back');
    //初始化：android标题居中
    $ionicConfigProvider.platform.android.navBar.alignTitle('center');
    $ionicConfigProvider.platform.android.tabs.position('bottom');
    $stateProvider
        .state('app', {
            url: "/app",
            abstract: true,
            templateUrl: "app.html"
        })
        .state('app.found', {
            url: "/found",
            views: {
                'found': {
                    templateUrl: "found.html",
                    controller: 'FoundCtrl'
                }
            }
        });
    $urlRouterProvider.otherwise("/app/found");
});
App.directive('hideTabs',function($rootScope){
    return {
        restrict:'AE',
        link:function($scope){
            $rootScope.hideTabs = 'tabs-item-hide';
            $scope.$on('$destroy',function(){
                $rootScope.hideTabs = ' ';
            });
        }
    };
});
    //Ask 问答
var Ask = angular.module('Ask', ['ionic','starter.controllers', 'starter.services']);
Ask.config(function($ionicConfigProvider,$stateProvider,$urlRouterProvider,$interpolateProvider) {
    //替换边界
    $interpolateProvider.startSymbol('<{');
    $interpolateProvider.endSymbol('}>');
    //默认返回按钮
    $ionicConfigProvider.backButton.text('返回').icon('ion-ios-arrow-back');
    //初始化：android标题居中
    $ionicConfigProvider.platform.android.navBar.alignTitle('center');
    $ionicConfigProvider.platform.android.tabs.position('bottom');
    $stateProvider
        .state('app', {
            url: "/app",
            abstract: true,
            templateUrl: "app.html"
        })
        .state('app.ask', {
            url: "/ask",
            views: {
                'ask': {
                    templateUrl: "ask.html",
                }
            }
        });
    $urlRouterProvider.otherwise("/app/ask");
});
App.directive('hideTabs',function($rootScope){
    return {
        restrict:'AE',
        link:function($scope){
            $rootScope.hideTabs = 'tabs-item-hide';
            $scope.$on('$destroy',function(){
                $rootScope.hideTabs = ' ';
            });
        }
    };
});
    //Me 我的
var Me = angular.module('Me', ['ionic','starter.controllers', 'starter.services']);
Me.config(function($ionicConfigProvider,$stateProvider,$urlRouterProvider,$interpolateProvider) {
    //替换边界
    $interpolateProvider.startSymbol('<{');
    $interpolateProvider.endSymbol('}>');
    //默认返回按钮
    $ionicConfigProvider.backButton.text('返回').icon('ion-ios-arrow-back');
    //初始化：android标题居中
    $ionicConfigProvider.platform.android.navBar.alignTitle('center');
    $ionicConfigProvider.platform.android.tabs.position('bottom');
    $stateProvider
        .state('app', {
            url: "/app",
            abstract: true,
            templateUrl: "app.html"
        })
        .state('app.me', {
            url: "/me",
            views: {
                'me': {
                    templateUrl: "me.html",
                    controller: 'MeCtrl'
                }
            }
        })
        .state('app.login', {
            url: "/login",
            views: {
                'me': {
                    templateUrl: "login.html",
                    controller: 'meLoginCtrl'
                }
            }
        })
        .state('app.user', {
            url: "/user",
            views: {
                'me': {
                    templateUrl: "user.html"
                }
            }
        })
        .state('app.about', {
            url: "/about",
            views: {
                'me': {
                    templateUrl: "about.html"
                }
            }
        })
        .state('app.collect', {
            url: "/collect",
            views: {
                'me': {
                    templateUrl: "collect.html"
                }
            }
        });
    $urlRouterProvider.otherwise("/app/me");
});
App.directive('hideTabs',function($rootScope){
    return {
        restrict:'AE',
        link:function($scope){
            $rootScope.hideTabs = 'tabs-item-hide';
            $scope.$on('$destroy',function(){
                $rootScope.hideTabs = ' ';
            });
        }
    };
});

