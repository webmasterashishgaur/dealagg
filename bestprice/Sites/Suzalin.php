<?php
class Suzalin extends Parsing{
	public $_code = 'Suzalin';
	public function getFacebookUrl(){
		return 'http://www.facebook.com/SuzalinOnlineShopping';
	}
	public function getAllowedCategory(){
		return array(Category::TABLETS,Category::MOBILE,Category::CAMERA,Category::COMP_LAPTOP);
	}

	public function getWebsiteUrl(){
		return 'http://www.suzalin.com/';
	}
	public function getSearchURL($query,$category = false,$subcat=false){
		$query = urldecode($query);
		$query = rawurlencode($query);
		if($category == Category::MOBILE){
			return "http://www.suzalin.com/Search/1_2_$query";
		}else if($category == Category::CAMERA){
			return "http://www.suzalin.com/Search/1_7_$query";
		}elseif($category == Category::COMP_LAPTOP){
			return "http://www.suzalin.com/Search/1_3_$query";
		}elseif ($category == Category::TABLETS){
			return "http://www.suzalin.com/Search/1_4_$query";
		}elseif($category == Category::GAMING){
			return "http://www.suzalin.com/Search/1_1_$query";
		}
		return "http://www.suzalin.com/Search/1_0_$query";
	}
	public function getLogo(){
		return 'http://www.suzalin.com/images/suzalin-logo.gif';
	}
	public function getData($html,$query,$category,$subcat){

		$data = array();
		phpQuery::newDocumentHTML($html);
		$count = 0;
		if(sizeof(pq('ul.productData')) > 0){
			foreach(pq('ul.productData') as $div){
				foreach(pq($div)->find('li') as $div){
					$image = pq($div)->children('.productWidget')->children('.productWidgetImage')->children('a')->html();
					$url = pq($div)->children('.productWidget')->children('.productWidgetImage')->children('a')->attr('href');
					$name = pq($div)->children('.productWidget')->children('.productWidgetTitle')->children('a')->html();
					$disc_price = pq($div)->children('.productWidget')->children('.productWidgetPriceContainer')->children('.ourPrice')->html();
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
					if(sizeof($data) > 5){
						break;
					}
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
		$price = pq('.prod_summary:first')->find('.offer_price:first')->html();
		$offer = pq('.shipping-section:first')->children('.block-body')->find('.addloffer')->text();
		if(sizeof(pq('.block-body')->children('.buy-btn-sec')) > 0){
			$stock = 1;
		}else{
			$stock = -1;
		}
		$shipping_cost = pq('.shipping-section:first')->find('.block-headertext:first span')->text();
		$shipping_time = '';
		$warrenty = '';

		$found = false;
		/*foreach(pq('.prod_summary:first')->children('li') as $li){
			if($found){
				$warrenty = pq($li)->html();
				break;
			}
			if(strpos(strtolower(pq($li)->html()),'warranty') !== false){
				$warrenty = 123;
			}
			
		}
		*/
		$found = false;
	    foreach(pq('.prod_summary')->find('li') as $li){
			if($found){
						$warrenty = pq($li)->html();
						
					}
			if(strpos(pq($li)->attr('class'),'colon')!== false){
				$found=true;
				
			}
			
		}
		$author = '';
		$attr = array();
		$cat = '';
		foreach(pq('.sc-breadbcrumb')->find('a') as $li){
			$cat .= pq($li)->html().',';
		}
		$data = array(
				'price' => $price,
				'offer' => $offer,
				'stock' => $stock,
				'shipping_cost' => $shipping_cost,
				'shipping_time' => $shipping_time,
				'attr' => $attr,
				'author' => $author,
				'cat' => $cat,
				'warrenty' => $warrenty
		);

		$data = $this->cleanProductData($data);
		return $data;
	}
}