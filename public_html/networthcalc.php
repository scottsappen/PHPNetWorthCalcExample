<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Net Worth Results</title>
        <meta name="description" content="Net worth Calculator, use it to calculate your net worth and compare your networth, assets and debts against others.">
        <meta name="keywords" content="Net worth Calculator, use it to calculate your net worth and compare your networth, assets and debts against others." />
		<meta name="author" content="scott sappenfield" />
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <style>
            body {
                padding-top: 10px;
                padding-bottom: 40px;
            }
        </style>
        <link rel="stylesheet" href="/css/bootstrap-responsive.min.css">
        <script src="/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
        <meta name="google-translate-customization" content="88694610275f2459-843fce1d10f83c04-ge949f1feb3d1e914-1a"></meta>
    </head>

    <body>

		<?
		setlocale(LC_MONETARY, "en_US");

		$ipaddress = $_SERVER["REMOTE_ADDR"];

		//database connection
		include("../cfg-files/dbinfo.inc.php");
		try {
			$pdo = new PDO('mysql:dbname=nothinn5_networth;host=localhost', $username, $password);
			$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo '<p class="text-error">Sorry about that, but the calculator experienced a hiccup.</p>  Please <a href="javascript:history.go(-1)">try again</a> or <a href="/blog">see what others are up to</a> for now.';
		}

		//post variables processing
		$assetsstocks = intval($_POST["assetsstocks"]);
		$assetscash = intval($_POST["assetscash"]);
		$assetsretirement = intval($_POST["assetsretirement"]);
		$assetsresidence = intval($_POST["assetsresidence"]);
		$assetsautos = intval($_POST["assetsautos"]);
		$assetsother = intval($_POST["assetsother"]);
		$debtsmortgage = intval($_POST["debtsmortgage"]);
		$debtsheloc = intval($_POST["debtsheloc"]);
		$debtsstudentloans = intval($_POST["debtsstudentloans"]);
		$debtscreditcards = intval($_POST["debtscreditcards"]);
		$debtsautoloans = intval($_POST["debtsautoloans"]);
		$debtsother = intval($_POST["debtsother"]);
		$age = intval($_POST["age"]);
		$sex = $_POST["sex"];
		$residence = $_POST["residence"];

		//calculate net worth from post variables
		$totalassets = $assetsstocks + $assetscash + $assetsretirement + $assetsresidence + $assetsautos + $assetsother;
		$totaldebts = $debtsmortgage + $debtsheloc + $debtsstudentloans + $debtscreditcards + $debtsautoloans + $debtsother;
		$calc_networth = $totalassets - $totaldebts;

		//we'll calculate short term liquidity as those things sellable so in a pinch, what you can amass
		//total assets minus house and retirement (can't sell house quickly or get retirement funds)
		$calc_liquidity = $assetsstocks + $assetscash + $assetsautos + $assetsother - $debtsstudentloans - $debtscreditcards - $debtsautoloans - $debtsother;

		//unique, somewhat unique, hash for this entry
		$RandomString=chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122));
		$RandomString2=chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122));
		$randhash = md5($RandomString);
		$randhash2 = md5($RandomString2);
		$nwhash = $randhash . $randhash2 . strrev($randhash) . strrev($randhash2);
		//trim if longer than 100 chars
		if (strlen($nwhash) > 100) {
			$nwhash = substr($nwhash, 0, 100);
		}

		//previous submission for this ip address
		$stmt = $pdo->prepare('SELECT nwhash FROM AssetsDebts WHERE ipaddress = :ipaddress');
		$stmt->execute(array(':ipaddress' => $ipaddress));
		$allResultsOfAssetsDebtCheck = $stmt->fetchAll();

		//if a previous id exists, just update that row for the user
		//else, do an insert
		if(count($allResultsOfAssetsDebtCheck) > 0) {
			$stmt = $pdo->prepare('UPDATE AssetsDebts SET assetsstocks=?, assetscash=?, assetsretirement=?, assetsresidence=?, assetsautos=?, assetsother=?, debtsmortgage=?, debtsheloc=?, debtsstudentloans=?, debtscreditcards=?, debtsautoloans=?, debtsother=?, age=?, sex=?, residence=?, nwhash=?, modifieddt=NOW() WHERE ipaddress=?');
			$stmt->execute(array($assetsstocks, $assetscash, $assetsretirement, $assetsresidence, $assetsautos, $assetsother, $debtsmortgage, $debtsheloc, $debtsstudentloans, $debtscreditcards, $debtsautoloans, $debtsother, $age, $sex, $residence, $nwhash, $ipaddress));
			//remove all previous user post submissions under that old data
			$stmt = $pdo->prepare('DELETE FROM UserNetWorthPosts WHERE assetsdebtsnwhash = :assetsdebtsnwhash');
			$stmt->execute(array(':assetsdebtsnwhash' => $allResultsOfAssetsDebtCheck[0]['nwhash']));
		} else {
			$stmt = $pdo->prepare('INSERT INTO AssetsDebts (ipaddress, assetsstocks, assetscash, assetsretirement, assetsresidence, assetsautos, assetsother, debtsmortgage, debtsheloc, debtsstudentloans, debtscreditcards, debtsautoloans, debtsother, age, sex, residence, nwhash, modifieddt) VALUES (:ipaddress, :assetsstocks, :assetscash, :assetsretirement, :assetsresidence, :assetsautos, :assetsother, :debtsmortgage, :debtsheloc, :debtsstudentloans, :debtscreditcards, :debtsautoloans, :debtsother, :age, :sex, :residence, :nwhash, NOW())');
			$stmt->execute(array(':ipaddress' => $ipaddress, ':assetsstocks' => $assetsstocks, ':assetscash' => $assetscash, ':assetsretirement' => $assetsretirement, ':assetsresidence' => $assetsresidence, ':assetsautos' => $assetsautos, ':assetsother' => $assetsother, ':debtsmortgage' => $debtsmortgage, ':debtsheloc' => $debtsheloc, ':debtsstudentloans' => $debtsstudentloans, ':debtscreditcards' => $debtscreditcards, ':debtsautoloans' => $debtsautoloans, ':debtsother' => $debtsother, ':age' => $age, ':sex' => $sex, ':residence' => $residence, ':nwhash' => $nwhash));
			//Set the wonderful hooblyGoobly field, this will be used in networthusershare as the PK for the database entry containing their net worth data
			//$hooblyGoobly = $pdo->lastInsertId();
		}
		$hooblyGoobly = $nwhash;

		//EVERYONE total number of submitted entries in database (not included my ipaddress)
		//$stmt = $pdo->prepare('SELECT COUNT(*) FROM AssetsDebts WHERE ipaddress <> :ipaddress');
		//$stmt->execute(array(':ipaddress' => $ipaddress));
		//$numEntriesInDB = $stmt->fetchColumn();

		//EVERYONE get average of all submitted entries in database
		$stmt = $pdo->prepare('SELECT FLOOR(AVG(assetsstocks)) as assetsstocks, FLOOR(AVG(assetscash)) as assetscash, FLOOR(AVG(assetsretirement)) as assetsretirement, FLOOR(AVG(assetsresidence)) as assetsresidence, FLOOR(AVG(assetsautos)) as assetsautos, FLOOR(AVG(assetsother)) as assetsother, FLOOR(AVG(debtsmortgage)) as debtsmortgage, FLOOR(AVG(debtsheloc)) as debtsheloc, FLOOR(AVG(debtsstudentloans)) as debtsstudentloans, FLOOR(AVG(debtscreditcards)) as debtscreditcards, FLOOR(AVG(debtsautoloans)) as debtsautoloans, FLOOR(AVG(debtsother)) as debtsother, FLOOR(AVG(age)) as age FROM  AssetsDebts WHERE ipaddress <> :ipaddress');
		$stmt->execute(array(':ipaddress' => $ipaddress));
		foreach ($stmt as $row) {
			$assetsstocksavgall = $row['assetsstocks'];
			$assetscashavgall = $row['assetscash'];
			$assetsretirementavgall = $row['assetsretirement'];
			$assetsresidenceavgall = $row['assetsresidence'];
			$assetsautosavgall = $row['assetsautos'];
			$assetsotheravgall = $row['assetsother'];
			$debtsmortgageavgall = $row['debtsmortgage'];
			$debtshelocavgall = $row['debtsheloc'];
			$debtsstudentloansavgall = $row['debtsstudentloans'];
			$debtscreditcardsavgall = $row['debtscreditcards'];
			$debtsautoloansavgall = $row['debtsautoloans'];
			$debtsotheravgall = $row['debtsother'];
			$ageavgall = $row['age'];
			break;
		}
		$totalassetsavgall = $assetsstocksavgall + $assetscashavgall + $assetsretirementavgall + $assetsresidenceavgall + $assetsautosavgall + $assetsotheravgall;
		$totaldebtsavgall = $debtsmortgageavgall + $debtshelocavgall + $debtsstudentloansavgall + $debtscreditcardsavgall + $debtsautoloansavgall + $debtsotheravgall;
		$calc_networthavgall = $totalassetsavgall - $totaldebtsavgall;

		//AGE GROUP total number of submitted entries in my age group
		//$stmt = $pdo->prepare('SELECT COUNT(*) FROM AssetsDebts WHERE age = :age and ipaddress <> :ipaddress');
		//$stmt->execute(array(':age' => $age, ':ipaddress' => $ipaddress));
		//$numEntriesMyAgeGroup = $stmt->fetchColumn();

		//AGE GROUP get average of all submitted entries in my age group
		$stmt = $pdo->prepare('SELECT FLOOR(AVG(assetsstocks)) as assetsstocks, FLOOR(AVG(assetscash)) as assetscash, FLOOR(AVG(assetsretirement)) as assetsretirement, FLOOR(AVG(assetsresidence)) as assetsresidence, FLOOR(AVG(assetsautos)) as assetsautos, FLOOR(AVG(assetsother)) as assetsother, FLOOR(AVG(debtsmortgage)) as debtsmortgage, FLOOR(AVG(debtsheloc)) as debtsheloc, FLOOR(AVG(debtsstudentloans)) as debtsstudentloans, FLOOR(AVG(debtscreditcards)) as debtscreditcards, FLOOR(AVG(debtsautoloans)) as debtsautoloans, FLOOR(AVG(debtsother)) as debtsother, FLOOR(AVG(age)) as age FROM  AssetsDebts WHERE age = :age AND ipaddress <> :ipaddress');
		$stmt->execute(array(':age' => $age, ':ipaddress' => $ipaddress));
		foreach ($stmt as $row) {
			$assetsstocksavgallage = $row['assetsstocks'];
			$assetscashavgallage = $row['assetscash'];
			$assetsretirementavgallage = $row['assetsretirement'];
			$assetsresidenceavgallage = $row['assetsresidence'];
			$assetsautosavgallage = $row['assetsautos'];
			$assetsotheravgallage = $row['assetsother'];
			$debtsmortgageavgallage = $row['debtsmortgage'];
			$debtshelocavgallage = $row['debtsheloc'];
			$debtsstudentloansavgallage = $row['debtsstudentloans'];
			$debtscreditcardsavgallage = $row['debtscreditcards'];
			$debtsautoloansavgallage = $row['debtsautoloans'];
			$debtsotheravgallage = $row['debtsother'];
			break;
		}
		$totalassetsavgallage = $assetsstocksavgallage + $assetscashavgallage + $assetsretirementavgallage + $assetsresidenceavgallage + $assetsautosavgallage + $assetsotheravgallage;
		$totaldebtsavgallage = $debtsmortgageavgallage + $debtshelocavgallage + $debtsstudentloansavgallage + $debtscreditcardsavgallage + $debtsautoloansavgallage + $debtsotheravgallage;
		$calc_networthavgallage = $totalassetsavgallage - $totaldebtsavgallage;

		//GENDER total number of submitted entries in my gender
		//$stmt = $pdo->prepare('SELECT COUNT(*) FROM AssetsDebts WHERE sex = :sex and ipaddress <> :ipaddress');
		//$stmt->execute(array(':sex' => $sex));
		//$numEntriesMyGender = $stmt->fetchColumn() - 1;

		//GENDER get average of all submitted entries in my gender
		$stmt = $pdo->prepare('SELECT FLOOR(AVG(assetsstocks)) as assetsstocks, FLOOR(AVG(assetscash)) as assetscash, FLOOR(AVG(assetsretirement)) as assetsretirement, FLOOR(AVG(assetsresidence)) as assetsresidence, FLOOR(AVG(assetsautos)) as assetsautos, FLOOR(AVG(assetsother)) as assetsother, FLOOR(AVG(debtsmortgage)) as debtsmortgage, FLOOR(AVG(debtsheloc)) as debtsheloc, FLOOR(AVG(debtsstudentloans)) as debtsstudentloans, FLOOR(AVG(debtscreditcards)) as debtscreditcards, FLOOR(AVG(debtsautoloans)) as debtsautoloans, FLOOR(AVG(debtsother)) as debtsother, FLOOR(AVG(age)) as age FROM  AssetsDebts WHERE sex = :sex AND ipaddress <> :ipaddress');
		$stmt->execute(array(':sex' => $sex, ':ipaddress' => $ipaddress));
		foreach ($stmt as $row) {
			$assetsstocksavgallsex = $row['assetsstocks'];
			$assetscashavgallsex = $row['assetscash'];
			$assetsretirementavgallsex = $row['assetsretirement'];
			$assetsresidenceavgallsex = $row['assetsresidence'];
			$assetsautosavgallsex = $row['assetsautos'];
			$assetsotheravgallsex = $row['assetsother'];
			$debtsmortgsexavgallsex = $row['debtsmortgage'];
			$debtshelocavgallsex = $row['debtsheloc'];
			$debtsstudentloansavgallsex = $row['debtsstudentloans'];
			$debtscreditcardsavgallsex = $row['debtscreditcards'];
			$debtsautoloansavgallsex = $row['debtsautoloans'];
			$debtsotheravgallsex = $row['debtsother'];
			break;
		}
		$totalassetsavgallsex = $assetsstocksavgallsex + $assetscashavgallsex + $assetsretirementavgallsex + $assetsresidenceavgallsex + $assetsautosavgallsex + $assetsotheravgallsex;
		$totaldebtsavgallsex = $debtsmortgageavgallsex + $debtshelocavgallsex + $debtsstudentloansavgallsex + $debtscreditcardsavgallsex + $debtsautoloansavgallsex + $debtsotheravgallsex;
		$calc_networthavgallsex = $totalassetsavgallsex - $totaldebtsavgallsex;

		//RESIDENCE total number of submitted entries in my residence
		//$stmt = $pdo->prepare('SELECT COUNT(*) FROM AssetsDebts WHERE residence = :residence and ipaddress <> :ipaddress');
		//$stmt->execute(array(':residence' => $residence));
		//$numEntriesMyResidence = $stmt->fetchColumn() - 1;

		//RESIDENCE get average of all submitted entries in my residence
		$stmt = $pdo->prepare('SELECT FLOOR(AVG(assetsstocks)) as assetsstocks, FLOOR(AVG(assetscash)) as assetscash, FLOOR(AVG(assetsretirement)) as assetsretirement, FLOOR(AVG(assetsresidence)) as assetsresidence, FLOOR(AVG(assetsautos)) as assetsautos, FLOOR(AVG(assetsother)) as assetsother, FLOOR(AVG(debtsmortgage)) as debtsmortgage, FLOOR(AVG(debtsheloc)) as debtsheloc, FLOOR(AVG(debtsstudentloans)) as debtsstudentloans, FLOOR(AVG(debtscreditcards)) as debtscreditcards, FLOOR(AVG(debtsautoloans)) as debtsautoloans, FLOOR(AVG(debtsother)) as debtsother, FLOOR(AVG(age)) as age FROM  AssetsDebts WHERE residence = :residence AND ipaddress <> :ipaddress');
		$stmt->execute(array(':residence' => $residence, ':ipaddress' => $ipaddress));
		foreach ($stmt as $row) {
			$assetsstocksavgallresidence = $row['assetsstocks'];
			$assetscashavgallresidence = $row['assetscash'];
			$assetsretirementavgallresidence = $row['assetsretirement'];
			$assetsresidenceavgallresidence = $row['assetsresidence'];
			$assetsautosavgallresidence = $row['assetsautos'];
			$assetsotheravgallresidence = $row['assetsother'];
			$debtsmortgresidenceavgallresidence = $row['debtsmortgage'];
			$debtshelocavgallresidence = $row['debtsheloc'];
			$debtsstudentloansavgallresidence = $row['debtsstudentloans'];
			$debtscreditcardsavgallresidence = $row['debtscreditcards'];
			$debtsautoloansavgallresidence = $row['debtsautoloans'];
			$debtsotheravgallresidence = $row['debtsother'];
			break;
		}
		$totalassetsavgallresidence = $assetsstocksavgallresidence + $assetscashavgallresidence + $assetsretirementavgallresidence + $assetsresidenceavgallresidence + $assetsautosavgallresidence + $assetsotheravgallresidence;
		$totaldebtsavgallresidence = $debtsmortgageavgallresidence + $debtshelocavgallresidence + $debtsstudentloansavgallresidence + $debtscreditcardsavgallresidence + $debtsautoloansavgallresidence + $debtsotheravgallresidence;
		$calc_networthavgallresidence = $totalassetsavgallresidence - $totaldebtsavgallresidence;

		$pdo = null;

		?>

		<!--[if lt IE 7]>
			<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
		<![endif]-->

		<form action="networthusershare.php" class="navbar-form" method="post">

			<div class="container">

				<div class="row">

					<div class="span12">

						<div class="pull-right" style="padding:20px 0 20px 0;">
							<div><p class="muted">Please share & help spread the magic!</p></div>
							<div class="fb-like" data-href="http://www.nothingbutnetworth.com" data-send="true" data-width="280" data-show-faces="true"></div>
							<div><a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.nothingbutnetworth.com" data-text="What's your Net Worth?" data-hashtags="networth">Tweet</a>
							<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>
							<div id="google_translate_element"></div><script type="text/javascript">
							function googleTranslateElementInit() {
							  new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, gaTrack: true, gaId: 'UA-38052312-1'}, 'google_translate_element');
							}
							</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
						</div>
						<div>
							<h3>
								Your <a data-toggle="modal" href="#myModalNetWorthExplained">net worth</a> is <? if ($calc_networth > 0) { ?><span class="text-success"><? } else { ?><span class="text-error"><? } echo money_format('%(.0n', $calc_networth); ?></span>
							</h3>
						</div>
						<div>
							<h3>
								Your <a data-toggle="modal" href="#myModalLiquidityExplained">short term liquidity</a> is <span class="text-success"><? echo money_format('%(.0n', $calc_liquidity); ?></span>
							</h3>
						</div>
						<? if ($calc_networth > 0) { ?>
						<div class="alert alert-success" id="div_alert_landing">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							You have a positive networth. That's probably a good thing. Keep it up!
						</div>
						<? } else if ($calc_networth < 0) { ?>
						<div class="alert" id="div_alert_landing">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							You have a negative networth. Looks like there may be some work to do.
						</div>
						<? } ?>
						<div>
							<img src="/img/circle2.png" style="padding-right:10px;"><input style="vertical-align:middle;" type="Submit" class="btn btn-success" id="btn_shareresults" value="Share My Results"<? if (($totalassets == 0) && ($totaldebts == 0)) { echo ' disabled="disabled"'; }  ?>>&nbsp;<a data-toggle="modal" href="#myModalSharingExplained">What does that mean?</a>
						</div>
						<div>
							<p>&nbsp;</p>
						</div>
						<div>
							<a class="btn btn-info" href="/index.php?bhb=yes">Revise My Entries</a>
							<a class="btn btn-info" href="/">Start Over</a>
							<a class="btn btn-info" href="/blog">Join Discussions</a>
						</div>

					</div>

				</div>

				<hr>

				<div class="row">

					<div class="span12">

						<div class="accordion" id="accordion2">

							<div class="accordion-group">
								<div class="accordion-heading">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseG1">
									<h4>Compare me with everyone else</h4>
									</a>
								</div>
								<div id="collapseG1" class="accordion-body collapse in">
									<div class="accordion-inner">
										<div class="row">
											<div class="span12">
												<div>
													<h3>Everyone else</h3>
												</div>
												<div>
													<h4>Average age: <? echo $ageavgall; ?></h4>
												</div>
											</div>
										</div>
										<div class="row-fluid">
											<div class="span4">
												<div>
													<h4>Total assets</h4>
												</div>
												<div>
													<? $a1=$totalassets; $a2=$totalassetsavgall; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
													<p>
													<? echo ($a1 > $a2) ? '<p class="text-success">' : '<p class="text-error">'; ?>
													Difference
													<? echo money_format('%(.0n', abs($a1 - $a2)); ?>
													<? echo ($a1 > $a2) ? '<i class="icon-thumbs-up"></i>' : '<i class="icon-thumbs-down"></i>'; ?>
													</p>
												</div>
											</div>
											<div class="span4">
												<div>
													<h4>Total debts</h4>
												</div>
												<div>
													<? $a1=$totaldebts; $a2=$totaldebtsavgall; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
													<p>
													<? echo ($a1 < $a2) ? '<p class="text-success">' : '<p class="text-error">'; ?>
													Difference
													<? echo money_format('%(.0n', abs($a1 - $a2)); ?>
													<? echo ($a1 < $a2) ? '<i class="icon-thumbs-up"></i>' : '<i class="icon-thumbs-down"></i>'; ?>
													</p>
												</div>
											</div>
											<div class="span4">
												<div>
													<h4>Net worth</h4>
												</div>
												<div>
													<? $a1=$calc_networth; $a2=$calc_networthavgall; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
													<p>
													<? echo ($a1 > $a2) ? '<p class="text-success">' : '<p class="text-error">'; ?>
													Difference
													<? echo money_format('%(.0n', abs($a1 - $a2)); ?>
													<? echo ($a1 > $a2) ? '<i class="icon-thumbs-up"></i>' : '<i class="icon-thumbs-down"></i>'; ?>
													</p>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="span12">
												<div>
													<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#div_collapseG1details">
													  Toggle the details
													</button>
												</div>
											</div>
										</div>
										<div class="row-fluid collapse" id="div_collapseG1details">
											<div class="span6">
												<div>
													<h5>Stocks and stock funds</h5>
												</div>
												<div>
													<? $a1=$assetsstocks; $a2=$assetsstocksavgall; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Cash</h5>
												</div>
												<div>
													<? $a1=$assetscash; $a2=$assetscashavgall; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Retirement accounts</h5>
												</div>
												<div>
													<? $a1=$assetsretirement; $a2=$assetsretirementavgall; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Value of residence(s)</h5>
												</div>
												<div>
													<? $a1=$assetsresidence; $a2=$assetsresidenceavgall; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Value of automobile(s)</h5>
												</div>
												<div>
													<? $a1=$assetsautos; $a2=$assetsautosavgall; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Other assets</h5>
												</div>
												<div>
													<? $a1=$assetsother; $a2=$assetsotheravgall; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
											</div>
											<div class="span6">
												<div>
													<h5>Mortgage(s)</h5>
												</div>
												<div>
													<? $a1=$debtsmortgage; $a2=$debtsmortgageavgall; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Home-equity loan</h5>
												</div>
												<div>
													<? $a1=$debtsheloc; $a2=$debtshelocavgall; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Student loan</h5>
												</div>
												<div>
													<? $a1=$debtsstudentloans; $a2=$debtsstudentloansavgall; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Credit cards</h5>
												</div>
												<div>
													<? $a1=$debtscreditcards; $a2=$debtscreditcardsavgall; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Automobile loan(s)</h5>
												</div>
												<div>
													<? $a1=$debtsautoloans; $a2=$debtsautoloansavgall; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Other debts</h5>
												</div>
												<div>
													<? $a1=$debtsother; $a2=$debtsotheravgall; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="accordion-group">
								<div class="accordion-heading">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseH1">
									<h4>Compare me with others age <? echo $age; ?></h4>
									</a>
									</div>
									<div id="collapseH1" class="accordion-body collapse">
									<div class="accordion-inner">
										<div class="row">
											<div class="span12">
												<div>
													<h3>Others your age</h3>
												</div>
											</div>
										</div>
										<div class="row-fluid">
											<div class="span4">
												<div>
													<h4>Total assets</h4>
												</div>
												<div>
													<? $a1=$totalassets; $a2=$totalassetsavgallage; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
													<p>
													<? echo ($a1 > $a2) ? '<p class="text-success">' : '<p class="text-error">'; ?>
													Difference
													<? echo money_format('%(.0n', abs($a1 - $a2)); ?>
													<? echo ($a1 > $a2) ? '<i class="icon-thumbs-up"></i>' : '<i class="icon-thumbs-down"></i>'; ?>
													</p>
												</div>
											</div>
											<div class="span4">
												<div>
													<h4>Total debts</h4>
												</div>
												<div>
													<? $a1=$totaldebts; $a2=$totaldebtsavgallage; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
													<p>
													<? echo ($a1 < $a2) ? '<p class="text-success">' : '<p class="text-error">'; ?>
													Difference
													<? echo money_format('%(.0n', abs($a1 - $a2)); ?>
													<? echo ($a1 < $a2) ? '<i class="icon-thumbs-up"></i>' : '<i class="icon-thumbs-down"></i>'; ?>
													</p>
												</div>
											</div>
											<div class="span4">
												<div>
													<h4>Net worth</h4>
												</div>
												<div>
													<? $a1=$calc_networth; $a2=$calc_networthavgallage; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
													<p>
													<? echo ($a1 > $a2) ? '<p class="text-success">' : '<p class="text-error">'; ?>
													Difference
													<? echo money_format('%(.0n', abs($a1 - $a2)); ?>
													<? echo ($a1 > $a2) ? '<i class="icon-thumbs-up"></i>' : '<i class="icon-thumbs-down"></i>'; ?>
													</p>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="span12">
												<div>
													<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#div_collapseH1details">
													  Toggle the details
													</button>
												</div>
											</div>
										</div>
										<div class="row-fluid collapse" id="div_collapseH1details">
											<div class="span6">
												<div>
													<h5>Stocks and stock funds</h5>
												</div>
												<div>
													<? $a1=$assetsstocks; $a2=$assetsstocksavgallage; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Cash</h5>
												</div>
												<div>
													<? $a1=$assetscash; $a2=$assetscashavgallage; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Retirement accounts</h5>
												</div>
												<div>
													<? $a1=$assetsretirement; $a2=$assetsretirementavgallage; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Value of residence(s)</h5>
												</div>
												<div>
													<? $a1=$assetsresidence; $a2=$assetsresidenceavgallage; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Value of automobile(s)</h5>
												</div>
												<div>
													<? $a1=$assetsautos; $a2=$assetsautosavgallage; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Other assets</h5>
												</div>
												<div>
													<? $a1=$assetsother; $a2=$assetsotheravgallage; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
											</div>
											<div class="span6">
												<div>
													<h5>Mortgage(s)</h5>
												</div>
												<div>
													<? $a1=$debtsmortgage; $a2=$debtsmortgageavgallage; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Home-equity loan</h5>
												</div>
												<div>
													<? $a1=$debtsheloc; $a2=$debtshelocavgallage; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Student loan</h5>
												</div>
												<div>
													<? $a1=$debtsstudentloans; $a2=$debtsstudentloansavgallage; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Credit cards</h5>
												</div>
												<div>
													<? $a1=$debtscreditcards; $a2=$debtscreditcardsavgallage; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Automobile loan(s)</h5>
												</div>
												<div>
													<? $a1=$debtsautoloans; $a2=$debtsautoloansavgallage; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Other debts</h5>
												</div>
												<div>
													<? $a1=$debtsother; $a2=$debtsotheravgallage; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="accordion-group">
								<div class="accordion-heading">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseI1">
									<h4>Compare me with other <? echo ($sex == 'M') ? 'males' : 'females'; ?></h4>
									</a>
									</div>
									<div id="collapseI1" class="accordion-body collapse">
									<div class="accordion-inner">
										<div class="row">
											<div class="span12">
												<div>
													<h3>Others your gender</h3>
												</div>
											</div>
										</div>
										<div class="row-fluid">
											<div class="span4">
												<div>
													<h4>Total assets</h4>
												</div>
												<div>
													<? $a1=$totalassets; $a2=$totalassetsavgallsex; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
													<p>
													<? echo ($a1 > $a2) ? '<p class="text-success">' : '<p class="text-error">'; ?>
													Difference
													<? echo money_format('%(.0n', abs($a1 - $a2)); ?>
													<? echo ($a1 > $a2) ? '<i class="icon-thumbs-up"></i>' : '<i class="icon-thumbs-down"></i>'; ?>
													</p>
												</div>
											</div>
											<div class="span4">
												<div>
													<h4>Total debts</h4>
												</div>
												<div>
													<? $a1=$totaldebts; $a2=$totaldebtsavgallsex; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
													<p>
													<? echo ($a1 < $a2) ? '<p class="text-success">' : '<p class="text-error">'; ?>
													Difference
													<? echo money_format('%(.0n', abs($a1 - $a2)); ?>
													<? echo ($a1 < $a2) ? '<i class="icon-thumbs-up"></i>' : '<i class="icon-thumbs-down"></i>'; ?>
													</p>
												</div>
											</div>
											<div class="span4">
												<div>
													<h4>Net worth</h4>
												</div>
												<div>
													<? $a1=$calc_networth; $a2=$calc_networthavgallsex; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
													<p>
													<? echo ($a1 > $a2) ? '<p class="text-success">' : '<p class="text-error">'; ?>
													Difference
													<? echo money_format('%(.0n', abs($a1 - $a2)); ?>
													<? echo ($a1 > $a2) ? '<i class="icon-thumbs-up"></i>' : '<i class="icon-thumbs-down"></i>'; ?>
													</p>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="span12">
												<div>
													<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#div_collapseI1details">
													  Toggle the details
													</button>
												</div>
											</div>
										</div>
										<div class="row-fluid collapse" id="div_collapseI1details">
											<div class="span6">
												<div>
													<h5>Stocks and stock funds</h5>
												</div>
												<div>
													<? $a1=$assetsstocks; $a2=$assetsstocksavgallsex; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Cash</h5>
												</div>
												<div>
													<? $a1=$assetscash; $a2=$assetscashavgallsex; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Retirement accounts</h5>
												</div>
												<div>
													<? $a1=$assetsretirement; $a2=$assetsretirementavgallsex; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Value of residence(s)</h5>
												</div>
												<div>
													<? $a1=$assetsresidence; $a2=$assetsresidenceavgallsex; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Value of automobile(s)</h5>
												</div>
												<div>
													<? $a1=$assetsautos; $a2=$assetsautosavgallsex; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Other assets</h5>
												</div>
												<div>
													<? $a1=$assetsother; $a2=$assetsotheravgallsex; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
											</div>
											<div class="span6">
												<div>
													<h5>Mortgage(s)</h5>
												</div>
												<div>
													<? $a1=$debtsmortgage; $a2=$debtsmortgageavgallsex; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Home-equity loan</h5>
												</div>
												<div>
													<? $a1=$debtsheloc; $a2=$debtshelocavgallsex; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Student loan</h5>
												</div>
												<div>
													<? $a1=$debtsstudentloans; $a2=$debtsstudentloansavgallsex; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Credit cards</h5>
												</div>
												<div>
													<? $a1=$debtscreditcards; $a2=$debtscreditcardsavgallsex; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Automobile loan(s)</h5>
												</div>
												<div>
													<? $a1=$debtsautoloans; $a2=$debtsautoloansavgallsex; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Other debts</h5>
												</div>
												<div>
													<? $a1=$debtsother; $a2=$debtsotheravgallsex; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="accordion-group">
								<div class="accordion-heading">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseJ1">
									<h4>Compare me with others in <? echo $residence; ?></h4>
									</a>
									</div>
									<div id="collapseJ1" class="accordion-body collapse">
									<div class="accordion-inner">
										<div class="row">
											<div class="span12">
												<div>
													<h3>Others in your location</h3>
												</div>
											</div>
										</div>
										<div class="row-fluid">
											<div class="span4">
												<div>
													<h4>Total assets</h4>
												</div>
												<div>
													<? $a1=$totalassets; $a2=$totalassetsavgallresidence; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
													<p>
													<? echo ($a1 > $a2) ? '<p class="text-success">' : '<p class="text-error">'; ?>
													Difference
													<? echo money_format('%(.0n', abs($a1 - $a2)); ?>
													<? echo ($a1 > $a2) ? '<i class="icon-thumbs-up"></i>' : '<i class="icon-thumbs-down"></i>'; ?>
													</p>
												</div>
											</div>
											<div class="span4">
												<div>
													<h4>Total debts</h4>
												</div>
												<div>
													<? $a1=$totaldebts; $a2=$totaldebtsavgallresidence; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
													<p>
													<? echo ($a1 < $a2) ? '<p class="text-success">' : '<p class="text-error">'; ?>
													Difference
													<? echo money_format('%(.0n', abs($a1 - $a2)); ?>
													<? echo ($a1 < $a2) ? '<i class="icon-thumbs-up"></i>' : '<i class="icon-thumbs-down"></i>'; ?>
													</p>
												</div>
											</div>
											<div class="span4">
												<div>
													<h4>Net worth</h4>
												</div>
												<div>
													<? $a1=$calc_networth; $a2=$calc_networthavgallresidence; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
													<p>
													<? echo ($a1 > $a2) ? '<p class="text-success">' : '<p class="text-error">'; ?>
													Difference
													<? echo money_format('%(.0n', abs($a1 - $a2)); ?>
													<? echo ($a1 > $a2) ? '<i class="icon-thumbs-up"></i>' : '<i class="icon-thumbs-down"></i>'; ?>
													</p>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="span12">
												<div>
													<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#div_collapseJ1details">
													  Toggle the details
													</button>
												</div>
											</div>
										</div>
										<div class="row-fluid collapse" id="div_collapseJ1details">
											<div class="span6">
												<div>
													<h5>Stocks and stock funds</h5>
												</div>
												<div>
													<? $a1=$assetsstocks; $a2=$assetsstocksavgallresidence; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Cash</h5>
												</div>
												<div>
													<? $a1=$assetscash; $a2=$assetscashavgallresidence; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Retirement accounts</h5>
												</div>
												<div>
													<? $a1=$assetsretirement; $a2=$assetsretirementavgallresidence; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Value of residence(s)</h5>
												</div>
												<div>
													<? $a1=$assetsresidence; $a2=$assetsresidenceavgallresidence; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Value of automobile(s)</h5>
												</div>
												<div>
													<? $a1=$assetsautos; $a2=$assetsautosavgallresidence; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Other assets</h5>
												</div>
												<div>
													<? $a1=$assetsother; $a2=$assetsotheravgallresidence; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
											</div>
											<div class="span6">
												<div>
													<h5>Mortgage(s)</h5>
												</div>
												<div>
													<? $a1=$debtsmortgage; $a2=$debtsmortgageavgallresidence; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Home-equity loan</h5>
												</div>
												<div>
													<? $a1=$debtsheloc; $a2=$debtshelocavgallresidence; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Student loan</h5>
												</div>
												<div>
													<? $a1=$debtsstudentloans; $a2=$debtsstudentloansavgallresidence; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Credit cards</h5>
												</div>
												<div>
													<? $a1=$debtscreditcards; $a2=$debtscreditcardsavgallresidence; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Automobile loan(s)</h5>
												</div>
												<div>
													<? $a1=$debtsautoloans; $a2=$debtsautoloansavgallresidence; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
												<div>
													<h5>Other debts</h5>
												</div>
												<div>
													<? $a1=$debtsother; $a2=$debtsotheravgallresidence; ?>
													<p>You <? echo money_format('%(.0n', $a1); ?></p>
													<p>Others <? echo money_format('%(.0n', $a2); ?></p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>

				</div>

				<hr>

				<footer>
					<p class="pull-right"><a href="#">Back to top</a></p>
					<p><span class="muted">&copy; 2012 NothingButNetWorth.com</span> &middot; <a href="/blog/net-worth-only-net-worth/">About</a></p>
				</footer>

			</div>

			<div id="myModalNetWorthExplained" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h3 id="H4">Net Worth</h3>
				</div>
				<div class="modal-body" style="max-height:200px;">
					<p>
						What is your net worth?  Is that what you're worth as a person?  No.  But there is some value in
						understanding where you are financially.
						As far as this tool is concerned, that picture is painted using a very simple calculation.
						</p><p>
						<span class="text-success">Net Worth</span> = (<span class="text-success">Total Assets</span> - <span class="text-error">Total Debts</span>)
						</p><p>
						It should be said that this tool can be classified as another random Internet tool so keep that mind.
						Meaning the tool is only as good as its input and on the Internet, you know, anything goes.
						</p><p>
						That being said, hopefully enough people over time will submit their information and
						make this more meaningful.
					</p>
				</div>
				<div class="modal-footer">
				  <button class="btn" data-dismiss="modal">Close</button>
				</div>
			</div>
			<div id="myModalLiquidityExplained" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h3 id="H4">Short Term Liquidity</h3>
				</div>
				<div class="modal-body" style="max-height:200px;">
					<p>
						This ought to give you a rough idea of where you stand if you had to quickly pull together
						your assets while also paying off your debts.  It's sort of a short-term peace of mind type number and
						was easy enough to calculate using the information you provided, so we decided to do it for you.
						</p><p>
						Assets - we've left off your retirement savings and your residence (assuming you don't want
						to touch that and can't sell your house quickly).
						</p><p>
						Debts - we've left off your mortgage and heloc (assuming you can't sell your house quickly).
					</p>
				</div>
				<div class="modal-footer">
				  <button class="btn" data-dismiss="modal">Close</button>
				</div>
			</div>
			<div id="myModalSharingExplained" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h3 id="H4">Sharing Your Net Worth Results</h3>
				</div>
				<div class="modal-body" style="max-height:200px;">
					<p>
						Each time you calculate your net worth, you have the opportunity to share those results with others (either
						anonymously or using your first name and last initial).
						</p><p>
						Ok, great, but <a href="http://www.nothingbutnetworth.com/blog/2013/02/05/net-worth-results-from-janie-m/">show me an example</a> of someone's post!
						</p><p>
						If you share your results, it will go under review.  If approved, your results will be published on the
						blog homepage as an actual live blog post.  The ability to share is only available when you have actually
						entered data for your assets and debts.  It doesn't do much good to share it otherwise.
						</p><p>
						As a post, people will be able to comment and provide feedback on anything, such as how you're doing.
						Small pats on the back can give you positive encouragement.  You can bookmark your post and come
						back to check on any updates at any time.  People will be able to see the breakdown of Assets and Debts
						that you entered.
						</p><p>
						A helpful hint: When sharing your results, just provide your first name and last initial.  As you would in
						any public forum, you don't want to share any private information about yourself such as your last name,
						phone number or street address.
						</p><p>
						Also, each time you calculate your net worth, your previous unpublished submissions to share your
						results are removed.  As an example, if you calculate your net worth and choose to share it with
						others, but come back 5 minutes later and recalculate your net worth, all previous requests to
						share your information are removed.  In general, we don't want to publish old results if you have
						calculated new results.  Use those new results to share with others.
					</p>
				</div>
				<div class="modal-footer">
				  <button class="btn" data-dismiss="modal">Close</button>
				</div>
			</div>

			<input type="hidden" name="hooblyGoobly" value="<? echo $hooblyGoobly; ?>">

		</form>

        <script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.8.3.min.js"><\/script>')</script>

        <script src="/js/vendor/bootstrap.min.js"></script>

		<script type="text/javascript">

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-38052312-1']);
		  _gaq.push(['_trackPageview']);

		  (function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>

		<script>
			$(document).ready(function(){
				$("#btn_avgnwbyagebarchartredraw").click(function () {
					drawChart();
				});
			});
		</script>

		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>

    </body>
</html>
