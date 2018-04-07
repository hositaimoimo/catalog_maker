<?php

$dbname = 'anomalocaris_catamake';
$host = 'mysql2102.xserver.jp';
$charset = 'utf8';
$db_user = 'anomalocaris_cm';
$db_pass = 'utr1fr9z73';
$site_url = 'https://catamake.com';


// 本番環境
define( 'DSN', "mysql:dbname=$dbname;host=$host;charset=$charset" );
define( 'DB_USER', $db_user );
define( 'DB_PASSWORD', $db_pass );
define( 'SITE_URL', $site_url );
define( 'IMAGE_HEIGHT', 300 );
define( 'IMAGE_WIDTH', 300 );

// local環境
// define( 'DSN', "mysql:dbname=catalog_maker;host=localhost;charset=utf8" );
// define( 'DB_USER', 'root' );
// define( 'DB_PASSWORD', '' );
// define( 'SITE_URL', $site_url );

// twitter
define( 'CONSUMER_KEY', 'OfojYTaKTGiFMTzbuoDpcQmLi' );
define( 'CONSUMER_SECRET', 'qc8YijdmVvgVd6obVSNlaiUbmwK45nsR183PXVPu2saHsoy9WH' );
define( 'OAUTH_CALLBACK', 'https://catamake.com/callback.php' );
