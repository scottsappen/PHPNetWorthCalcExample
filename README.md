# PHPNetWorthCalcExample
Simple net worth calculator written in PHP and integrated with WordPress, ReCaptcha and a 2nd standalone MySQL DB.

I did this for a number of reasons:
- I wanted to see why people love PHP so much
- I wanted to see how easy it was to leverage WordPress APIs and do something meaningful
- I wanted to see how easy it is to integrate a 2nd standalone MySQL database
- I wanted to do something non-trivial with JavaScript

In some ways, I wish I wasn't so curious about various tech because I could have spent those hours watching the tube. :)

cfg-files/dbinfo.inc.php - that's the access file for the 2nd MySQL database. If you're not careful, your website could be compromised at a level below public_html at which point your db access file woudl be compromised too...so you create it in a seperate folder with lower level chmod permissions. Your website hoster can give you more information.

public_html/dbinfo.inc.php - example of connecting to MySQL DB, creating a dataTable array, selecting data from DB, populating the dataTable array and json_encodeing that data

e.g. $jsonData = json_encode($dataTable);

public_html/zadmin/zadmin.php - example of retrieving previous stored result in MySQL DB and posting that as a blog post in WordPress DB

e.g. $post_id = wp_insert_post( $user_post, $wp_error );

