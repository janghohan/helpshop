<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'helpshop' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'autoset' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '*aPt5%>BAqInF^9OA~jC-00Fb$(pctvNK9d~Qe7L fU7l0!{*`XZ`Rw6S3a[Qe6`' );
define( 'SECURE_AUTH_KEY',  'o.lTDH9v#y#UWaDuwCT-7cF,}{.&B*&A$$>d&or]^34KC;[Q>b(U_/])m?iK>.5k' );
define( 'LOGGED_IN_KEY',    'kfogw} 5:f/?Is arj]%Q;Nqy)[[}iy.O{!{:(,X(@oe1^A8o@We||1O_-nb8<YT' );
define( 'NONCE_KEY',        'oJGJhxlSI`C`aO.zI+Xand.i5h+u7OL T(YM-|J/:QNP[=I53BNTkqO6L1;KhJtV' );
define( 'AUTH_SALT',        'tVONwu|S5yrQM)lEb6W>SWk1])A9YWaNCL#==:A=BIe3m}=1ji$:dmxF4O7 $hAu' );
define( 'SECURE_AUTH_SALT', '8 `]^ M)cku>v|Mz*0p#em|Esm/DcPJ(}PZw`-w.P#iSRQ04|=Ka,PZAKeaqw@Xb' );
define( 'LOGGED_IN_SALT',   'C9y8&w%#E+BF3Q!CI]]Pl,;-=cV8FnUpaCwy+Y=BTRQFK*@>8oC5WsLQ#%!;b-?>' );
define( 'NONCE_SALT',       'ChoToV;,U:b9<zFlx30^r<c{O2NB~E|S4w,5P%pZv^J~:uUL6;QbF#Ht$)Aheb-t' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
