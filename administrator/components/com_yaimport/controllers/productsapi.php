<?php
//defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');
class YaimportControllerProductsApi extends JControllerLegacy{
	
	private static $api_key = '';
	private static $api_url = '';
	private static $api_version = 'v1';
	private static $format = 'json';
	private static $opinion_username = 'Отзыв с Yandex маркета';
	
	private static $url_model = '#api_url#/#api_version#/model/#pid#?format=#format#&from=joomla&api_key=#api_key#';
	private static $url_detail = '#api_url#/#api_version#/model/#pid#/details?format=#format#?from=joomla&api_key=#api_key#';
	private static $url_opinion = '#api_url#/#api_version#/model/#pid#/opinion?format=#format#&count=30&page=1&from=joomla&api_key=#api_key#';
	
	function __construct($config = array()){
        header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
        header("Pragma: no-cache"); //HTTP 1.0
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
		eval(base64_decode("ICAgICAgICBwYXJlbnQ6Ol9fY29uc3RydWN0KCRjb25maWcpOwogICAgICAgIGNoZWNrQWNjZXNzQ29udHJvbGxlcigicHJvZHVjdHMiKTsKICAgICAgICBhZGRTdWJtZW51KCJwcm9kdWN0cyIpOwoJCQoJCXJlcXVpcmVfb25jZSgkX1NFUlZFUlsnRE9DVU1FTlRfUk9PVCddIC4gJy9hZG1pbmlzdHJhdG9yL2NvbXBvbmVudHMvY29tX3lhaW1wb3J0L2xhbmcvcnUtUlUucGhwJyk7CgkJCgkJJHNldHRpbmdzX3BhdGggPSAkX1NFUlZFUlsnRE9DVU1FTlRfUk9PVCddIC4gJy9hZG1pbmlzdHJhdG9yL2NvbXBvbmVudHMvY29tX3lhaW1wb3J0L3NldHRpbmdzLmpzb24nOwoJCQoJCWlmKCFmaWxlX2V4aXN0cygkc2V0dGluZ3NfcGF0aCkpewoJCQkkcmVzcG9uc2UgPSBhcnJheSgic3VjY2VzcyIgPT4gZmFsc2UsICJyZXN1bHQiID0+IF9ZQUlNUE9SVF9TRVRUKTsKCQkJJGRvY3VtZW50ID0gSkZhY3Rvcnk6OmdldERvY3VtZW50KCk7CgkJCSRkb2N1bWVudC0+c2V0TWltZUVuY29kaW5nKCdhcHBsaWNhdGlvbi9qc29uJyk7CgkJCUpSZXNwb25zZTo6c2V0SGVhZGVyKCdDb250ZW50LURpc3Bvc2l0aW9uJywnYXR0YWNobWVudDtmaWxlbmFtZT0icmVzdWx0Lmpzb24iJyk7CgkJCQoJCQlleGl0KGpzb25fZW5jb2RlKCRyZXNwb25zZSkpOwoJCX0KCQkKCQkkc2V0dGluZ3MgPSBqc29uX2RlY29kZShmaWxlX2dldF9jb250ZW50cygkc2V0dGluZ3NfcGF0aCkpOwoJCQoJCWlmKG1kNSgkX1NFUlZFUlsnU0VSVkVSX05BTUUnXS4nbWFnbml0Jy4kX1NFUlZFUlsnU0VSVkVSX0FERFInXSkgIT0gJHNldHRpbmdzLT5jb21fa2V5KXsKCQkJJHJlc3BvbnNlID0gYXJyYXkoInN1Y2Nlc3MiID0+IGZhbHNlLCAicmVzdWx0IiA9PiBfWUFJTVBPUlRfTElTKTsKCQkJJGRvY3VtZW50ID0gSkZhY3Rvcnk6OmdldERvY3VtZW50KCk7CgkJCSRkb2N1bWVudC0+c2V0TWltZUVuY29kaW5nKCdhcHBsaWNhdGlvbi9qc29uJyk7CgkJCUpSZXNwb25zZTo6c2V0SGVhZGVyKCdDb250ZW50LURpc3Bvc2l0aW9uJywnYXR0YWNobWVudDtmaWxlbmFtZT0icmVzdWx0Lmpzb24iJyk7CgkJCQoJCQlleGl0KGpzb25fZW5jb2RlKCRyZXNwb25zZSkpOwoJCX0KCQkKCQlzZWxmOjokYXBpX3VybCA9ICRzZXR0aW5ncy0+YXBpX3VybDsKCQlzZWxmOjokYXBpX2tleSA9ICRzZXR0aW5ncy0+YXBpX2tleTsKCQkKCQlzZWxmOjokdXJsX2RldGFpbCA9IHN0cl9yZXBsYWNlKAoJCQlbJyNhcGlfdXJsIycsJyNhcGlfdmVyc2lvbiMnLCcjZm9ybWF0IycsJyNhcGlfa2V5IyddLAoJCQlbc2VsZjo6JGFwaV91cmwsIHNlbGY6OiRhcGlfdmVyc2lvbiwgc2VsZjo6JGZvcm1hdCwgc2VsZjo6JGFwaV9rZXldLAoJCQlzZWxmOjokdXJsX2RldGFpbAoJCSk7CgkJc2VsZjo6JHVybF9vcGluaW9uID0gc3RyX3JlcGxhY2UoCgkJCVsnI2FwaV91cmwjJywnI2FwaV92ZXJzaW9uIycsJyNmb3JtYXQjJywnI2FwaV9rZXkjJ10sCgkJCVtzZWxmOjokYXBpX3VybCwgc2VsZjo6JGFwaV92ZXJzaW9uLCBzZWxmOjokZm9ybWF0LCBzZWxmOjokYXBpX2tleV0sCgkJCXNlbGY6OiR1cmxfb3BpbmlvbgoJCSk7CgkJc2VsZjo6JHVybF9tb2RlbCA9IHN0cl9yZXBsYWNlKAoJCQlbJyNhcGlfdXJsIycsJyNhcGlfdmVyc2lvbiMnLCcjZm9ybWF0IycsJyNhcGlfa2V5IyddLAoJCQlbc2VsZjo6JGFwaV91cmwsIHNlbGY6OiRhcGlfdmVyc2lvbiwgc2VsZjo6JGZvcm1hdCwgc2VsZjo6JGFwaV9rZXldLAoJCQlzZWxmOjokdXJsX21vZGVsCgkJKTs="));
    }
    
