<?php
require_once('./common.php');

# Generate Unique ID
$token=str_replace('.','',uniqid('', true));

# Get Web form datas
$coordinates_lat = mysqli_real_escape_string($db,$_POST['coordinates_lat']);
$coordinates_lon = mysqli_real_escape_string($db,$_POST['coordinates_lon']);
$comment = mysqli_real_escape_string($db,$_POST['comment']);
$categorie = mysqli_real_escape_string($db,$_POST['categorie']);

# Init Datas
$status = 0;
$json = array('token' => $token, 'status' => 0);

# Insert user datas to MySQL Database
error_log(!empty($coordinates_lat).'-'.!empty($coordinates_lon) .'-'.!empty($comment).'-'.!empty($categorie));
if(!empty($coordinates_lat) and !empty($coordinates_lon) and !empty($comment) and !empty($categorie)) {

  mysqli_query($db,'INSERT INTO obs_list (`obs_coordinates_lat`,`obs_coordinates_lon`,`obs_comment`,`obs_categorie`,`obs_token`,`obs_time`,`obs_status`) VALUES
				  ("'.$coordinates_lat.'","'.$coordinates_lon.'","'.$comment.'","'.$categorie.'","'.$token.'","'.time().'",0)') ;

  if($mysqlerror = mysqli_error($db)) {
    $status = 1;
    error_log('CREATE_ISSUE : MySQL Error '.$mysqlerror);
  }
}
else {
  $status = 1;
  error_log('CREATE_ISSUE : Field not supported');
}

# If error force return 500 ERROR CODE
if($status != 0) {
  http_response_code(500);
}

# Return Token value
$json['status'] = $status;
echo json_encode($json);
?>
