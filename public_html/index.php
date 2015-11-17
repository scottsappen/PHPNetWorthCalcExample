<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Nothing But Net Worth</title>
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

		//this is the default user message if we come into index.php for the first time
		$userMessage = 'Been here before? <a href="/index.php?bhb=yes">Fill in the form for me</a>.';

		//By default, gender set to Male
		//The age and residence are selected in jquery after the document has loaded
		$sexcheckedmale = "checked";
		$sexcheckedfemale = "";

		//post variables processing
		$beenherebefore = $_GET["bhb"];

		if ($beenherebefore == 'yes') {
			setlocale(LC_MONETARY, "en_US");
			$ipaddress = $_SERVER["REMOTE_ADDR"];

			//database connection
			include("../cfg-files/dbinfo.inc.php");
			try {
				$pdo = new PDO('mysql:dbname=nothinn5_networth;host=localhost', $username, $password);
				$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$stmt = $pdo->prepare('SELECT assetsstocks, assetscash, assetsretirement, assetsresidence, assetsautos, assetsother, debtsmortgage, debtsheloc, debtsstudentloans, debtscreditcards, debtsautoloans, debtsother, age, sex, residence FROM AssetsDebts WHERE ipaddress = :ipaddress');
				$stmt->execute(array(':ipaddress' => $ipaddress));
				$userMessage = '<p class="text-error">No results were found.  Please start over by filling in this form.</p>';
				foreach ($stmt as $row) {
					$assetsstocks = $row["assetsstocks"];
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
						$sexcheckedmale = "checked";
						$sexcheckedfemale = "";
					} else {
						$sexcheckedmale = "";
						$sexcheckedfemale = "checked";
					}
					$residence = $row['residence'];
					$userMessage = '<p class="text-warning">Some results were found, but you should review and revise them as necessary.</p>';
					break;
				}

			} catch (PDOException $e) {
				$userMessage = '<p class="text-error">Sorry about that, but the calculator experienced a hiccup.</p>  Please <a href="/index.php?bhb=yes">try once more</a> or <a href="/">start over</a>.';
			}
		}

    	?>

        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <form action="networthcalc.php" class="navbar-form" method="post">

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
							<h2><a href="http://www.nothingbutnetworth.com"><img src="http://www.nothingbutnetworth.com/blog/wp-content/themes/atahualpa/images/nothingbutnetworthlogo.png" style="padding-right:10px;"></a>Net Worth Calculator</h2>
						</div>
						<div>
							<h5 class="muted">Calculate your net worth using this simple and free net worth calculator!</h5>
							<p class="muted"><small>Or you can go offline and use Excel to calculate your net worth using <a href="/NothingButNetWorthNetWorthCalculator.xlsx">this starter</a>.</small></p>
						</div>

					</div>

				</div>

				<hr>

				<div class="row" style="text-align:center;">
					<div class="span4">
						<a href="#" onclick="return false;"><img src="/img/circle1.png" id="img_circle1"></a>
						<h4>Calculate your net worth</h4>
						<p><? echo $userMessage; ?></p>
					</div>
					<div class="span4">
						<img src="/img/circle2.png">
						<h4>Share your results</h4>
						<p><a data-toggle="modal" href="#myModalSharingExplained">What does that mean?</a></p>
				   </div>
					<div class="span4">
						<a href="/blog"><img src="/img/circle3.png"></a>
						<h4>Join in on discussions</h4>
						<p><a href="/blog">Take me there now.</a></p>
					</div>
				</div>

				<hr>

				<div class="row">

					<div class="span4">
						<div>
							<h2>Your assets</h2>
						</div>
						<div class="input-prepend input-append">
							<label id="lbl_stocksandbonds">Stocks and bonds</label>
							<span class="add-on">$</span>
							<input type="number" name="assetsstocks" id="assetsstocks" class="span2 assets" min="0" max="1000000000" value="<? echo $assetsstocks; ?>">
							<span class="add-on">.00</span>
						</div>
						<div class="input-prepend input-append">
							<label>Cash</label>
							<span class="add-on">$</span>
							<input type="number" name="assetscash" class="span2 assets" min="0" max="1000000000" value="<? echo $assetscash; ?>">
							<span class="add-on">.00</span>
						</div>
						<div class="input-prepend input-append">
							<label>Retirement accounts</label>
							<span class="add-on">$</span>
							<input type="number" name="assetsretirement" class="span2 assets" min="0" max="1000000000" value="<? echo $assetsretirement; ?>">
							<span class="add-on">.00</span>
						</div>
						<div class="input-prepend input-append">
							<label>Value of residence(s)</label>
							<span class="add-on">$</span>
							<input type="number" name="assetsresidence" class="span2 assets" min="0" max="1000000000" value="<? echo $assetsresidence; ?>">
							<span class="add-on">.00</span>
						</div>
						<div class="input-prepend input-append">
							<label>Value of automobile(s)</label>
							<span class="add-on">$</span>
							<input type="number" name="assetsautos" class="span2 assets" min="0" max="1000000000" value="<? echo $assetsautos; ?>">
							<span class="add-on">.00</span>
						</div>
						<div class="input-prepend input-append">
							<label>Other</label>
							<span class="add-on">$</span>
							<input type="number" name="assetsother" class="span2 assets" min="0" max="1000000000" value="<? echo $assetsother; ?>">
							<span class="add-on">.00</span>
						</div>
						<div>
							<h4 class="text-success"><span id="lbl_totalassets"></span></h4>
						</div>
					</div>

					<div class="span4">
						<div>
							<h2>Your debts</h2>
						</div>
						<div class="input-prepend input-append">
							<label>Mortgage(s)</label>
							<span class="add-on">$</span>
							<input type="number" name="debtsmortgage" class="span2 debts" min="0" max="1000000000" value="<? echo $debtsmortgage; ?>">
							<span class="add-on">.00</span>
						</div>
						<div class="input-prepend input-append">
							<label>Home-equity loan</label>
							<span class="add-on">$</span>
							<input type="number" name="debtsheloc" class="span2 debts" min="0" max="1000000000" value="<? echo $debtsheloc; ?>">
							<span class="add-on">.00</span>
						</div>
						<div class="input-prepend input-append">
							<label>Student loan(s)</label>
							<span class="add-on">$</span>
							<input type="number" name="debtsstudentloans" class="span2 debts" min="0" max="1000000000" value="<? echo $debtsstudentloans; ?>">
							<span class="add-on">.00</span>
						</div>
						<div class="input-prepend input-append">
							<label>Credit cards</label>
							<span class="add-on">$</span>
							<input type="number" name="debtscreditcards" class="span2 debts" min="0" max="1000000000" value="<? echo $debtscreditcards; ?>">
							<span class="add-on">.00</span>
						</div>
						<div class="input-prepend input-append">
							<label>Automobile loan(s)</label>
							<span class="add-on">$</span>
							<input type="number" name="debtsautoloans" class="span2 debts" min="0" max="1000000000" value="<? echo $debtsautoloans; ?>">
							<span class="add-on">.00</span>
						</div>
						<div class="input-prepend input-append">
							<label>Other</label>
							<span class="add-on">$</span>
							<input type="number" name="debtsother" class="span2 debts" min="0" max="1000000000" value="<? echo $debtsother; ?>">
							<span class="add-on">.00</span>
						</div>
						<div>
							<h4 class="text-error"><span id="lbl_totaldebts"></label></h4>
						</div>
					</div>

					<div class="span4">
						<div>
							<h2>About you</h2>
						</div>
						<div>
							<label>Your age</label>
							<select name="age" id="age">
								<option>9</option>
								<option>10</option>
								<option>11</option>
								<option>12</option>
								<option>13</option>
								<option>14</option>
								<option>15</option>
								<option>16</option>
								<option>17</option>
								<option>18</option>
								<option>19</option>
								<option>20</option>
								<option>21</option>
								<option>22</option>
								<option>23</option>
								<option>24</option>
								<option>25</option>
								<option>26</option>
								<option>27</option>
								<option>28</option>
								<option>29</option>
								<option>30</option>
								<option>31</option>
								<option>32</option>
								<option>33</option>
								<option>34</option>
								<option>35</option>
								<option>36</option>
								<option>37</option>
								<option>38</option>
								<option>39</option>
								<option>40</option>
								<option>41</option>
								<option>42</option>
								<option>43</option>
								<option>44</option>
								<option>45</option>
								<option>46</option>
								<option>47</option>
								<option>48</option>
								<option>49</option>
								<option>50</option>
								<option>51</option>
								<option>52</option>
								<option>53</option>
								<option>54</option>
								<option>55</option>
								<option>56</option>
								<option>57</option>
								<option>58</option>
								<option>59</option>
								<option>60</option>
								<option>61</option>
								<option>62</option>
								<option>63</option>
								<option>64</option>
								<option>65</option>
								<option>66</option>
								<option>67</option>
								<option>68</option>
								<option>69</option>
								<option>70</option>
								<option>71</option>
								<option>72</option>
								<option>73</option>
								<option>74</option>
								<option>75</option>
								<option>76</option>
								<option>77</option>
								<option>78</option>
								<option>79</option>
								<option>80</option>
								<option>81</option>
								<option>82</option>
								<option>83</option>
								<option>84</option>
								<option>85</option>
								<option>86</option>
								<option>87</option>
								<option>88</option>
								<option>89</option>
								<option>90</option>
								<option>91</option>
								<option>92</option>
								<option>93</option>
								<option>94</option>
								<option>95</option>
								<option>96</option>
								<option>97</option>
								<option>98</option>
								<option>99</option>
							</select>
						</div>
						<div>
							<p>&nbsp;</p>
						</div>
						<div>
							<label class="radio">
							  <input type="radio" name="sex" id="optionsRadios1" value="M" <? echo $sexcheckedmale; ?>>
							  Male
							</label>
							<label class="radio">
							  <input type="radio" name="sex" id="optionsRadios2" value="F" <? echo $sexcheckedfemale; ?>>
							  Female
							</label>
						</div>
						<div>
							<p>&nbsp;</p>
						</div>
						<div>
							<label>Country of residence</label>
							<select name="residence" id="residence">
								<option value="United States">United States</option>
								<option value="United Kingdom">United Kingdom</option>
								<option value="Afghanistan">Afghanistan</option>
								<option value="Albania">Albania</option>
								<option value="Algeria">Algeria</option>
								<option value="American Samoa">American Samoa</option>
								<option value="Andorra">Andorra</option>
								<option value="Angola">Angola</option>
								<option value="Anguilla">Anguilla</option>
								<option value="Antarctica">Antarctica</option>
								<option value="Antigua and Barbuda">Antigua and Barbuda</option>
								<option value="Argentina">Argentina</option>
								<option value="Armenia">Armenia</option>
								<option value="Aruba">Aruba</option>
								<option value="Australia">Australia</option>
								<option value="Austria">Austria</option>
								<option value="Azerbaijan">Azerbaijan</option>
								<option value="Bahamas">Bahamas</option>
								<option value="Bahrain">Bahrain</option>
								<option value="Bangladesh">Bangladesh</option>
								<option value="Barbados">Barbados</option>
								<option value="Belarus">Belarus</option>
								<option value="Belgium">Belgium</option>
								<option value="Belize">Belize</option>
								<option value="Benin">Benin</option>
								<option value="Bermuda">Bermuda</option>
								<option value="Bhutan">Bhutan</option>
								<option value="Bolivia">Bolivia</option>
								<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
								<option value="Botswana">Botswana</option>
								<option value="Bouvet Island">Bouvet Island</option>
								<option value="Brazil">Brazil</option>
								<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
								<option value="Brunei Darussalam">Brunei Darussalam</option>
								<option value="Bulgaria">Bulgaria</option>
								<option value="Burkina Faso">Burkina Faso</option>
								<option value="Burundi">Burundi</option>
								<option value="Cambodia">Cambodia</option>
								<option value="Cameroon">Cameroon</option>
								<option value="Canada">Canada</option>
								<option value="Cape Verde">Cape Verde</option>
								<option value="Cayman Islands">Cayman Islands</option>
								<option value="Central African Republic">Central African Republic</option>
								<option value="Chad">Chad</option>
								<option value="Chile">Chile</option>
								<option value="China">China</option>
								<option value="Christmas Island">Christmas Island</option>
								<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
								<option value="Colombia">Colombia</option>
								<option value="Comoros">Comoros</option>
								<option value="Congo">Congo</option>
								<option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
								<option value="Cook Islands">Cook Islands</option>
								<option value="Costa Rica">Costa Rica</option>
								<option value="Cote D'ivoire">Cote D'ivoire</option>
								<option value="Croatia">Croatia</option>
								<option value="Cuba">Cuba</option>
								<option value="Cyprus">Cyprus</option>
								<option value="Czech Republic">Czech Republic</option>
								<option value="Denmark">Denmark</option>
								<option value="Djibouti">Djibouti</option>
								<option value="Dominica">Dominica</option>
								<option value="Dominican Republic">Dominican Republic</option>
								<option value="Ecuador">Ecuador</option>
								<option value="Egypt">Egypt</option>
								<option value="El Salvador">El Salvador</option>
								<option value="Equatorial Guinea">Equatorial Guinea</option>
								<option value="Eritrea">Eritrea</option>
								<option value="Estonia">Estonia</option>
								<option value="Ethiopia">Ethiopia</option>
								<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
								<option value="Faroe Islands">Faroe Islands</option>
								<option value="Fiji">Fiji</option>
								<option value="Finland">Finland</option>
								<option value="France">France</option>
								<option value="French Guiana">French Guiana</option>
								<option value="French Polynesia">French Polynesia</option>
								<option value="French Southern Territories">French Southern Territories</option>
								<option value="Gabon">Gabon</option>
								<option value="Gambia">Gambia</option>
								<option value="Georgia">Georgia</option>
								<option value="Germany">Germany</option>
								<option value="Ghana">Ghana</option>
								<option value="Gibraltar">Gibraltar</option>
								<option value="Greece">Greece</option>
								<option value="Greenland">Greenland</option>
								<option value="Grenada">Grenada</option>
								<option value="Guadeloupe">Guadeloupe</option>
								<option value="Guam">Guam</option>
								<option value="Guatemala">Guatemala</option>
								<option value="Guinea">Guinea</option>
								<option value="Guinea-bissau">Guinea-bissau</option>
								<option value="Guyana">Guyana</option>
								<option value="Haiti">Haiti</option>
								<option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
								<option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
								<option value="Honduras">Honduras</option>
								<option value="Hong Kong">Hong Kong</option>
								<option value="Hungary">Hungary</option>
								<option value="Iceland">Iceland</option>
								<option value="India">India</option>
								<option value="Indonesia">Indonesia</option>
								<option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
								<option value="Iraq">Iraq</option>
								<option value="Ireland">Ireland</option>
								<option value="Israel">Israel</option>
								<option value="Italy">Italy</option>
								<option value="Jamaica">Jamaica</option>
								<option value="Japan">Japan</option>
								<option value="Jordan">Jordan</option>
								<option value="Kazakhstan">Kazakhstan</option>
								<option value="Kenya">Kenya</option>
								<option value="Kiribati">Kiribati</option>
								<option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
								<option value="Korea, Republic of">Korea, Republic of</option>
								<option value="Kuwait">Kuwait</option>
								<option value="Kyrgyzstan">Kyrgyzstan</option>
								<option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
								<option value="Latvia">Latvia</option>
								<option value="Lebanon">Lebanon</option>
								<option value="Lesotho">Lesotho</option>
								<option value="Liberia">Liberia</option>
								<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
								<option value="Liechtenstein">Liechtenstein</option>
								<option value="Lithuania">Lithuania</option>
								<option value="Luxembourg">Luxembourg</option>
								<option value="Macao">Macao</option>
								<option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
								<option value="Madagascar">Madagascar</option>
								<option value="Malawi">Malawi</option>
								<option value="Malaysia">Malaysia</option>
								<option value="Maldives">Maldives</option>
								<option value="Mali">Mali</option>
								<option value="Malta">Malta</option>
								<option value="Marshall Islands">Marshall Islands</option>
								<option value="Martinique">Martinique</option>
								<option value="Mauritania">Mauritania</option>
								<option value="Mauritius">Mauritius</option>
								<option value="Mayotte">Mayotte</option>
								<option value="Mexico">Mexico</option>
								<option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
								<option value="Moldova, Republic of">Moldova, Republic of</option>
								<option value="Monaco">Monaco</option>
								<option value="Mongolia">Mongolia</option>
								<option value="Montserrat">Montserrat</option>
								<option value="Morocco">Morocco</option>
								<option value="Mozambique">Mozambique</option>
								<option value="Myanmar">Myanmar</option>
								<option value="Namibia">Namibia</option>
								<option value="Nauru">Nauru</option>
								<option value="Nepal">Nepal</option>
								<option value="Netherlands">Netherlands</option>
								<option value="Netherlands Antilles">Netherlands Antilles</option>
								<option value="New Caledonia">New Caledonia</option>
								<option value="New Zealand">New Zealand</option>
								<option value="Nicaragua">Nicaragua</option>
								<option value="Niger">Niger</option>
								<option value="Nigeria">Nigeria</option>
								<option value="Niue">Niue</option>
								<option value="Norfolk Island">Norfolk Island</option>
								<option value="Northern Mariana Islands">Northern Mariana Islands</option>
								<option value="Norway">Norway</option>
								<option value="Oman">Oman</option>
								<option value="Pakistan">Pakistan</option>
								<option value="Palau">Palau</option>
								<option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
								<option value="Panama">Panama</option>
								<option value="Papua New Guinea">Papua New Guinea</option>
								<option value="Paraguay">Paraguay</option>
								<option value="Peru">Peru</option>
								<option value="Philippines">Philippines</option>
								<option value="Pitcairn">Pitcairn</option>
								<option value="Poland">Poland</option>
								<option value="Portugal">Portugal</option>
								<option value="Puerto Rico">Puerto Rico</option>
								<option value="Qatar">Qatar</option>
								<option value="Reunion">Reunion</option>
								<option value="Romania">Romania</option>
								<option value="Russian Federation">Russian Federation</option>
								<option value="Rwanda">Rwanda</option>
								<option value="Saint Helena">Saint Helena</option>
								<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
								<option value="Saint Lucia">Saint Lucia</option>
								<option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
								<option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
								<option value="Samoa">Samoa</option>
								<option value="San Marino">San Marino</option>
								<option value="Sao Tome and Principe">Sao Tome and Principe</option>
								<option value="Saudi Arabia">Saudi Arabia</option>
								<option value="Senegal">Senegal</option>
								<option value="Serbia and Montenegro">Serbia and Montenegro</option>
								<option value="Seychelles">Seychelles</option>
								<option value="Sierra Leone">Sierra Leone</option>
								<option value="Singapore">Singapore</option>
								<option value="Slovakia">Slovakia</option>
								<option value="Slovenia">Slovenia</option>
								<option value="Solomon Islands">Solomon Islands</option>
								<option value="Somalia">Somalia</option>
								<option value="South Africa">South Africa</option>
								<option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
								<option value="Spain">Spain</option>
								<option value="Sri Lanka">Sri Lanka</option>
								<option value="Sudan">Sudan</option>
								<option value="Suriname">Suriname</option>
								<option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
								<option value="Swaziland">Swaziland</option>
								<option value="Sweden">Sweden</option>
								<option value="Switzerland">Switzerland</option>
								<option value="Syrian Arab Republic">Syrian Arab Republic</option>
								<option value="Taiwan, Province of China">Taiwan, Province of China</option>
								<option value="Tajikistan">Tajikistan</option>
								<option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
								<option value="Thailand">Thailand</option>
								<option value="Timor-leste">Timor-leste</option>
								<option value="Togo">Togo</option>
								<option value="Tokelau">Tokelau</option>
								<option value="Tonga">Tonga</option>
								<option value="Trinidad and Tobago">Trinidad and Tobago</option>
								<option value="Tunisia">Tunisia</option>
								<option value="Turkey">Turkey</option>
								<option value="Turkmenistan">Turkmenistan</option>
								<option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
								<option value="Tuvalu">Tuvalu</option>
								<option value="Uganda">Uganda</option>
								<option value="Ukraine">Ukraine</option>
								<option value="United Arab Emirates">United Arab Emirates</option>
								<option value="United Kingdom">United Kingdom</option>
								<option value="United States">United States</option>
								<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
								<option value="Uruguay">Uruguay</option>
								<option value="Uzbekistan">Uzbekistan</option>
								<option value="Vanuatu">Vanuatu</option>
								<option value="Venezuela">Venezuela</option>
								<option value="Viet Nam">Viet Nam</option>
								<option value="Virgin Islands, British">Virgin Islands, British</option>
								<option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
								<option value="Wallis and Futuna">Wallis and Futuna</option>
								<option value="Western Sahara">Western Sahara</option>
								<option value="Yemen">Yemen</option>
								<option value="Zambia">Zambia</option>
								<option value="Zimbabwe">Zimbabwe</option>
							</select>
						</div>
						<div>
							<p>&nbsp;</p>
						</div>
						<div>
							<input type="Submit" class="btn btn-success" id="btn_calculate" value="Calculate My Net Worth">
						</div>
						<div>
							<p>&nbsp;</p>
						</div>
						<div>
							<a type="btn btn-link" href="/">Reset and start over</a>
						</div>
						<div>
							<p>&nbsp;</p>
						</div>
						<div id="div_progress_calculate">
		                </div>
					</div>

				</div>

				<div class="row">

					<div class="span12">


						<div>
							<button type="button" class="btn-link" data-toggle="collapse" data-target="#div_collapseaboutcalc">
							Tell me a little more about this site</button>
						</div>

						<div class="collapse" id="div_collapseaboutcalc">
							<p></p>
							<p>
							This site is uniquely designed around the topic of net worth.
							It helps you figure out what your net worth is and helps you connect with others to
							increase your net worth.  We're not talking about your net worth from a psychological
							perspective (although we may have a lot of pats on the back and good feelings here),
							we're talking about purely the financial kind (Assets - Debts = Net Worth).  Net worth
							is an important part of your life and is often a pretty good indicator of your overall
							financial health.
							</p>
							<p>
							This net worth calculator will calculate your net worth and help you determine what spectrum you're
							in so you can improve your net worth.  Once you calculate your net worth, you'll be able
							to see some quick comparisons of your net worth against others that have also performed their
							net worth calculations.
							</p>
							<p>
							Go into the discussions area after you've calculated your net worth and understand how
							it calculates your net worth.  Inside the discussions area, you'll find like minded
							people who have net worths larger and smaller than your own.  Those with greater net worth
							will be able to help you as you will be able to help those aspiring to reach your level.
							</p>
							<p>
							But most important of all, have fun and hopefully you'll get something out of it!
							</p>
						</div>

					</div>

				</div>

				<hr>

				<footer>
					<p class="pull-right"><a href="#">Back to top</a></p>
					<p><span class="muted">&copy; 2012 NothingButNetWorth.com</span> &middot; <a href="/blog/net-worth-only-net-worth/">About</a></p>
				</footer>

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

        </form>

        <script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.8.3.min.js"><\/script>')</script>

        <script src="/js/vendor/bootstrap.min.js"></script>

		<script>
			$(document).ready(function(){
				$("#img_circle1").click(function () {
					$('html, body').animate({scrollTop: $("#lbl_stocksandbonds").offset().top}, 1000);
					$("#assetsstocks").focus();
				});
				$(".assets").each(function() {
					$(this).change(function(){
						calculateSumOfAssets();
					});
				});
				$(".debts").each(function() {
					$(this).change(function(){
						calculateSumOfDebts();
					});
				});
				$("#btn_calculate").click(function () {
					$("#div_progress_calculate").html("<div class='progress progress-striped active' id='div_progress_calculate'><div class='bar' style='width: 100%'></div></div>");
				});
				if ("<? echo ($age); ?>" != "") {
					$("#age").val( "<? echo ($age); ?>" ).attr('selected',true);
				} else {
					$("#age").val( "40" ).attr('selected',true);
				}
				if ("<? echo ($residence); ?>" != "") {
					$("#residence").val( "<? echo ($residence); ?>" ).attr('selected',true);
				}
			});
			function calculateSumOfAssets() {
				var sum = 0;
				$(".assets").each(function() {
					if(!isNaN(this.value) && this.value.length!=0 && this.value > 0 && this.value < 1000000000) {
						sum += parseFloat(this.value);
					} else {
						this.value = "";
					}
				});
				$("#lbl_totalassets").html("Total Assets $" + sum.toFixed(0));
			}
			function calculateSumOfDebts() {
				var sum = 0;
				$(".debts").each(function() {
					if(!isNaN(this.value) && this.value.length!=0 && this.value > 0 && this.value < 1000000000) {
						sum += parseFloat(this.value);
					} else {
						this.value = "";
					}
				});
				$("#lbl_totaldebts").html("Total Debts $" + sum.toFixed(0));
			}
			<?
			if ($beenherebefore == 'yes') {
				echo "calculateSumOfAssets();";
				echo "calculateSumOfDebts();";
			}
			?>
		</script>

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
