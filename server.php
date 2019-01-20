<?php
include('db.php');



if(trim($_POST['search_key'])!=''){
  $searchkey = $_POST['search_key'];

 $address = urlencode($searchkey);
 $url = "https://maps.google.com/maps/api/geocode/json?key=YOURAPIKEY&address=".urlencode($address);
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
                $user_query = "SELECT country,lat,longitude,count('city') as city FROM `user_places` WHERE user_id='".$user_id."' and country ='".$country."' GROUP BY country";
                $new_place = mysqli_query($conn,$user_query);
                $new_place_info = mysqli_fetch_array($new_place);
              //  print_r($new_place_info);exit;
				$response = array("status"=>1,"response"=>"User Place Added Successfully","lat"=>$new_place_info['lat'],"long"=>$new_place_info['longitude'],"country"=>$new_place_info['country'],"count"=>$new_place_info['city']);
        	}else{
        		$response = array("status"=>0,"response"=>"Address Not found , Please try with another one","lat"=>"","long"=>"","country"=>"","count"=>"");
        	}
         echo    $map_result =json_encode($response,true);
 
  exit;
}


 
 ?>