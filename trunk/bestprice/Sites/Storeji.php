<?php
class Storeji extends Parsing{
	public $_code = 'Storeji';
	public function getFacebookUrl(){
		return 'http://www.facebook.com/storejifan';
	}
	public function getAllowedCategory(){
		return array(Category::COMP_LAPTOP,Category::HOME_APPLIANCE,Category::TV,Category::MOBILE,Category::TABLETS,Category::MOBILE_ACC);
	}

	public function getWebsiteUrl(){
		return 'http://www.storeji.com/';
	}
	public function getSearchURL($query,$category = false,$subcat){
		if($category == Category::MOBILE || $category == Category::MOBILE_ACC){
			return "http://www.storeji.com/catalogsearch/result/index/?cat=19&q=$query";
		}elseif ($category == Category::COMP_LAPTOP){
			return "http://www.storeji.com/catalogsearch/result/index/?cat=142&q=$query";
		}elseif($category == Category::TABLETS){
			return "http://www.storeji.com/catalogsearch/result/index/?cat=141&q=$query";
		}
		return "http://www.storeji.com/catalogsearch/result/?q=$query";
	}
	public function getLogo(){
		return 'http://www.storeji.com/skin/frontend/default/ma_camerastore/images/logo.png';
	}
	public function getData($html,$query,$category,$subcat=false){

		$data = array();
		phpQuery::newDocumentHTML($html);
		if(sizeof(pq('.products-grid')) > 0){
			foreach(pq('.products-grid') as $div){
				foreach(pq($div)->children('li') as $div){
					$image = pq($div)->children('a:first')->html();
					$url = pq($div)->children('a:first')->attr('href');
					$name = pq($div)->children('.product-descirption')->children('.product-name')->children('a')->html();
					if(sizeof(pq($div)->children('.product-descirption')->find('.price-box')->children('.special-price'))){
						$disc_price = pq($div)->children('.product-descirption')->find('.price-box')->children('.special-price')->children('.price')->html();
					}else{
						$disc_price = pq($div)->children('.product-descirption')->find('.price-box')->children('.regular-price')->children('.price')->html();
					}
					$offer = '';
					$shipping = '';
					$stock = 0;
					$author = '';
					$data[] = array(
							'name'=>$name,
							'image'=>$image,
							'disc_price'=>$disc_price,
							'url'=>$url,
							'website'=>$this->getCode(),
							'offer'=>$offer,
							'shipping'=>$shipping,
							'stock'=>$stock,
							'author' => $author,
							'cat' => ''
					);
				}
			}
		}
		$data2 = array();
		foreach($data as $row){
			$html = $row['image'];
			$html .= '</img>';
			phpQuery::newDocumentHTML($html);
			$img = pq('img')->attr('src');
			$row['image'] = $img;
			$data2[] = $row;
		}
		$data2 = $this->cleanData($data2, $query);
		$data2 = $this->bestMatchData($data2, $query,$category,$subcat);
		return $data2;
	}
	public function hasProductdata(){
		return true;
	}
	public function getProductData($html,$price,$stock){
		phpQuery::newDocumentHTML($html);
		foreach(pq('.price-box')->find('span') as $li){
			if(pq($li)->attr('class')=='price'){
					
				$price=pq($li)->html();
			}
		}

		$stock = pq('.product-shop:first')->children('.availability:first')->find('span')->html();
		if($stock == 'In stock'){
			$stock = 1;
		}else{
			$stock = -1;
		}

		$shipping_cost = 'Free Shipping ';

		$shipping_time ='';

		$attr = array();
		$cat = '';
		foreach(pq('.breadcrumbs')->children('ul:first')->children('li') as $li){
			$cat .= pq($li)->children('a')->html().',';
		}

		$warrenty = '';

		$offer ='';
		$data = array(
				'price' => $price,
				'offer' => $offer,
				'stock' => $stock,
				'shipping_cost' => $shipping_cost,
				'shipping_time' => $shipping_time,
				'attr' => $attr,
				'author' => '',
				'cat' => $cat,
				'warrenty' => ''
		);

		$data = $this->cleanProductData($data);
		return $data;
	}
}