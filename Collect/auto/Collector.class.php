<?php 
/**
* 
*/
class Collector
{

	/**
	 * [采集的数据库]
	 * @var string
	 */
	public $_db = 'ys_url_collect';

	/**
	 * 文章数据库
	 * @var string
	 */
	public $_news_db = 'ys_article';

	/**
	 * 相册表
	 * @var string
	 */
	public $_pic_db = 'ys_pic';

	/**
	 * 图片表
	 * @var string
	 */
	public $_pic_img_db = 'ys_pic_img';

	/**
	 * PDO类
	 * @var [type]
	 */
	public $_pdo;

	/**
	 * REDIS
	 * @var [type]
	 */
	public $_redis;

	/**
	 * redis_key
	 * @var string
	 */
	public $_redis_collect_key = '__resdis__collect__key__';

	/**
	 * 日志主目录
	 * @var string
	 */
	public $_base_log = 'Log';

	/**
	 * sql错误日志目录
	 * @var string
	 */
	public $_sql_log = 'sql.log';

	/**
	 * 已采集过的日志目录
	 * @var string
	 */
	public $_url_log = 'url.log';

	/**
	 * 采集日志
	 * @var string
	 */
	public $_collect_log = 'collect.log';

	/**
	 * 图片上传根目录
	 * @var string
	 */
	public $_upload_path = '../../Uploads/Images/';

	/**
	 * 图片url目录
	 * @var string
	 */
	public $_url_path = '/Uploads/Images/';

	/**
	 * 初始化
	 */
	public function __construct()
	{
		header("Content-type:text/html;charset=utf-8");
		global $pdo;
		$this->_pdo = $pdo;
		global $redis;
		$this->_redis = $redis;
		// $this->_sql_log = $this->_base_log.'/'.@date('Y-m-d').'_'.$this->_sql_log;
		// $this->_url_log = $this->_base_log.'/'.@date('Y-m-d').'_'.$this->_url_log;
		// $this->_collect_log = $this->_base_log.'/'.@date('Y-m-d').'_'.$this->_collect_log;
		$this->_sql_log = $this->_base_log.'/'.$this->_sql_log;
		$this->_url_log = $this->_base_log.'/'.$this->_url_log;
		$this->_collect_log = $this->_base_log.'/'.$this->_collect_log;
		$this->_upload_path .= @date('Ymd').'/';
		$this->mkImgDir($this->_upload_path);	
		$this->_url_path = QDURL.$this->_url_path.@date('Ymd').'/';	
	}

	/**
	 * 取链要采集的链接
	 * @param  integer $type [description]
	 * @param  string  $url  [description]
	 * @return [type]        [description]
	 */
	public function getUrl($type = 1,$url = ''){
		if($type && $url){
			error_log(@date('Y-m-d H:i:s')."\r\n"."beginning:getUrl\r\n",3,$this->_collect_log);
			$sql = "select id from ys_url_collect where status = 0 and type = ? and category = 0 order by id desc";
			$tmp = $this->_pdo->prepare($sql);
			$tmp->bindValue(1, $type, PDO::PARAM_INT);
			$tmp->execute();
			$list = $tmp->fetch();
			if(empty($list)){
				// 已经采集完成
				$this->setUrl($type,$url);
			}else{
				$this->setContent($type);
			}
			error_log(@date('Y-m-d H:i:s')."\r\n"."ending:getUrl\r\n",3,$this->_collect_log);
		}
	}

	/**
	 * 把要采集的链接记录下来
	 * @param integer $type [description]
	 * @param string  $url  [description]
	 */
	public function setUrl($type = 1,$url = ''){
		if($type && $url){
			switch ($type) {
				case 2:
					break;
				default:
					$this->setBaijiaUrl($type,$url);
					break;
			}
			error_log(@date('Y-m-d H:i:s')."\r\n"."endding:setUrl\r\n",3,$this->_collect_log);
		}
	}

	/**
	 * 记录采集内容
	 * @param integer $type [description]
	 */
	public function setContent($type = 1){
		if($type){
			// 每次更新15条
			$sql = "select id,url from ys_url_collect where status = 0 and type = ? order by id desc limit 15";
			$tmp = $this->_pdo->prepare($sql);
			$tmp->bindValue(1, $type, PDO::PARAM_INT);
			$tmp->execute();
			$list = $tmp->fetchAll();
			if($list){
				switch ($type) {
					case 2:
						break;
					default:
						$this->setBaijiaContent($list);
						break;
				}
			}
			error_log(@date('Y-m-d H:i:s')."\r\n"."endding:setContent\r\n",3,$this->_collect_log);
		}
	}

