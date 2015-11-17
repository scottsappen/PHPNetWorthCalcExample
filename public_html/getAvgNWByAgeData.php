<?php

	//database connection
	include("../cfg-files/dbinfo.inc.php");
	try {
		$pdo = new PDO('mysql:dbname=nothinn5_networth;host=localhost', $username, $password);
		$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		echo 'Sorry about that, but the data for average net worth by age is unavailable.  Please try again later.';
	}

	$dataTable = array(
		'cols' => array(
			 array('type' => 'number', 'label' => 'Age'),
			 array('type' => 'number', 'label' => 'Average Net Worth')
		)
	);

	//Get average net worth by age data
	$stmt = $pdo->prepare('SELECT AGE, FLOOR(AVG(`assetsstocks` + `assetscash` + `assetsretirement` + `assetsresidence` + `assetsautos` + `assetsother` - `debtsmortgage` - `debtsheloc` - `debtsstudentloans` - `debtscreditcards` - `debtsautoloans` - `debtsother`)) as AVGNWBYAGE FROM `AssetsDebts` GROUP BY AGE');
	$stmt->execute();
	foreach ($stmt as $row) {
		$assetsstocksavgall = $row["assetsstocks"];
		$assetscashavgall = $row['assetscash'];
		$dataTable['rows'][] = array(
			'c' => array (
				 array('v' => $row['AGE']),
				 array('v' => $row['AVGNWBYAGE'])
			 )
		);
	}

	$jsonData = json_encode($dataTable);

	echo $jsonData;

?>