    function display($cachable = false, $urlparams = false)
	{    
        $mainframe = JFactory::getApplication();    
	
		//self::getApiContentByProductIds([19009]);
	}
	
	public function getDescriptionFromApi()
	{
		if(!isset($_GET['product_id'])) exit('Ошибка получения описания.');
		
		$product_id = $_GET['product_id'];
		
		if(is_array($product_id)) {
			$return = self::getApiContentByProductIds($product_id);
		}else {
			$return = self::getApiContentByProductIds([intval($product_id)]);
		}
		
		$response = array("success" => $return);

		$document = JFactory::getDocument();
		$document->setMimeEncoding('application/json');
		JResponse::setHeader('Content-Disposition','attachment;filename="result.json"');
//debug YB 15.02.2018++
		file_put_contents("/var/www/trilinenew/yb.json", $response);
//debug YB 15.02.2018--

		exit(json_encode($response));
	}
	
	public function getApiContentByProductIds($product_ids, $need_model = true, $need_detail = true, $need_opinion = false)
	{
		$db = JFactory::getDBO();
		
		$query = "SELECT * FROM `#__yaimport_relates` WHERE product_id IN (".implode(',',$product_ids).")";
		$db->setQuery($query);
        
		$products = $db->loadObjectList();
		
		if($products) {
			foreach($products as $product) {
				if($need_model && $need_detail){
					//Получаем описание модели
					$url_model = str_replace('#pid#',$product->yandex_id,self::$url_model);
					$model = self::getContentByUrl($url_model);
//debug YB 15.02.2018++
		file_put_contents("/var/www/trilinenew/yb.json", $model);
//debug YB 15.02.2018--

					//Получаем характеристики модели
					$url_detail = str_replace('#pid#',$product->yandex_id,self::$url_detail);				
					$detail = self::getContentByUrl($url_detail);

					//Пишем в базу описания
					self::updateProductDescription($model, $detail, $product->product_id);
				}
				
				if($need_opinion){
					//Получаем описание модели
					$url_opinion = str_replace('#pid#',$product->yandex_id,self::$url_opinion);				
					$opinion = self::getContentByUrl($url_opinion);
					//Пишем в базу отзывы
					if(!self::updateProductOpinion($opinion, $product->product_id)){
						return false;
					}
				}
			}
			
			return true;
		}
		
		return false;
	}
		
	public function getContentByUrl($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);//json
		
