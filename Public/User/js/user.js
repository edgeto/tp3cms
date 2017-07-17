// 登陆注册相关
var login = angular.module('Login',[]);
login.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('<{');
    $interpolateProvider.endSymbol('}>');
});
login.controller('LoginCate',['$scope', function($scope) {
    var doRegisterElement = angular.element(document.querySelector('#doRegister'));
    var doLoginElement = angular.element(document.querySelector('#doLogin'));
    var fromElement = angular.element(document.querySelector('#referer_url'));
    console.log(doRegisterElement.html());
    if(fromElement.val()){
        doRegisterElement.attr('href', doRegisterElement.attr('href')+'?referer_url='+fromElement.val());
        doLoginElement.attr('href', doLoginElement.attr('href')+'?referer_url='+fromElement.val());
    }
}]);


// 修改头像
var avatur = angular.module('Avatur', ['ngFileUpload']);
avatur.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('<{');
    $interpolateProvider.endSymbol('}>');
});
//上传图片
avatur.controller('AvaturCate',['$scope', 'Upload', function($scope, Upload) {
    $scope.avaturBtn = false;
    var avaturBtn = angular.element(document.querySelector('#avaturBtn'));
    // upload on file select or drop
    $scope.upload = function (file) {
        avaturBtn.html('修改中。。。');
        $scope.avaturBtn = true;
        if(file){
            Upload.upload({
                url: '/index/avatur',
                data: {file: file}
            }).then(function (resp) {
                console.log(resp.data);
                if(resp.data.code == 0){
                    $scope.avaturBtn = false;
                    avaturBtn.html('修改头像');
                }
                //成功
            }, function (resp) {
                //失败 
                console.log(resp);
            }, function (evt) {
                //操作
                console.log(evt);
            });
        }
    };
}]);

// 邮箱相关
var email = angular.module('Email',[]);
email.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('<{');
    $interpolateProvider.endSymbol('}>');
});
email.controller('EmailCate',['$scope', '$http','$interval',function($scope,$http,$interval) {
    var MyModalElement = angular.element(document.querySelector('#MyModal'));
    var emailElement = angular.element(document.querySelector('#email'));
    var MyModalElementTitle = angular.element(document.querySelector('#MyModal #myModalLabel'));
    var getCodeElement = angular.element(document.querySelector('.getCode'));
    var codeElement = angular.element(document.querySelector('#code'));
    // 获取验证码
    $scope.getCode = function(){
        if(emailElement.val() == ''){
            MyModalElementTitle.html('请选填写邮箱地址!');
            MyModalElement.modal('toggle');
        }else{
            if(isEmail(emailElement.val())){
                $http({
                  method: 'POST',
                  url: '/security/getEmailCode',
                  data:{email:emailElement.val()}
                }).then(function successCallback(response) {
                    // this callback will be called asynchronously
                    // when the response is available
                    console.log(response);
                    if(response.data.code != 0){
                        MyModalElementTitle.html(response.data.msg);
                        MyModalElement.modal('toggle');
                    }else{
                        var time_out = 60;
                        var timer = $interval(function(){
                            time_out--;
                            getCodeElement.attr('disabled', 'disabled');
                            getCodeElement.html('等待'+time_out+'秒');
                            if(time_out <= 0){
                                getCodeElement.removeAttr('disabled');    
                                getCodeElement.html('获取验证码');
                                $interval.cancel(timer);
                            }
                        }, 1000);
                    }
                    
                }, function errorCallback(response) {
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.
                });
            }else{
                MyModalElementTitle.html('请选填写邮箱地址!');
                MyModalElement.modal('toggle');
            }
        }
    };
    // 绑定邮箱
    $scope.email = function(){
        if(emailElement.val() == ''){
            MyModalElementTitle.html('请选填写邮箱地址!');
            MyModalElement.modal('toggle');
            return false;
        }
        if(codeElement.val() == ''){
            MyModalElementTitle.html('请选填写验证码!');
            MyModalElement.modal('toggle');
            return false;
        }
        $http({
          method: 'POST',
          url: '/security/email',
          data:{email:emailElement.val(),code:codeElement.val()}
        }).then(function successCallback(response) {
            // this callback will be called asynchronously
            // when the response is available
            console.log(response);
            if(response.data.code != 0){
                MyModalElementTitle.html(response.data.msg);
                MyModalElement.modal('toggle');
            }else{
                location.href = response.data.data;
            }
            
        }, function errorCallback(response) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
        });
    };
}]);


/*判断电话格式*/
function isTelephone (telephone) {
    var telReg = !!telephone.match(/^(0|86|17951)?(13[0-9]|15[012356789]|17[6780]|18[0-9]|14[57])[0-9]{8}$/);
    //如果手机号码不能通过验证
    return telReg;
}

/*判断邮箱格式*/
function isEmail (email) {
    var emailReg = !!email.match(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/);
    //如果邮箱不能通过验证
    return emailReg;
}

