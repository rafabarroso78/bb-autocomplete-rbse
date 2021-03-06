<?php
// contains utility functions mb_stripos_all() and apply_highlight()
//require_once 'local_utils.php';

require __DIR__ . '/vendor/autoload.php'; 

//use Google\Cloud\BigQuery\BigQueryClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$term = trim($_GET['id']);
echo 'this is term:'.$term;
//[START build_service]
// $bigQuery = new BigQueryClient([
// 	'projectId' => 'rbse-webserv',
// ]);
// Connect to CloudSQL from App Engine.
$dsn = getenv('MYSQL_DSN');
$user = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');
if (!isset($dsn, $user) || false === $password) {
    throw new Exception('Set MYSQL_DSN, MYSQL_USER, and MYSQL_PASSWORD environment variables');
}

$db = new PDO($dsn, $user, $password);

$queryResults1 = $db->query('SELECT sku, name FROM products where lower(name) like "%'.$term.'%"');
//$options = ['useLegacySql' => true];

//echo 'query: '.$query;
   
//$queryResults1 = $bigQuery->runQuery($query, $options);

$a_json = array();
$a_json_row = array();
 
$a_json_invalid = array(array("id" => "#", "value" => $term, "label" => "Only letters and digits are permitted..."));
$json_invalid = json_encode($a_json_invalid);
  
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
echo '<pre>';
echo $json;
?>