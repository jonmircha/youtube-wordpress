<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'dogs');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'u[RenfOpAsu5E8blc{V7;~`3P<Qvs7i$X|8K,nCFYzhU|<=1kuxJL@/Q1:,|lg9&');
define('SECURE_AUTH_KEY',  '[-W{^NLZ-s@4;a-iou2NS%YV>Gm$!wl*)%iwi{n.LB|(i~#Dqw>H(D<_$Zw.:l#|');
define('LOGGED_IN_KEY',    '#qcE)% hKY|*[3: Zu*W }HsHRBL2o0+Y<{UD%lF=MVD*G&g>:6KYG4NK$H]j}V3');
define('NONCE_KEY',        '9dB5*3d-MzqV<mfnso(`1xlcu,)EvjTN+-2{N[yB#)WP.e-`GdVqZ++we2N t$G5');
define('AUTH_SALT',        ' c.3GRx+L4 CRD=IP+5W_cwH36i9C{|-9$B?YC-`vZflYS[{K$VX|+OxXuG-uwci');
define('SECURE_AUTH_SALT', 'vBW:Y&0%r@.|4{rRV_o#+]!gr_5jYoF .F=oRs(42r,<MQZTrxrU(/+wyKDrA#w#');
define('LOGGED_IN_SALT',   'jx:[OTof+{!Ii2!rk`m Lh~jSiir33?E^MI`j7$;!~3j/a`$y#jbBXhU0sJ`|zi-');
define('NONCE_SALT',       'Bn32p l|AoT7nkl7Z-[yNT/ZX4s!L|+c$LjDt3_!bcgT}2WDmjU2_3K+_Y Ly;V8');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'oufheruigei_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
