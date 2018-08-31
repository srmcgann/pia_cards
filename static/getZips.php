<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $host = 'localhost';
  $user = 'user';
  $pass = 'pia57253';
  $db = 'pia_cards';
  $link = mysqli_connect($host, $user, $pass, $db);

  function utf8ize($d) {
      if (is_array($d)) {
          foreach ($d as $k => $v) {
              $d[$k] = utf8ize($v);
          }
      } else if (is_string ($d)) {
          return utf8_encode($d);
      }
      return $d;
  }
      
  function getDistance($lat1, $lon1, $lat2, $lon2) {
    $R = 3959; // Earth's radius in miles
    $pi = acos(-1);
    $p1 = $lat1 / 180 * $pi;
    $p2 = $lat2 / 180 * $pi;
    $deltap = ($lat2-$lat1) / 180 * $pi;
    $deltaq = ($lon2-$lon1) / 180 * $pi;
    $a = sin($deltap/2) * sin($deltap/2) + cos($p1) * cos($p2) * sin($deltaq/2) * sin($deltaq/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return $R * $c;
  }

  function getBusinesses() {
    global $link;
    if (isset($_POST['zip']) && isset($_POST['page'])) {
      $resultsPerPage = 10;
      $page = $_POST['page'];
      $userZip = mysqli_real_escape_string($link, $_POST['zip']);

      $res = $link->query("SELECT lat, lng FROM zip_lat_long WHERE zip = $userZip");
      if ($res && mysqli_num_rows($res)) {
        $row = mysqli_fetch_assoc($res);
        $user_lat = $row['lat'];
        $user_lng = $row['lng'];
      } else {
        echo json_encode([]);
        die();
      }

      $dists = [];
      $resA = $link->query("SELECT id, zip_code FROM businesses");
      for($i = 0; $i < mysqli_num_rows($resA); $i++) {
        $row = mysqli_fetch_assoc($resA);
        $id = $row['id'];
        $company_zip = $row['zip_code'];
        $resB = $link->query("SELECT lat, lng FROM zip_lat_long WHERE zip = $company_zip");
        $row = mysqli_fetch_assoc($resB);
        $lat = $row['lat'];
        $lng = $row['lng'];
        $dist = getDistance($user_lat, $user_lng, $lat, $lng);
        array_push($dists, [$id, $dist, $lat, $lng]);
      }
      
      function cmp($a, $b) {
         return $a[1] - $b[1];
      }
      usort($dists, "cmp");
      
      $fullResults = [];
      for($i = $resultsPerPage * ($page - 1); $i < $resultsPerPage * $page; $i++) {
        $id = $dists[$i][0];
        $res = $link->query("SELECT * FROM businesses WHERE id = $id");
        $row = mysqli_fetch_assoc($res);
        $row["distance"] = $dists[$i][1];
        $row["lat"] = $dists[$i][2];
        $row["lng"] = $dists[$i][3];
        $row["description"] = str_replace("\n","",$row["description"]);
        array_push($fullResults, $row);
      }
      echo json_encode(utf8ize($fullResults));
    }
  }
  
  function getPrelimData() {
    global $link;
    $res = $link->query('SELECT COUNT(*) FROM businesses');
    $row = mysqli_fetch_assoc($res);
    echo json_encode($row['COUNT(*)']);
  }
  
  $rest_json = file_get_contents("php://input");
  $_POST = json_decode($rest_json, true);

  if (isset($_POST['query'])) {
    switch($_POST['query']) {
      case 'get businesses': getBusinesses(); break;
      case 'get prelimData': getPrelimData(); break;
    }
  }
  
?>