	/**
	 * 采集百度百家的url链接
	 * @param integer $type [description]
	 * @param string  $url  [description]
	 */
	public function setBaijiaUrl($type = 1,$url = ''){
		$data = array();
		if($url){
			if(is_array($url)){
				foreach ($url as $key => $_url) {
					$_url_data = $this->setBaijiaUrlSingle($type,$_url);
					if($_url_data){
						$data = array_merge($data,$_url_data);
						sleep(3);
					}
				}
			}else{
				$data = $this->setBaijiaUrlSingle($type,$url);
			}
		}
		if($data){
			$this->insertAll($this->_db,$data);
		}
	}

	/**
	 * [单个百家 description]
	 * @param [type] $type [description]
	 * @param string $url  [description]
	 * @param int $category  [description]
	 */
	public function setBaijiaUrlSingle($type = 1,$url='',$category = 0){
		$data = array();
		if($type && $url){
			$html = file_get_html($url);
			if($html){
				$content = $html->find('div.feeds',0);
				if($content){
					$count = 0;
					/*$url_log_content = '';
					if(is_file($this->_url_log)){
						$url_log_content = file_get_contents($this->_url_log);
					}*/
					foreach ($content->find('h3 a') as $key => $value) {
						// 取最新10条
						if($count >= 10){
							break;
						}
						$patter = "/\/article\/\d+/";
						$href = $value->href;
						if($href && preg_match($patter,$href)){
							$is_exist = $this->isRedisUrl($this->_redis_collect_key,$href);
							if(!$is_exist){
								$is_can = true;
								// 防止redis过期的情况
								$isRedisUrl = $this->getRedisUrl($this->_redis_collect_key);
								if(empty($isRedisUrl)){
									$sql = "select id from {$this->_db} whre url = ?";
									$tmp = $this->_pdo->prepare($sql);
									$tmp->bindValue(1, $href, PDO::PARAM_STR);
									$tmp->execute();
									$list = $tmp->fetch();
									if(!empty($list)){
										$is_can = false;	
									}
								}
								if($is_can){
									$this->setRedisUrl($this->_redis_collect_key,$href);
									$count++;
									$tmp = array('type'=>$type,'url'=>$value->href,'add_time'=>time(),'category'=>$category);
									$data[] = $tmp;
								}
							}
							/*if(!stristr($url_log_content,$href)){
								$count++;
								$tmp = array('type'=>$type,'url'=>$value->href,'add_time'=>time());
								$data[] = $tmp;
							}*/
						}
					}
				}
			}
		}
		return $data;
	}

	/**
	 * 采集百度百家的内容
	 * @param array $url [description]
	 */
	public function setBaijiaContent($url = array()){
		if($url && is_array($url)){
			$data = array();
			foreach ($url as $key => $value) {
				if(!empty($value['url'])){
					$tmp = array();
					$_url = $value['url'];
					$html = file_get_html($_url);
					$title = $html->find('div#page h1',0)->plaintext;
					// 引用
					$desc = $html->find('blockquote',0)->plaintext;
					// $content = $html->find('div.article-detail',0)->outertext;
					$_content = $html->find('div.article-detail',0);
					// 去掉广告
					if($_content->find('div.l-main-inner-ad',0)){
						$_content->find('div.l-main-inner-ad',0)->outertext = '';
					}
					// 文章主图
					$img = '';
					$hasImg = false;
					// 替换图片
					foreach ($_content->find('img') as $key => $element) {
						$new_img_src = $this->SaveImg($element->src);
						if($new_img_src){
							if(!$hasImg){
								$img = $new_img_src;
								$hasImg = true;
							}
							$_content->find('img',$key)->src = $new_img_src;
							sleep(3);
						}
					}
					$content = $_content->outertext;
					if($title && $desc && $content){
						$tmp['title'] = $title;
						$tmp['abstract'] = $desc;
						$tmp['desc'] = $desc;
						$tmp['img'] = $img;
						$tmp['content'] = htmlspecialchars($content);
						$tmp['status'] = 0;
						$tmp['collect_url'] = $_url;
						$tmp['add_time'] = time();
						$tmp['update_time'] = time();
						$res = $this->insert($this->_news_db,$tmp);
						if($res){
							$now_time = time();
							$up_sql = "UPDATE {$this->_db} SET `status` = 1, `collect_time` = {$now_time} WHERE id = ? ";
							$up_rs = $this->_pdo->prepare($up_sql);
							$up_rs->bindValue(1, $value['id'], PDO::PARAM_INT);
							$upup = $up_rs->execute();
							// 改为用redis了
							// $msg = ','.$_url;
							// error_log($msg,3,$this->_url_log);
						}
					}
				}
				sleep(3);
			}
		}
	}

