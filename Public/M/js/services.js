/**
 * Created by Administrator on 2016/4/13.
 */
angular.module('starter.services', ['ngCookies'])
    .factory('Lists', function($http) {
        var Lists = [
            {
                'name':'资讯',
                'css':'bg_greenblue',
                'url':'/article/cateLists.html'
            },
            /*{
                'name':'养生',
                'css':'bg_pink',
                'url':'/artCategory/lists/id/2.html'
            },
            {
                'name':'育儿',
                'css':'bg_purple',
                'url':'/artCategory/lists/id/3.html'
            },
            {
                'name':'笑话',
                'css':'bg_green',
                'url':'/artCategory/lists/id/4.html'
            },*/
            {
                'name':'视频',
                'css':'bg_orange',
                'url':'/video/cateLists.html'
            },
            {
                'name':'图片',
                'css':'bg_red',
                'url':'/pic/cateLists.html'
            },
           /* {
                'name':'问答',
                'css':'bg_blue',
                'url':''
            },*/
            {
                'name':'更多',
                'css':'bg_grayc',
                'url':'/index/found.html'
            },
        ];
        return Lists;
    })
    .factory('superCache', ['$cacheFactory','$http', function($cacheFactory,$http) {
        console.log(is_login);
        //初始化的缓存
        //$http.get("http://localhost/phpinfo.php").success(function(response) {console.log(response);});
        var rootCache = $cacheFactory('super-cache');
        rootCache.put('user','我是qiuyuan');
        rootCache.put('is_login',is_login);
        return rootCache;
    }])
    .factory('superCookie', ['$cookieStore','$http', function($cookieStore,$http) {
        //angularjs cookie 存在版本的问题
        //初始化的cookie 为何返回不了异步的值??
        /*console.log($cookieStore.get('isLogin'));
        $http.jsonp(API_URL + '/user/isLogin?callback=JSON_CALLBACK')
            .success(function(response) {
                //console.log(response);
                if(response.code == 1){
                    $cookieStore.put('isLogin',0);
                }
            });
        return $cookieStore;*/
    }]);
