<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Nothing But Net Worth</title>
        <meta name="description" content="Calculate your net worth and compare your networth, assets and debts against others.">
        <meta name="keywords" content="Calculate your net worth and compare your networth, assets and debts against others." />
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
    </head>
    <body>

    	<?

		//load wordpress functions, core library etc
		require_once('../blog/wp-blog-header.php');

		//database connection
		include("../../cfg-files/dbinfo.inc.php");
		try {
			$pdo = new PDO('mysql:dbname=nothinn5_networth;host=localhost', $username, $password);
			$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			//EVERYONE total number of submitted entries in database
			$stmt = $pdo->prepare('SELECT COUNT(*) FROM AssetsDebts');
			$stmt->execute();
			$numEntriesInDB = $stmt->fetchColumn();

			//If this is a POST, then lets delete or post the blog message and then just show this page again
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$postHash = $_POST['txt_posthash'];
				$postFirstName = wp_strip_all_tags($_POST['txt_postfirstname']);
				$postMessage = wp_strip_all_tags($_POST['txt_postmessage']);
				$postSecretKey = $_POST['txt_secretkey'];
				if ($postSecretKey == "thisisthepostkey123#!") {
					if (isset($_POST['btn_publish'])) {
						//post btn_publish clicked
						//get the user post from database
						$stmt = $pdo->prepare('SELECT assetsstocks, assetscash, assetsretirement, assetsresidence, assetsautos, assetsother, debtsmortgage, debtsheloc, debtsstudentloans, debtscreditcards, debtsautoloans, debtsother, age, sex, residence FROM UserNetWorthPosts INNER JOIN AssetsDebts ON UserNetWorthPosts.assetsdebtsnwhash = AssetsDebts.nwhash WHERE UserNetWorthPosts.assetsdebtsnwhash = :assetsdebtsnwhash');
						$stmt->execute(array(':assetsdebtsnwhash' => $postHash));
						foreach ($stmt as $row) {
							$assetsstocks = $row['assetsstocks'];
							$assetscash = $row['assetscash'];
							$assetsretirement = $row['assetsretirement'];
							$assetsresidence = $row['assetsresidence'];
							$assetsautos = $row['assetsautos'];
							$assetsother = $row['assetsother'];
							$debtsmortgage = $row['debtsmortgage'];
							$debtsheloc = $row['debtsheloc'];
							$debtsstudentloans = $row['debtsstudentloans'];
							$debtscreditcards = $row['debtscreditcards'];
							$debtsautoloans = $row['debtsautoloans'];
							$debtsother = $row['debtsother'];
							$age = $row['age'];
							$sex = $row['sex'];
							if ($sex == 'M') {
								$sex = "male";
							} else {
								$sex = "female";
							}
							$residence = $row['residence'];
							$totalassets = $assetsstocks + $assetscash + $assetsretirement + $assetsresidence + $assetsautos + $assetsother;
							$totaldebts = $debtsmortgage + $debtsheloc + $debtsstudentloans + $debtscreditcards + $debtsautoloans + $debtsother;
							$calc_networth = $totalassets - $totaldebts;
							$calc_liquidity = $assetsstocks + $assetscash + $assetsautos + $assetsother - $debtsstudentloans - $debtscreditcards - $debtsautoloans - $debtsother;
							break;
						}
						//Create post object
						$postBodyContent = '<h4>' . $postFirstName . ' shared the results of the net worth calculator.</h4>';
						if (strlen($postMessage) > 0) {
							$postBodyContent = $postBodyContent . '<p><blockquote>A personal message from ' . $postFirstName . ':  <strong>' . $postMessage . '</strong></blockquote></p>';
						}
						$postBodyContent = $postBodyContent . '<p>Please take a moment to review the results below and provide your thoughts.  Also, <a title="Nothing But Net Worth" href="http://www.nothingbutnetworth.com" target="_blank">calculate your own net worth</a> and share it with others to get feedback.</p>';
						$postBodyContent = $postBodyContent . '<h4>About ' . $postFirstName . '</h4>';
						$postBodyContent = $postBodyContent . '<p>' . $postFirstName . ' is a ' . $age . ' year old ' . $sex . ' residing in ' . $residence . ' .  ' . $postFirstName . '&#8217;s net worth is $' . $calc_networth . ' and short-term liquidity is $' . $calc_liquidity . '.</p>';
						$postBodyContent = $postBodyContent . '<h4>' . $postFirstName . '&#8217;s Breakdown</h4>';
						$postBodyContent = $postBodyContent . '<p>The following are ' . $postFirstName . '&#8217;s assets and debts.</p>';
						$postBodyContent = $postBodyContent . '<h4>Assets (Total Assets $' . $totalassets . ')</h4>';
						$postBodyContent = $postBodyContent . '<p>Stocks and bonds: $' . $assetsstocks . '</p>';
						$postBodyContent = $postBodyContent . '<p>Cash: $' . $assetscash . '</p>';
						$postBodyContent = $postBodyContent . '<p>Retirement accounts: $' . $assetsretirement . '</p>';
						$postBodyContent = $postBodyContent . '<p>Value of residence(s): $' . $assetsresidence . '</p>';
						$postBodyContent = $postBodyContent . '<p>Value of automobile(s): $' . $assetsautos . '</p>';
						$postBodyContent = $postBodyContent . '<p>Other: $' . $assetsother . '</p>';
						$postBodyContent = $postBodyContent . '<h4>Debts (Total Debts $' . $totaldebts . ')</h4>';
						$postBodyContent = $postBodyContent . '<p>Mortgage(s): $' . $debtsmortgage . '</p>';
						$postBodyContent = $postBodyContent . '<p>Home-equity loan: $' . $debtsheloc . '</p>';
						$postBodyContent = $postBodyContent . '<p>Student loan(s): $' . $debtsstudentloans . '</p>';
						$postBodyContent = $postBodyContent . '<p>Credit cards: $' . $debtscreditcards . '</p>';
						$postBodyContent = $postBodyContent . '<p>Automobile loan(s): $' . $debtsautoloans . '</p>';
						$postBodyContent = $postBodyContent . '<p>Other: $' . $debtsother . '</p>';
						$user_post = array(
						  'post_type'     => 'post',
						  'post_title'    => 'Net Worth Results From ' . $postFirstName,
						  'post_content'  => $postBodyContent,
						  'post_status'   => 'publish',
						  'post_author'   => 2,
						  'tags_input'    => array('net worth','net worth calculator','net worth results','assets', 'debts', 'liquidity', 'nothingbutnetworth.com')
						);
						$post_id = wp_insert_post( $user_post, $wp_error );
						wp_set_post_terms( $post_id, 'Net Worth Results' );
						if (strlen($wp_error) > 0) {
							echo 'Post error = ' . $wp_error;
						}
						echo '<a href="/blog" target="_blank">Visit blog (new window)</a> to verify post and then <a href="#" onclick="WorkWithThisPost(\'' . $postFirstName . '\',\'' . $postMessage . '\',\'' . $postHash . '\');return false;">delete this post</a> if it posted successfully.';
					} else {
						//delete btn_delete clicked
						$stmt = $pdo->prepare('DELETE FROM UserNetWorthPosts WHERE assetsdebtsnwhash = :assetsdebtsnwhash');
						$stmt->execute(array(':assetsdebtsnwhash' => $postHash));
						echo 'Post was deleted';
					}
				}
			}

			//Should be last statement, if a GET or even if coming back after an insert or delete as a POST,
			//show the latest entries so we can work with them
			$stmt = $pdo->prepare('SELECT usernetworthpostsid, firstname, message, assetsdebtsnwhash, status, UserNetWorthPosts.modifieddt as modifieddt, assetsdebtsid, ipaddress, assetsstocks, assetscash, assetsretirement, assetsresidence, assetsautos, assetsother, debtsmortgage, debtsheloc, debtsstudentloans, debtscreditcards, debtsautoloans, debtsother, age, sex, residence, nwhash FROM UserNetWorthPosts INNER JOIN AssetsDebts ON UserNetWorthPosts.assetsdebtsnwhash = AssetsDebts.nwhash ORDER BY usernetworthpostsid ASC');
			$stmt->execute();

		} catch (PDOException $e) {
			$echo = 'Administration error using the database.';
		}

    	?>

        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <form action="zadmin.php" class="navbar-form" method="post">

			<div class="container">

				<div class="row">

					<div class="span12">

						<div>
							<h2><a href="http://www.nothingbutnetworth.com"><img src="http://www.nothingbutnetworth.com/blog/wp-content/themes/atahualpa/images/nothingbutnetworthlogo.png" style="padding-right:10px;"></a>Administration</h2>
						</div>

					</div>

				</div>

				<hr>

				<div class="row">

					<div class="span12">

						<div>
							<h4>There are a total of <? echo ($numEntriesInDB); ?> unique net worth results saved in the system.</h4>
						</div>

					</div>

				</div>

				<hr>

				<?
				$loopCounter = 1;
				foreach ($stmt as $row) {

					$assetsstocks = $row['assetsstocks'];
					$assetscash = $row['assetscash'];
					$assetsretirement = $row['assetsretirement'];
					$assetsresidence = $row['assetsresidence'];
					$assetsautos = $row['assetsautos'];
					$assetsother = $row['assetsother'];
					$debtsmortgage = $row['debtsmortgage'];
					$debtsheloc = $row['debtsheloc'];
					$debtsstudentloans = $row['debtsstudentloans'];
					$debtscreditcards = $row['debtscreditcards'];
					$debtsautoloans = $row['debtsautoloans'];
					$debtsother = $row['debtsother'];
					$age = $row['age'];
					$sex = $row['sex'];
					$residence = $row['residence'];
					$totalassets = $assetsstocks + $assetscash + $assetsretirement + $assetsresidence + $assetsautos + $assetsother;
					$totaldebts = $debtsmortgage + $debtsheloc + $debtsstudentloans + $debtscreditcards + $debtsautoloans + $debtsother;
					$calc_networth = $totalassets - $totaldebts;
					$calc_liquidity = $assetsstocks + $assetscash + $assetsautos + $assetsother - $debtsstudentloans - $debtscreditcards - $debtsautoloans - $debtsother;

				echo '
				<div class="row">
					<div class="span12">
						<div>
							<h5>Admin details</h5>
						</div>
						<div>
							post id = ' . $row["usernetworthpostsid"] . '
						</div>
						<div>
							assets id = ' . $row["assetsdebtsid"] . '
						</div>
						<div>
							post hash = ' . substr($row["assetsdebtsnwhash"], 0, 30) . '...
						</div>
						<div>
							asst hash = ' . substr($row["nwhash"], 0, 30) . '...
						</div>
						<div>
							<h5>Post details</h5>
						</div>
						<div>
							first name: ' . $row["firstname"] . '
						</div>
						<div>
							message: ' . $row["message"] . '
						</div>
						<div>
							post status: ' . $row["status"] . '
						</div>
						<div>
							post modified datetime: ' . $row["modifieddt"] . ' (2 hours behind EST)
						</div>
						<div>
							<h5>AssetDebt details</h5>
						</div>
						<div>
							ipaddress = ' . $row["ipaddress"] . ', age = ' . $age . ', gender = ' . $sex . ', residence = ' . $residence . '
						</div>
						<div>
							assets: assetsstocks = ' . $assetsstocks . ', assetscash = ' . $assetscash . ', assetsretirement = ' . $assetsretirement . ', assetsresidence = ' . $assetsresidence . ', assetsautos = ' . $assetsautos . ', assetsother = ' . $assetsother . '
						</div>
						<div>
							debts: debtsmortgage = ' . $debtsmortgage . ', debtsheloc = ' . $debtsheloc . ', debtsstudentloans = ' . $debtsstudentloans . ', debtscreditcards = ' . $debtscreditcards . ', debtsautoloans = ' . $debtsautoloans . ', debtsother = ' . $debtsother . '
						</div>
						<div>
							total assets = ' . $totalassets . '
						</div>
						<div>
							total debts = ' . $totaldebts . '
						</div>
						<div>
							net worth = ' . $calc_networth . '
						</div>
						<div>
							liquidity = ' . $calc_liquidity . '
						</div>
						<div>
							<p>&nbsp;</p>
						</div>
						<div>
							<a id="myLink' . $loopCounter . '" title="Click to do something" href="#" onclick="WorkWithThisPost(\'' . $row["firstname"] . '\',\'' . $row["message"] . '\',\'' . $row["assetsdebtsnwhash"] . '\');return false;">Click here to work with this post</a>
						</div>
					</div>
				</div>

				<hr>';

				$loopCounter++;
				}
				?>

				<footer>
					<div>
						<label>First name</label>
						<input type="text" name="txt_postfirstname" id="txt_postfirstname" value="">
					</div>
					<div>
						<label>Message</label>
						<textarea name="txt_postmessage" id="txt_postmessage" rows="4"></textarea>
					</div>
					<div>
						<label>Post hash</label>
						<input type="text" name="txt_posthash" id="txt_posthash" value="">
					</div>
					<div>
						<label>Secret key</label>
						<input type="text" name="txt_secretkey" id="txt_secretkey" value="">
					</div>
					<div>
						<input type="Submit" class="btn btn-success" name="btn_publish" id="btn_publish" value="Publish This Post">
						<input type="Submit" class="btn btn-danger" name="btn_delete" id="btn_delete" value="Delete This Post">
					</div>
					<div>
						<p>&nbsp;</p>
					</div>
					<div>
						<a href="#">Back to top</a>
					</div>
				</footer>

			</div>

        </form>

        <script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.8.3.min.js"><\/script>')</script>

        <script src="/js/vendor/bootstrap.min.js"></script>

        <script>
			function WorkWithThisPost(postFirstName, postMessage, postHash) {
				$('html, body').animate({scrollTop: $("#btn_publish").offset().top}, 1000);
				$("#txt_postfirstname").val("");
				$("#txt_postmessage").val("");
				$("#txt_posthash").val("");
				if (postFirstName.length > 0) {
					$("#txt_postfirstname").val(postFirstName);
				} else {
					$("#txt_postfirstname").val("Anonymous");
				}
				$("#txt_postmessage").val(postMessage);
				$("#txt_posthash").val(postHash);
			}
        </script>

    </body>
</html>