	/**
	 * 多重数据去重
	 * @param  array  $array [description]
	 * @return [type]        [description]
	 */
	public function unique($array = array()){
		$data = array();
		if($array){
			foreach ($array as $key => $value) {
				if(!in_array($value, $data)){
					$data[] = $value;
				}
			}
		}
		return $data;
	}

	/**
	 * 批量插入
	 * @param  [type] $table_name [description]
	 * @param  [type] $itemAll    [description]
	 * @return [type]             [description]
	 */
 	public function insertAll($table_name,$itemAll){
 		$itemAll = $this->unique($itemAll);
 		shuffle($itemAll);
 		if($table_name && is_array($itemAll)){
 			$fields = array_keys($itemAll[0]);
 			$values = array();
 			foreach ($itemAll as $_key => $_value) {
 				$value = '(';
 				foreach ($_value as $_k => $_v) {
 					if(is_string($_v)){
 						$value .=  '\''.$_v.'\',';
 					}else{
 						$value .= $_v.',';
 					}
 				}
 				$value = rtrim($value,',').')';
 				$values[] = $value;
 			}
 			$sql = "INSERT INTO $table_name (".implode(',', $fields).") VALUES ".implode(',',$values);
 			$rs = $this->_pdo->prepare($sql);
		    $rs->execute();
		    if($rs->errorCode() != '00000'){
				$msg = @date('Y-m-d H:i:s:')."\r\n"."function:insertAll\r\n".json_encode($rs->errorInfo());
				error_log($msg,3,$this->_sql_log);
				die();
			}
 		}
 	}

 	/**
 	 * 单个插入
 	 * @param  [type] $table_name [description]
 	 * @param  [type] $item       [description]
 	 * @return [type]             [description]
 	 */
	public function insert($table_name,$item){
		if($item == null || !is_array($item)){
			return false;
		}
		
		$fields = array_keys($item);
		$values = array_values($item);
		
		if(is_array($fields) ){
			$fld = '`'.implode('`,`',$fields).'`';
		}else{
			$fld = $fields;
		}
		
		if(is_array($values) ){
			
			$val = '';
			foreach($values as $v){
				$v = stripslashes($v);
				$v = addslashes($v);
				$val.="'$v',";
			}
			
			$val = substr($val,0,-1);

		}else{
			$val=$values;
		}
		 
		$sql="insert into $table_name($fld) values($val)";
		$rs = $this->_pdo->prepare($sql);
		$rs->execute();
		if($rs->errorCode() != '00000'){
			$msg = @date('Y-m-d H:i:s:')."\r\n"."function:insert\r\n".json_encode($rs->errorInfo());
			error_log($msg,3,$this->_sql_log);
			die();
		}
		return $this->_pdo->lastInsertId();
	}

	/**
	 * 添加集合元素
	 * @param string $key [description]
	 * @param string $url [description]
	 */
	public function setRedisUrl($key='',$url=''){
	  	//添加到集合里面
	  	$key = $key ? $key : $this->_redis_collect_key;
	  	if($key && $url){
        	$this->_redis->sAdd($key,$url);
	        //设置过期时间
	        // $this->_redis->expire($key,$expire+86400);
	  	}
	}  

	/**
	 * 取集合元素
	 * @param  string $key [description]
	 * @return [type]      [description]
	 */
	public function getRedisUrl($key=''){
		$key = $key ? $key : $this->_redis_collect_key;
		return $this->_redis->sMembers($key);
	}

	/**
	 * 删除集合
	 * @param  string $key [description]
	 * @return [type]      [description]
	 */
	public function delRedisUrl($key=''){
		$key = $key ? $key : $this->_redis_collect_key;
		if($key){
			$this->_redis->del($key);
		}
	}

	/**
	 * 判断值是否是集合成员
	 * @param  string  $key [description]
	 * @param  string  $url [description]
	 * @return boolean      [description]
	 */
	public function isRedisUrl($key='',$url=''){
		$key = $key ? $key : $this->_redis_collect_key;
		if($key && $url){
			return $this->_redis->Sismember($key,$url);
		}
		return false;
	}

