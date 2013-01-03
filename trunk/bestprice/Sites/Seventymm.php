<?php
class Seventymm extends Parsing{
	public $_code = 'Seventymm';
	public function getFacebookUrl(){
		return 'http://www.facebook.com/SeventymmCom';
	}
	public function getAllowedCategory(){
		return array(Category::MOBILE,Category::MOBILE_ACC);
	}

	public function getWebsiteUrl(){
		return 'http://shop.seventymm.com/';
	}
	public function getSearchURL($query,$category = false,$subcat=false){
		$q = urldecode($query);
		$q = str_replace(" ", '-', $q);
		$q = trim(preg_replace("![^0-9a-z-]+!i", "", $q));
		if($category == Category::MOBILE){
			return "http://shop.seventymm.com/Search/$q/Tablets-and-Mobiles/2829/Mobiles/2369/All-Price/0/Any/0/Any/0/1/1/3/Go";
		}else if($category == Category::MOBILE_ACC){
			return "http://shop.seventymm.com/Search/$query/Tablets-and-Mobiles/2829/All-Classification/0/All-Price/0/Any/0/Any/0/1/1/3/Go";
		}else if($category == Category::CAMERA){
			if($subcat == Category::NOT_SURE){
				return "http://shop.seventymm.com/Search/$query/Electronics/2235/Point-and-Shoot/2375/All-Price/0/Any/0/Any/0/1/1/3/Go"; //digital camera
			}else if($subcat == Category::CAM_DIGITAL_CAMERA){
				return "http://shop.seventymm.com/Search/$query/Electronics/2235/Point-and-Shoot/2375/All-Price/0/Any/0/Any/0/1/1/3/Go"; //digital camera
			}else if($subcat == Category::CAM_DIGITAL_SLR){
				return "http://shop.seventymm.com/Search/$query/Electronics/2235/D-SLR/2377/All-Price/0/Any/0/Any/0/1/1/3/Go"; // dslr
			}else if($subcat == Category::CAM_CAMCORDER){
				return "http://shop.seventymm.com/Search/$query/Electronics/2235/Camcorders/2378/All-Price/0/Any/0/Any/0/1/1/3/Go"; //camcorders
			}else if($subcat == Category::CAM_MIRRORLESS){
				return "http://shop.seventymm.com/Search/$query/Electronics/2235/Point-and-Shoot/2375/All-Price/0/Any/0/Any/0/1/1/3/Go"; //digital camera
			}else{
				return '';
			}
		}else if($category == Category::CAMERA_ACC){
			if($subcat == Category::NOT_SURE){
				return "http://shop.seventymm.com/Search/$query/Electronics/2235/Accessories/2629/All-Price/0/Any/0/Any/0/1/1/3/Go"; //acc
			}else if($subcat == Category::CAM_ACC_ADAPTER_CHARGES){
				return "http://shop.seventymm.com/Search/$query/Electronics/2235/Accessories/2629/All-Price/0/Any/0/Any/0/1/1/3/Go"; //acc
			}else if($subcat == Category::CAM_ACC_BAGS){
				return "http://shop.seventymm.com/Search/$query/Electronics/2235/Accessories/2629/All-Price/0/Any/0/Any/0/1/1/3/Go"; //acc
			}else if($subcat == Category::CAM_ACC_BATTERY){
				return "http://shop.seventymm.com/Search/$query/Electronics/2235/Accessories/2629/All-Price/0/Any/0/Any/0/1/1/3/Go"; //acc
			}else if($subcat == Category::CAM_ACC_FLASH_LIGHTS){
				return "http://shop.seventymm.com/Search/$query/Electronics/2235/Accessories/2629/All-Price/0/Any/0/Any/0/1/1/3/Go"; //acc
			}else if($subcat == Category::CAM_ACC_LENSEFILTER){
				return "http://shop.seventymm.com/Search/$query/Electronics/2235/Lenses/2619/All-Price/0/Any/0/Any/0/1/1/3/Go"; //lenses
			}else if($subcat == Category::CAM_ACC_LENSES){
				return "http://shop.seventymm.com/Search/$query/Electronics/2235/Lenses/2619/All-Price/0/Any/0/Any/0/1/1/3/Go"; //lenses
			}else if($subcat == Category::CAM_ACC_MEMORY_AND_STORAGE){
				return "http://shop.seventymm.com/Search/$query/Electronics/2235/Accessories/2629/All-Price/0/Any/0/Any/0/1/1/3/Go"; //acc
			}else if($subcat == Category::CAM_ACC_OTHER_ACC){
				return "http://shop.seventymm.com/Search/$query/Electronics/2235/Accessories/2629/All-Price/0/Any/0/Any/0/1/1/3/Go"; //acc
			}else if($subcat == Category::CAM_ACC_SCREEN_PROTECTOR){
				return "http://shop.seventymm.com/Search/$query/Electronics/2235/Accessories/2629/All-Price/0/Any/0/Any/0/1/1/3/Go"; //acc
			}else if($subcat == Category::CAM_ACC_TRIPODS){
				return "http://shop.seventymm.com/Search/$query/Electronics/2235/Accessories/2629/All-Price/0/Any/0/Any/0/1/1/3/Go"; //acc
			}else{
				return '';
			}

		}
		return "http://shop.seventymm.com/Search/$q/All-Categories/0/All-Classification/0/All-Price/0/Any/0/Any/0/1/1/3/Go";
	}
	public function getLogo(){
		return 'http://staticcontent.seventymm.com/Images/SiteMasterV4/LogoC.png';
	}
	public function getData($html,$query,$category,$subcat){

		$data = array();
		phpQuery::newDocumentHTML($html);
		if(sizeof(pq('.Search_Panel')) > 0){
			//http://images.seventymm.com/Img/Item/ItemRegular/43262.jpg
			foreach(pq('.Search_Panel')->children('.MT10')->children('.MT20')->children('div') as $div){
				$image = pq($div)->children('img')->attr('do');
				$url = pq($div)->children('a')->attr('href');
				$name = pq($div)->children('a')->html();
				$disc_price = pq($div)->attr('sp');
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
		$data = $this->cleanData($data, $query);
		$data = $this->bestMatchData($data, $query,$category,$subcat);
		return $data;
	}
}