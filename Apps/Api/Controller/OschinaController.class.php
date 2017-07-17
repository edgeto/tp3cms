<?php
/**
 * Oschina
 * Class OschinaController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Api\Controller;

class OschinaController extends BaseController {

	public function notify($value=''){
		dump(I('get.'));exit;
	}

	public function oauth2Token(){
		$oauth['client_id'] = 'brktW6NBqNwmbi5Tb7gl';
		$oauth['client_secret'] = 'EtS1VSbcL75nd8jF49lZuIBGOVq6JlXu';
		$oauth['grant_type'] = 'authorization_code';
		$oauth['redirect_uri'] = 'oauth2Token';
		$oauth['code'] = 'abvs';
		$oauth['dataType'] = 'json';


	}

}