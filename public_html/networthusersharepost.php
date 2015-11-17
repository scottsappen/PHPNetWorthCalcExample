<?php
  require_once('recaptchalib.php');
  include_once("Mail.php");
  $privatekey = "6LchYdwSAAAAAFJq4wZwOX9sLQX6ETs0QeO6gfd4";
  $resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

  if (!$resp->is_valid) {
    // What happens when the CAPTCHA was entered incorrectly
    $userMessage = '<p class="text-error">The reCAPTCHA verification code was incorrect.</p> Please <a href="javascript:history.go(-1)">go back</a> in your browser and try it again.';
  } else {

	//database connection
	include("../cfg-files/dbinfo.inc.php");
	include("../cfg-files/mailinfo.inc.php");
	try {
		$pdo = new PDO('mysql:dbname=nothinn5_networth;host=localhost', $username, $password);
		$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$firstname = $_POST["firstName"];
		$message = $_POST["userMessage"];
		$assetsdebtsnwhash = $_POST["hooblyGoobly"];
		$status = "UserSubmitted";

		//delete all entries in the database for the assetsdebtsnwhash = hooblyGoobly
		//this will remove new posts that have to do with the same assetsdebtsnwhash data
		//so if a user shares results, then comes back in later and shares an updated result set, we only care about the latest one
		//if the original blog post made it out the door, fine, no big deal, if not, we'll use this one
		$stmt = $pdo->prepare('DELETE FROM UserNetWorthPosts WHERE assetsdebtsnwhash = :assetsdebtsnwhash');
		$stmt->execute(array(':assetsdebtsnwhash' => $assetsdebtsnwhash));

		//insert this new row of data for the post into the database
		$stmt = $pdo->prepare('INSERT INTO UserNetWorthPosts (firstname, message, assetsdebtsnwhash, status, modifieddt) VALUES (:firstname, :message, :assetsdebtsnwhash, :status, NOW())');
		$stmt->execute(array(':firstname' => $firstname, ':message' => $message, ':assetsdebtsnwhash' => $assetsdebtsnwhash, ':status' => $status));

		$userMessage = '<div><p class="text-success">Congratulations, your submission was successful.</p> If approved, your results will be posted as soon as possible.  If you recalculate your net worth before this post is published, this post will be automatically removed and you will need to share those new results instead.</div><div><p>&nbsp;</p></div><div><a href="/blog"><img src="/img/circle3.png" style="padding-right:10px;">Join discussions and see what others are up to now.</a></div>';

		//sending email
		$To = "nbnwsupport@nothingbutnetworth.com";
		$Subject = "New user blog post submitted";
		$Message = "Someone just posted blog results.";
		$From = "nbnwsupport@nothingbutnetworth.com";
		$Headers = array ('From' => $From, 'To' => $To, 'Subject' => $Subject);
		$SMTP = Mail::factory('smtp', array ('host' => $MailHost, 'auth' => true,
		'username' => $MailUser, 'password' => $MailPassword));
		$mail = $SMTP->send($To, $Headers, $Message);
		//if (PEAR::isError($mail)){
		//	echo($mail->getMessage());
		//} else {
		//	echo("Email Message sent!");
		//}

	} catch (PDOException $e) {
		$userMessage = '<p class="text-error">Sorry about that, but we just experienced a hiccup.</p> Please <a href="javascript:history.go(-1)">go back</a> in your browser and try it again or skip it for now and <a href="/blog">see what others are up to.</a>.';
	}

    $pdo = null;

  }


?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Net Worth Sharing Status</title>
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

        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

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
							<h2><a href="/blog"><img src="http://www.nothingbutnetworth.com/blog/wp-content/themes/atahualpa/images/nothingbutnetworthlogo.png" style="padding-right:10px;"></a>Sharing Status</h2>
						</div>

					</div>

				</div>

				<hr>

				<div class="row">

					<div class="span12">

						<div>
							<? echo $userMessage; ?>
						</div>
						<div>
							<p>&nbsp;</p>
						</div>

					</div>

				</div>

				<hr>

				<footer>
					<p class="pull-right"><a href="#">Back to top</a></p>
					<p><span class="muted">&copy; 2012 NothingButNetWorth.com</span> &middot; <a href="/blog/net-worth-only-net-worth/">About</a></p>
				</footer>

			</div>

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
