<?php
include('db.php');



if(trim($_POST['search_key'])!=''){
  $searchkey = $_POST['search_key'];

 $address = urlencode($searchkey);
 $url = "https://maps.google.com/maps/api/geocode/json?key=YOUR-API-KEY&address=".urlencode($address);
$ch = curl_init();
        	curl_setopt($ch, CURLOPT_URL, $url);
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);   
        	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        	$result = curl_exec($ch);
        	$map_result = json_decode($result,true);
        	if($map_result['status']=="OK"){
        		$user_id=$_COOKIE['user_id'];
        		$address_info =$map_result['results'][0];
                $location_info = $address_info['address_components'];
               /* echo "<pre>";
                print_r($location_info);exit;*/

        		/*$city_info = array_shift($address_info['address_components']);
        		$city = $city_info['long_name'];
        		$country_info = end($address_info['address_components']);
        		$country = $country_info['long_name'];*/
                $city=$country='';
                foreach ($location_info as $key => $value) {
                    if($value['types'][0]=="locality"){
                        $city = $value['long_name'];
                    }
                    if($value['types'][0]=="country"){
                        $country = $value['long_name'];
                    }
                }
        		$lat_long_info = $address_info['geometry']['location'];
        		$lat = $lat_long_info['lat'];
        		$long = $lat_long_info['lng'];
        		$result_address = $address_info['formatted_address'];
        		$sql = "insert into user_places (lat,longitude,city,country,user_id,address) values ('".$lat."','".$long."','".$city."','".$country."','".$user_id."','".$result_address."')";
                $query = mysqli_query($conn,$sql);
        		//echo $sql;exit;
               $city= $city !=0 ?$city[0]:"";
              
				$response = array("status"=>1,"response"=>"User Place Added Successfully","result"=>array("lat"=>$lat,"lng"=>$long,"city"=>$city));
        	}else{
        		$response = array("status"=>0,"response"=>"Address Not found , Please try with another one","result"=>"");
        	}
         echo    $map_result =json_encode($response,true);
 
  exit;
}


 
 ?>