<html>
<head>
  <title>Task1</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.6/dist/loadingoverlay.min.js"></script>
  <script src="http://maps.google.com/maps/api/js?key=YOURAPIKEY&libraries=places" type="text/javascript"></script>
  <style type="text/css">
    .mapsearch{
      position: fixed;
      z-index: 11;
      margin: 100px 0px 0px 20px;
    }
    .card-columns{
      position: fixed;
      z-index: 11;
      margin-top: 50px;
      margin-left: 60%;
      opacity: 5;
    }
    </style>
</head>
<body>
  <div class="container" id="task">
    <?php
    include('db.php'); 
if(!isset($_COOKIE['user_id'])){
$cookie_name = "user_id";
$cookie_value = date('ymdHis');
setcookie($cookie_name, $cookie_value); 
$user_id = $cookie_value;
}else{
  $user_id =$_COOKIE['user_id'];
}
$user_query = "SELECT country,lat,longitude,count('city') as city FROM `user_places` WHERE user_id='".$user_id."'  GROUP BY country order by id desc";
//$user_query ="select * from user_places where user_id ='".$user_id."' and city !='' ";
$places = mysqli_query($conn,$user_query);
$place_counts = mysqli_num_rows($places);
$user_citys = array();
$user_countries = array();
$user_markers =array();
if($place_counts){
  while ($row=mysqli_fetch_array($places)) {
   $user_citys[] = $row['city'];
   $user_places['country']=$row['country'];
   $user_places['lat']=$row['lat'];
   $user_places['longitude']=$row['longitude'];
   $user_places['city']=$row['city'];
   $user_markers[] = array_values($user_places);
  }
}
/*echo "<pre>";
print_r($user_markers);
exit;*/
$map_markers =  json_encode($user_markers,JSON_PRETTY_PRINT);


?>
    <div class="row mapsearch" id="map_search">
      <input type="text" name="search_place"  class="form-control  col-md-4" id="search_place" size="50">
      <div style="margin-left: 10px;" class="col-ms-2"><button class="btn btn-primary add_place">Add Location</button></div>
  </div>
      <div class="card-columns">
        <div class="card bg-light">
          <div class="card-body text-center">
            <img src="download.png" class="rounded-circle" alt="Cinque Terre">
            <p class="card-text"><div class="text-danger">Siva Manikanta<br><small class="text-muted">( 9491259896 )</small></div></p>
            <table class="table" id="user_info">
              <tr><th>User Id</th><td><?= $user_id; ?></td></tr>
              <tr><th>countries:</th><td><?= $place_counts ?></td></tr>
              <tr><th>Cities:</th><td><?= array_sum($user_citys) ?></td></tr>
            </table>
          </div>
        </div>
      </div>
    <br><br>
  <div id="map" style="height: 100%; width: 100%;">
</div>
</div>

<script type="text/javascript">
       function initialize(){
                var input = document.getElementById('search_place');
                new google.maps.places.Autocomplete(input);
            }
            google.maps.event.addDomListener(window, 'load', initialize);
            var locations =<?= $map_markers; ?>;
            var map = new google.maps.Map(document.getElementById('map'), {
            zoom:2,
            center: new google.maps.LatLng(20.5937, 78.9629),
            mapTypeId: google.maps.MapTypeId.ROADMAP
            });
           var infowindow = new google.maps.InfoWindow();
           var marker, i;
           for (i = 0; i < locations.length; i++) { 
                marker = new google.maps.Marker({
                  position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                  icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+locations[i][3]+'|FE6256|000000',
                  map: map
                });
              google.maps.event.addListener(marker, 'click', (function(marker, i) {

                return function() {
                	//alert('hi');
                  infowindow.setContent(locations[i][0]);
                  infowindow.open(map, marker);
                }
            })(marker, i));
         }
  </script>
  <script type="text/javascript">
  $(document).ready(function(){

    $(".add_place").click(function(){
      var search_place = $.trim($("#search_place").val());
      if(search_place==''){
        alert('Please Enter Place');
        $("#search_place").focus();
      }else{
         $.LoadingOverlay("show");
          $.ajax({
            type: "POST",
            url: "server.php",
            data: {'search_key':search_place},
            dataType: "JSON",
            success: function (data) { 
             $.LoadingOverlay("hide");
             alert(data.response);
             if(data.status==1){	
            var markers = new Array();
			var myLatlng = new google.maps.LatLng(data.lat,data.long);
			var marker = new google.maps.Marker({
			position: myLatlng,
			icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+data.count+'|FE6256|000000',
			map: map,
			title:data.country,

			});
    		markers.push(marker);
    		//$("#user_info").load(location.href + " #map");
             $("#user_info").load(location.href + " #user_info");
             $('#search_place').val('');

             }
             
            },
            error : function (data){
               $.LoadingOverlay("hide"); 
            alert(data.response);                
            }            
          }); 
      }
    })
    return false ;
  })
</script>
</body>
</html>