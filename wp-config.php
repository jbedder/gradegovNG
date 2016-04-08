<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'rategovng');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '*#w^2;PxM@:XW4omu,iWWJ[u.~{+Y,hfm*2_q1tdLg(Bh^jzz4R/pQ4L: ,KM?Go');
define('SECURE_AUTH_KEY',  'M:DMh<6,RF +Gv=`p.%H.i./>Nzmw`1iU?igMk]` kB36rMqz&$;xIWLE{(aMkK4');
define('LOGGED_IN_KEY',    'oC`4r71ce=]I(k 6]DR/=Ym+Dy,4x6~;_xMQsX7Q}:cD>~]v8I#Imj<KjN8FfIvh');
define('NONCE_KEY',        ',8LU:jU:79*:f}Kqk9o5SKq:?e NzgEW(9w[gUC@[`G4;qWHL{!,N,7Wz8j*|Btg');
define('AUTH_SALT',        'hSK>n.(QVVAB@h*F^*aGDyouCV6c0^q(ix<I.vR^JG)aoI7Zmmk$0(_fdFxD)U?4');
define('SECURE_AUTH_SALT', '4$CS(PBW(/S~LV %>p]e[jfj9)oW^C=^$`IteILW|(s:gAmhu}}dA%(~=.^Co${w');
define('LOGGED_IN_SALT',   '>F8O|X5h6|(MhyX$1;j7:hsC|Bs%LD3 .H_9Q<0*m,R7I*wDv3-!RiW]<iwz`O&H');
define('NONCE_SALT',       'S;GyT3TE?lVKwu|_q$*np2kh,)hzb3.`}xjOE58jv/[0wXad~#Sx>HtVzGk.p24#');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