	/**
	 * 生成图目录
	 * @param  string $dir [description]
	 * @return [type]      [description]
	 */
	public function mkImgDir($dir=''){
		if($dir && !is_dir($dir)){
			if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
		        
		    }else{
		        $str = "mkdir -p " . $dir;
		        $res = system($str,$retval);
		        if($retval != 0){
		            error_log(@date('Y-m-d H:i:s')."\r\n"."mkImgDir:error\r\n",3,$this->_collect_log);
		            exit;
		        }
		    }
		}
	}

	/**
	 * 保存远程图片
	 * @param string $url [description]
	 */
	public function SaveImg($url=''){
		$url_name = '';
		if($url){
			$file = time().rand(0,99).'.jpg';
			$file_name = $this->_upload_path.$file;
			if(@file_put_contents($file_name,@file_get_contents($url))){
				$url_name = $this->_url_path.$file;
			}
			
		}
		return $url_name;
	}

	/**
	 * 取链要采集的链接
	 * @param  integer $type [description]
	 * @param  string  $url  [description]
	 * @return [type]        [description]
	 */
	public function getPicUrl($type = 1,$url = ''){
		if($type && $url){
			error_log(@date('Y-m-d H:i:s')."\r\n"."beginning:getPicUrl\r\n",3,$this->_collect_log);
			$sql = "select id from ys_url_collect where status = 0 and type = ? and category = 1 order by id desc";
			$tmp = $this->_pdo->prepare($sql);
			$tmp->bindValue(1, $type, PDO::PARAM_INT);
			$tmp->execute();
			$list = $tmp->fetch();
			// if(empty($list)){
			if(1){
				// 已经采集完成
				$this->setPicUrl($type,$url);
			}else{
				$this->setPicContent($type);
			}
			error_log(@date('Y-m-d H:i:s')."\r\n"."ending:getUrl\r\n",3,$this->_collect_log);
		}
	}

	/**
	 * 把要采集的链接记录下来
	 * @param integer $type [description]
	 * @param string  $url  [description]
	 */
	public function setPicUrl($type = 1,$url = ''){
		if($type && $url){
			switch ($type) {
				case 2:
					break;
				default:
					$this->setMeiTuLuUrl($type,$url);
					break;
			}
			error_log(@date('Y-m-d H:i:s')."\r\n"."endding:setUrl\r\n",3,$this->_collect_log);
		}
	}

	public function setMeiTuLuUrl($type = 1,$url = ''){
		$data = array();
		if($url){
			if(is_array($url)){
				foreach ($url as $key => $_url) {
					$_url_data = $this->setMeiTuLuUrlSingle($type,$_url);
					if($_url_data){
						$data = array_merge($data,$_url_data);
						sleep(3);
					}
				}
			}else{
				$data = $this->setMeiTuLuUrlSingle($type,$url);
			}
		}
		if($data){
			$this->insertAll($this->_db,$data);
		}
	}

	/**
	 * [单个美图录 description]
	 * @param [type] $type [description]
	 * @param string $url  [description]
	 * @param int $category  [description]
	 */
	public function setMeiTuLuUrlSingle($type = 1,$url='',$category = 1){
		$data = array();
		if($type && $url){
			$html = file_get_html($url);
			if($html){
				$content = $html->find('.main',0);
				if($content){
					$count = 0;
					foreach ($content->find('li a') as $key => $value) {
						// 取最新10条
						// if($count >= 10){
						// 	break;
						// }
						$patter = "/\/item\/\d+(\.html)?/";
						$href = $value->href;
						if($href && preg_match($patter,$href)){
							$is_exist = $this->isRedisUrl($this->_redis_collect_key,$href);
							if(!$is_exist){
								$is_can = true;
								// 防止redis过期的情况
								$isRedisUrl = $this->getRedisUrl($this->_redis_collect_key);
								if(empty($isRedisUrl)){
									$sql = "select id from {$this->_db} whre url = ?";
									$tmp = $this->_pdo->prepare($sql);
									$tmp->bindValue(1, $href, PDO::PARAM_STR);
									$tmp->execute();
									$list = $tmp->fetch();
									if(!empty($list)){
										$is_can = false;	
									}
								}
								if($is_can){
									$this->setRedisUrl($this->_redis_collect_key,$href);
									// $count++;
									$tmp = array('type'=>$type,'url'=>$value->href,'add_time'=>time(),'category'=>$category);
									$data[] = $tmp;
								}
							}
						}
					}
				}
			}
		}
		return $data;
	}

	/**
	 * 记录采集内容
	 * @param integer $type [description]
	 */
	public function setPicContent($type = 1,$category = 1){
		if($type){
			// 每次更新15条
			$sql = "select id,url from ys_url_collect where status = 0 and type = ? and category = ? limit 15";
			$tmp = $this->_pdo->prepare($sql);
			$tmp->bindValue(1, $type, PDO::PARAM_INT);
			$tmp->bindValue(2, $category, PDO::PARAM_INT);
			$tmp->execute();
			$list = $tmp->fetchAll();
			if($list){
				switch ($type) {
					case 2:
						break;
					default:
						$this->setMeiTuLuContent($list);
						break;
				}
			}
			error_log(@date('Y-m-d H:i:s')."\r\n"."endding:setContent\r\n",3,$this->_collect_log);
		}
	}

	/**
	 * 采集美图录的图片
	 * @param array $url [description]
	 */
	public function setMeiTuLuContent($url = array()){
		if($url && is_array($url)){
			$data = array();
			foreach ($url as $key => $value) {
				if(!empty($value['url'])){
					$tmp = array();
					$_url = $value['url'];
					$html = file_get_html($_url);
					$name = $html->find('.weizhi h1',0)->plaintext;
					// 分辨率
					$px = '';
					/*$pxArr = $html->find('.c_l p',3)->plaintext;
					if(!empty($pxArr)){
						$pxArr = explode('：',$pxArr);
						if(!empty($pxArr[1])){
							$px = $pxArr[1];
						}
					}*/
					// 模特
					$model_name = '';
					/*$modelArr = $html->find('.c_l p',4)->plaintext;
					if(!empty($modelArr)){
						$modelArr = explode('：',$modelArr);
						if(!empty($modelArr[1])){
							$model_name = $modelArr[1];
						}
					}*/
					// 来源
					$from = '';
					$fromStr = $html->find('.tags',0)->plaintext;
					if(!empty($fromStr)){
						$from = $fromStr;
					}
					$abstract = $keyword = $desc = $content = $m_content = '';
					// 标签（关键词，描述？）
					$tags = $html->find('.fenxiang_l',0)->plaintext;
					if(!empty($tags)){
						$tags = explode('：',$tags);
						if(!empty($tags[1])){
							$abstract = $keyword = $desc = $content = $m_content = $tags[1];
						}
					}
					$_content = $html->find('.content',0);
					// 文章主图
					$img = '';
					$hasImg = false;
					// 替换图片
					$imgArr = array();
					foreach ($_content->find('img') as $key => $element) {
						$new_img_src = $this->SaveImg($element->src);
						$imgArr[] = $new_img_src;
						if($new_img_src){
							if(!$hasImg){
								$img = $new_img_src;
								$hasImg = true;
							}
							sleep(1);
						}
					}
					//分页
					$pages = $html->find('#pages',0);
					$page = 0;
					if($pages){
						foreach ($pages->find('a') as $key => $element) {
							if(is_numeric($element->plaintext)){
								$page = $element->plaintext;
							}
						}
					}
					if($page > 0){
						for ($i=2; $i <=$page; $i++) { 
							$pageUrl = str_replace('.html',"_{$i}.html",$_url);
							if($pageUrl){
								$_page_html = file_get_html($pageUrl);
								$_page_content = $_page_html->find('.content',0);
								foreach ($_page_content->find('img') as $_key => $element) {
									$imgArr[] = $this->SaveImg($element->src);
									sleep(1);
								}
							}
						}
					}
					if($name){
						$tmp['name'] = $name;
						$tmp['abstract'] = $abstract;
						$tmp['keyword'] = $keyword;
						$tmp['img'] = $img;
						$tmp['desc'] = $desc;
						$tmp['content'] = $content;
						$tmp['m_content'] = $m_content;
						$tmp['px'] = $px;
						$tmp['from'] = $from;
						$tmp['model_name'] = $model_name;
						$tmp['status'] = 0;
						$tmp['collect_url'] = $_url;
						$tmp['add_time'] = time();
						$tmp['update_time'] = time();
						$res = $this->insert($this->_pic_db,$tmp);
						if($res){
							if($imgArr){
								$addAllImg = array();
								foreach ($imgArr as $_key => $_value) {
									$addAllImg[] = array('pic_id'=>$res,'url'=>$_value);
								}
								$this->insertAll($this->_pic_img_db,$addAllImg);
							}
							$now_time = time();
							$up_sql = "UPDATE {$this->_db} SET `status` = 1, `collect_time` = {$now_time} WHERE id = ? ";
							$up_rs = $this->_pdo->prepare($up_sql);
							$up_rs->bindValue(1, $value['id'], PDO::PARAM_INT);
							$upup = $up_rs->execute();
						}
					}
				}
				sleep(3);
			}
		}
	}


}
