<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require dirname(__FILE__) . '/../include/DbHandler.php';


$output_json = array();
$db = new DbHandler();

$eventID = $_POST['eventID'];


$output_json = $db->getCalendarData($eventID);

if($output_json != NULL)
{
   $event_array =$output_json;
}
else
{
    echo $output_json;
}

// Send JSON to the client.
echo json_encode($event_array);