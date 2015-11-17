<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Net Worth Share</title>
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
		<script type="text/javascript">
		 var RecaptchaOptions = {
			theme : 'white'
		 };
		 </script>
        <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
        <meta name="google-translate-customization" content="88694610275f2459-843fce1d10f83c04-ge949f1feb3d1e914-1a"></meta>
    </head>

    <body>

        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

		<form action="networthusersharepost.php" class="navbar-form" method="post">

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
							<h2><a href="http://www.nothingbutnetworth.com"><img src="/img/circle2.png" style="padding-right:10px;"></a>Share Your Net Worth Results With Others</h2>
						</div>
						<div>
							<h5 class="muted">Submit this as a public blog post and get feedback from others.</h5>
						</div>

					</div>

				</div>

				<hr>

				<div class="row">

					<div class="span12">

						<div>
							<label>What's your first name?</label>
							<h6 class="muted">You can leave it blank to be anonymous</h6>
							<input type="text" name="firstName" class="span2">
						</div>
						<div>
							<p>&nbsp;</p>
						</div>
						<div>
							<label>Anything specific you want to share?</label>
							<h6 class="muted">e.g. Do you think my net worth looks ok at my age?</h6>
							<input type="text" name="userMessage" class="span4">
						</div>
						<div>
							<p>&nbsp;</p>
						</div>
						<div>
							<label>Please show you're a human, not a computer.</label>
							<?php
							  require_once('recaptchalib.php');
							  $publickey = "6LchYdwSAAAAAGAwzL17EOHVna0e57XMbYBBkyT-";
							  echo recaptcha_get_html($publickey);
							?>
						</div>
						<div>
							<p>&nbsp;</p>
						</div>
						<div>
							<input type="Submit" class="btn btn-success" id="btn_sharepost" value="Share My Results">
						</div>
						<div>
							<p>&nbsp;</p>
						</div>
						<div>
							&nbsp;<a href="javascript:history.go(-1)">Nah, don't share right now</a>
						</div>

					</div>

				</div>

				<hr>

				<footer>
					<p class="pull-right"><a href="#">Back to top</a></p>
					<p><span class="muted">&copy; 2012 NothingButNetWorth.com</span> &middot; <a href="/blog/net-worth-only-net-worth/">About</a></p>
				</footer>

			</div>

			<input type="hidden" name="hooblyGoobly" value="<? echo $_POST["hooblyGoobly"]; ?>">

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