		return json_decode($result);
	}
	
	public function updateProductOpinion($opinions, $product_id)
	{
		if(!is_object($opinions)) return false;
	
		$db = JFactory::getDBO();

		if($opinions->modelOpinions->opinion){
			foreach($opinions->modelOpinions->opinion as $opinion){
				
				if(empty($opinion->id)) return false;
				
				$review = '';

				$date = new DateTime();
				$date->setTimestamp(substr($opinion->date,0,-3));
				$opinion->date =  $date->format('Y-m-d H:i:s');
				
				//Проверяем существование
				$query = "select * from `#__yaimport_opinions` where `product_id` = '".$product_id."' and `opinion_id` = '".$opinion->id."'";
				$db->setQuery($query);
				$existsopinion = $db->loadAssocList();
				
				if(!isset($existsopinion[0])) {
					//Формирую отзыв
					$review = '<b>Достоинства:</b> '.$opinion->pro.'<br>'.'<b>Недостатки:</b> '.$opinion->contra.'<br><b>Комментарий:</b> '.$opinion->text;
					
					//Добавляем к товару
					$query = "insert into `#__jshopping_products_reviews` (`product_id`,`user_id`,`user_name`,`user_email`,`time`,`review`,`mark`,`publish`,`ip`) 
								values (".$product_id.",0,'".self::$opinion_username."','','".$opinion->date."','".addslashes($review)."','". ($opinion->grade+3)*2 ."',1,'0.0.0.0')";

					$db->setQuery($query);
					$db->query();
					
					//Записываем в таблицу, чтоб не повторять
					$query = "insert into `#__yaimport_opinions` (`product_id`,`opinion_id`) values (".$product_id.",".intval($opinion->id).")";
					$db->setQuery($query);
					$db->execute();
				}
			}
			$query = "update `#__yaimport_relates` SET `yandex_opinion_downloaded` = 1, `yandex_opinion_downloaded_at` = '".date('Y-m-d H-i-s')."' WHERE `product_id` = ".$product_id;
			$db->setQuery($query);
			$db->execute();
			
			return true;
		}
		return false;
	}
	
	public function updateProductDescription($model, $detail, $product_id)
	{
		if(!is_object($model)) return false;
		
		$path_image = $_SERVER['DOCUMENT_ROOT'] . '/components/com_jshopping/files/img_products/';
		$db = JFactory::getDBO();
		
		$query = "update `#__yaimport_relates` SET `yandex_name` = '".$model->model->vendor . ' ' . $model->model->name."', `yandex_downloaded` = 1, `yandex_downloaded_at` = '".date('Y-m-d H-i-s')."' WHERE yandex_id = " . intval($model->model->id);
		$db->setQuery($query);
		$db->execute();
		//главная картинка
		if($model->model->mainPhoto->url) {
			$file_name = str_replace('https://mdata.yandex.net/i?path=','',$model->model->mainPhoto->url);
			
			if(copy($model->model->mainPhoto->url, $path_image.$file_name)) {
				
				copy($model->model->previewPhoto->url, $path_image.'thumb_'.$file_name);
				
				$query = "update `#__jshopping_products` SET `image` = '".$file_name."' WHERE product_id = " . $product_id;
				$db->setQuery($query);
				$db->execute();		
			}
		}
		//картинки
		if($model->model->photos->photo) {
			$jshopConfig = JSFactory::getConfig();
			require_once($jshopConfig->path.'lib/image.lib.php');
			
			//Удалем если есть
			$query = "delete from `#__jshopping_products_images` WHERE product_id = " . $product_id;
			$db->setQuery($query);
			$db->execute();
			
			$photo_order = 1;
			
			foreach($model->model->photos->photo as $photo) {
				$file_name = str_replace('https://mdata.yandex.net/i?path=','',$photo->url);
				
				copy($photo->url, $_SERVER['DOCUMENT_ROOT'] . '/components/com_jshopping/files/img_products/'.$file_name);

				ImageLib::resizeImageMagic(
					$path_image.$file_name, 
					$jshopConfig->image_product_width, 
					$jshopConfig->image_product_height, 
					$jshopConfig->image_cut, 
					$jshopConfig->image_fill, 
					$path_image.'thumb_'.$file_name, 
					$jshopConfig->image_quality, 
					$jshopConfig->image_fill_color);			
				//copy($path_image.$file_name, $path_image.'thumb_'.$file_name);
				copy($path_image.$file_name, $path_image.'full_'.$file_name);
				
				$query = "insert into `#__jshopping_products_images` (`product_id`, `image_name`, `name`, `ordering`) values (".$product_id.",'".$file_name."','".$model->model->name."',".$photo_order.")";
				$db->setQuery($query);
				$db->execute();
				
				$photo_order++;
			}
		}
		//Описание
		if($detail->modelDetails) {
			$product_description = '<div>';
			
			foreach($detail->modelDetails as $parent_detail) {
				//h2 $parent_detail->name
				$product_description .= '<h2 class="property-name">' . $parent_detail->name . '</h2>';
				$product_description .= '<table cellpadding="0" cellspacing="0" class="property-list"><tbody>';
				foreach($parent_detail->params as $child_detail) {
					$product_description .= '<tr><th>' . $child_detail->name . '</th><td>' . str_replace($child_detail->name . ':','',$child_detail->value) . '</td></tr>';
				}
				$product_description .= '</tbody></table>';
			}
			
			$product_description .= '</div>';
		}
		//Фильтры
		if($detail->modelDetails) {
			$order = 1;
			
			foreach($detail->modelDetails as $parent_detail) {
				$query = "select * from `#__jshopping_products_extra_field_groups` where `name_ru-RU` = '".addslashes($parent_detail->name)."'";
				$db->setQuery($query);
				$groups = $db->loadAssocList();
				
				if(!isset($groups[0])) {
					$query = "insert into `#__jshopping_products_extra_field_groups` (`ordering`,`name_en-GB`,`name_ru-RU`) values (".$order.",'','".addslashes($parent_detail->name)."')";
					$db->setQuery($query);
					$db->query();
					$group_id = $db->insertid();
				}else {
					$group_id = $groups[0]['id'];
				}

				$order_fields = 1;
				foreach($parent_detail->params as $child_detail) {			
					$query = "select * from `#__jshopping_products_extra_fields` where `name_ru-RU` = '".addslashes($child_detail->name)."' and `group` = ".$group_id;
					$db->setQuery($query);
					$field = $db->loadAssocList();
					
					if(!isset($field[0])) {
						$query = "insert into `#__jshopping_products_extra_fields` (`allcats`,`cats`,`type`,`multilist`,`group`,`ordering`,`name_en-GB`,`description_en-GB`,`name_ru-RU`,`description_ru-RU`) values 
									(1,'a:0:{}',0,0,".$group_id.",".$order_fields.",'','','".addslashes($child_detail->name)."','')";
						$db->setQuery($query);
						$db->query();
						$field_id = $db->insertid();
						
						
						$query = "ALTER TABLE `#__jshopping_products` ADD extra_field_".$field_id." INT NOT NULL";
						$db->setQuery($query);
						$db->query();
					}else {
						$field_id = $field[0]['id'];
					}
					
					$query = "select * from `#__jshopping_products_extra_field_values` where `name_ru-RU` = '".addslashes($child_detail->value)."'";
					$db->setQuery($query);
					$value = $db->loadAssocList();
					
					if(!isset($value[0])) {
						$query = "insert into `#__jshopping_products_extra_field_values` (`field_id`,`ordering`,`name_en-GB`,`name_ru-RU`) values 
									(".$field_id.",1,'','".addslashes($child_detail->value)."')";
						$db->setQuery($query);
						$db->query();
						$value_id = $db->insertid();
					}else {
						$value_id = $value[0]['id'];
					}
					
					$product_values[] = "`extra_field_".$field_id."` = ".$value_id;
					
					$order_fields++;
				}
				
				if(isset($product_values)) {
					$query = "update `#__jshopping_products` set ".implode(' , ',$product_values)." where `product_id` = ".$product_id; 
					$db->setQuery($query);
					$db->query();
				}
			}
			$order++;
		}
		
		$query = "update `#__jshopping_products` SET `description_ru-RU` = '".$product_description."' WHERE product_id = " . $product_id;
		$db->setQuery($query);
		$db->execute();	
	}
	
	public function getApiContentByCategoryId(){
		$url = 'http://market.apisystem.biz/v2/category.json?api_key=0d80d29f01656a793c40ea08bcf10995986ea785';
		$catid = 135661;
		
		$ch = curl_init();
				
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$result = curl_exec($ch);//json
				curl_close($ch);
				
				echo $result;
				exit();
	}
	
	private function parseFullDesc($pid)
    {

        $result = '';

        $url = self::$details_model_template;
        $url = str_replace('#pid#', $pid, $url);
        $ch = curl_init($url);
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        curl_close($ch);
        $items = json_decode($content);

        foreach ($items->modelDetails as $ditem) {
            foreach ($ditem->params as $detail) {
                $result .= '<tr><td class="spec-label">' . $detail->name . '</td><td class="spec-value">' . $detail->value . '</td></tr>';
            }
        }

        $result .= '</table>';

        return $result;
    }
	
	public function getOpinionFromApi()
	{
		if(!isset($_GET['product_id'])) exit('Ошибка получения описания.');
		
		$product_id = $_GET['product_id'];
		
		if(is_array($product_id)) {
			$return = self::getApiContentByProductIds($product_id,false,false,true);
		}else {
			$return = self::getApiContentByProductIds([intval($product_id)],false,false,true);
		}
		
		$response = array("success" => $return);

		$document = JFactory::getDocument();
		$document->setMimeEncoding('application/json');
		JResponse::setHeader('Content-Disposition','attachment;filename="result.json"');

		exit(json_encode($response));
	}
}