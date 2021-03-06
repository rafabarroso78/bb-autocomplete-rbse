<?php
// contains utility functions mb_stripos_all() and apply_highlight()
//require_once 'local_utils.php';

require __DIR__ . '/vendor/autoload.php'; 

use Google\Cloud\BigQuery\BigQueryClient;

$term = trim($_GET['term']);

$term = preg_replace('/\s+/', ' ', $term);

$a_json_invalid = array(array("id" => "#", "value" => $term, "label" => "Only letters and digits are permitted..."));
$json_invalid = json_encode($a_json_invalid);
// SECURITY HOLE ***************************************************************
// allow space, any unicode letter and digit, underscore and dash
if(preg_match("/[^\040\pL\pN_-]/u", $term)) {
  print $json_invalid;
  exit;
}

//[START build_service]
$bigQuery = new BigQueryClient([
	'projectId' => 'rbse-webserv',
]);

$query = 'SELECT sku, name FROM [rbse-webserv:bp.productsf] where lower(name) like "%'.strtolower($term).'%" order by name asc;';
$options = ['useLegacySql' => true];
$queryResults1 = $bigQuery->runQuery($query, $options);
 
// prevent direct access
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND
strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) {
  $user_error = 'Access denied - not an AJAX request...';
  trigger_error($user_error, E_USER_ERROR);
}
 
$a_json = array();
$a_json_row = array();
 
// replace multiple spaces with one
 
/**
 * Create SQL
 */
// $sql = 'SELECT url, post_title FROM posts WHERE date_published is not null ';
// for($i = 0; $i < $p; $i++) {
//   $sql .= ' AND post_title LIKE ' . "'%" . $conn->real_escape_string($parts[$i]) . "%'";
// }
 
// $rs = $conn->query($sql);
// if($rs === false) {
//   $user_error = 'Wrong SQL: ' . $sql . 'Error: ' . $conn->errno . ' ' . $conn->error;
//   trigger_error($user_error, E_USER_ERROR);
// }

if ($queryResults1->isComplete()) 
{
    $rows = $queryResults1->rows();

    foreach ($rows as $row) 
    {
        $a_json_row["id"] = $row['sku'];
		$a_json_row["value"] = $row['name'];
		$a_json_row["label"] = $row['name'];
		array_push($a_json, $a_json_row);
    }
} 

// foreach($jsonArray as $item)
// {
// 	if(strpos(strtolower($item->name), strtolower($term)) !== false)
// 	{
// 		$a_json_row["id"] = $item->sku;
// 		$a_json_row["value"] = $item->name;
// 		$a_json_row["label"] = $item->name;
// 		array_push($a_json, $a_json_row);
// 	}
// }

 
// highlight search results
//$a_json = apply_highlight($a_json, $parts);
//header("Content-Type: application/json;");
$json = json_encode($a_json);
echo $json;
?>