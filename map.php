<!DOCTYPE html>
<html>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.6/dist/loadingoverlay.min.js"></script>
   <script src="http://maps.google.com/maps/api/js?key=YOUR-API-KEY&libraries=places" type="text/javascript"></script>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Task 1</title>
    <style type="text/css">
         #map {
        height: 100%;
      }
        .mapsearch{
      position: fixed;
      z-index: 11;
      margin: 100px 0px 0px 20px;
    }
    .card-columns{
      position: fixed;
      z-index: 11;
      margin-top: 50px;
      margin-left: 80%;
      opacity: 5;
    }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
       
      }
    </style>
  </head>

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
      $user_query ="select * from user_places where user_id ='".$user_id."' and city !='' ";
      $places = mysqli_query($conn,$user_query);
      $place_counts = mysqli_num_rows($places);
      $user_markers =$city=$country=array();

      if($place_counts){

        while ($row=mysqli_fetch_array($places)) {
          $city[$row['city']] = $row['city'];
           $country[$row['country']] = $row['country'];
         $user_markers[] = array("lat"=>$row['lat'],"lng"=>$row['longitude'],"loc"=>$row['city'][0]);//array_values($user_places);
        }
      }
      $map_markers =  json_encode($user_markers,JSON_NUMERIC_CHECK);
   // echo $map_markers;exit;
     ?>
  <body>
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
              <tr><th>User Id</th><td><?= $user_id ?></td></tr>
              <tr><th>countries:</th><td><?= count($country) ?></td></tr>
              <tr><th>Cities:</th><td><?= count($city) ?></td></tr>
            </table>
          </div>
        </div>
      </div>
    <div id="map"></div>
    
    <script type="text/javascript">
            function initialize(){
                var input = document.getElementById('search_place');
                new google.maps.places.Autocomplete(input);
            }
            google.maps.event.addDomListener(window, 'load', initialize);
      function initMap() {

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 2,
          center: {lat: 20.5937, lng: 78.9629}
        });

        var markers = locations.map(function(location, i) {
          return new google.maps.Marker({
            position: location,
            label: locations[i]["loc"]//labels[i % labels.length]
          });
        });
        var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
      }
      var locations = <?= $map_markers; ?>;
 
    </script>
    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
        initMap()
    $(".add_place").click(function(evt){
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
           
            location.reload();
           /*  locations.push(data.result);
               $("#map").load("#map")
                 evt.preventDefault();
             console.log(data.result);*/
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