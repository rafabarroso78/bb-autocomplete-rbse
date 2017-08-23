<?php
	require __DIR__ . '/vendor/autoload.php'; 

	use Google\Cloud\BigQuery\BigQueryClient;
	include 'data.php';

	$term = trim($_GET['id']);
	//[START build_service]
	$bigQuery = new BigQueryClient([
    	'projectId' => 'rbse-webserv',
	]);

	$query = 'SELECT * FROM [rbse-webserv:bp.products] WHERE sku = 643628 LIMIT 1;';
	$options = ['useLegacySql' => true];
	$queryResults = $bigQuery->runQuery($query, $options);

	foreach($jsonArray as $item)
	{
		if(strpos($item->sku, $term) !== false)
		{
			$productInfo = $item;
		}
	}
?>
<html>
	<title>Product Search Demo App</title>
	<head>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
		<link rel="stylesheet" type="text/css" href="/static/jquery/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="/static/css/autocomplete.css">
		<link rel="stylesheet" type="text/css" href="/static/css/bootstrap.css"/>
		<script type="text/javascript" src="/static/jquery/external/jquery/jquery.js"></script>
		<script type="text/javascript" src="/static/jquery/jquery-ui.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="/static/jquery/jquery.ui.autocomplete.html.js"></script>
		<script type="text/javascript" src="/static/js/autocomplete.js"></script>
		<link rel="stylesheet" href="/static/jquery/jquery-ui.min.css" type="text/css"/> 
	</head>
	<body style="margin:10px;width:95%;">

		<div  style="background:#003d69;color:#003d69;padding:10px">
      		<h1 style="color:#fff"><a style="color:white;" href="/">Better Purchase</a></h1>
   		</div>

  		<div class="container-fluid">

			<div class="sidebar">
				<br><br>
				<hr>
				<a href="/"><< Back to Search</a>
			<p></p>
			<p><a href="{{ url }}"></a></p>

			</div> <!-- end sidebar -->

			<div class="content">
				<br>
			  	<h1><? echo $productInfo->name ?><Br><span style="font-size:14px;">SKU: <? echo $productInfo->sku ?></span></h1>
			  	<table class="table">
			  		<tr>
			  			<td style="width:300px;"><img src="<? echo $productInfo->image ?>" style="width:280;"></td>
			  			<td style="text-align:left;">
			  				<span style="font-size:24px;color:orange">$<? echo number_format($productInfo->price,2)?></span>
			  				<br>
			  				<br>
			  				<b>Manufacturer</b><Br>
			  				<? echo $productInfo->manufacturer ?><br>
			  				<b>Model</b><Br>
			  				<? echo $productInfo->model ?><br>
			  				<b>Description</b><Br>
			  				<? echo $productInfo->description ?>
			  			</td>
			  		</tr>
			  	</table>
				<!-- <a href="javascript:void(0)" class="btn btn-warning" id= "test-button">Test Here!</a> -->
			</div> <!-- end content -->
		</div> <!-- end container-fluid -->
	</body>
</html>