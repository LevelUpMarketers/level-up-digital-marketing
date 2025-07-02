<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '~@AhoErqT=Yk3 EWeq]O2(N;!?8%wP)Fe`wQ9V2l#B7EiP?zPuKJ(_xfpy6}0SX-' );
define( 'SECURE_AUTH_KEY',   '?9,Cf)fq8oNj;J:)V|`1t}}`gRXS}0:tkgnGrI.0KZ b!ytNQbkcbsc7A?>xX7$p' );
define( 'LOGGED_IN_KEY',     '|IH0o:APqo<?qm=KQRlt7~}czGK3=QS,<;U8T%/en4ob`+{n[8pxmf?RY6|o5tfx' );
define( 'NONCE_KEY',         ')fD{)9/07gb]gk@9^@ng&)t)mQ,+JX4BlRCH>)t,O-7K~BC1IANT@x |AT89Ja8.' );
define( 'AUTH_SALT',         'm8I`yiP#n|(+(.G14__)`RhG9 qB/Ud*}#FfoWzXg2Q(D5F@;T@H|M<k_tupq<q/' );
define( 'SECURE_AUTH_SALT',  'U/ Yq!:r<f|uoy(*&E[QI-+xQ%S8>a=wlQw^.a+a`=@krZO]CYUvM~[=XMb2Y Ap' );
define( 'LOGGED_IN_SALT',    'Np>uXT[q|5LDe>]XhOZ#h+mPPMr0tv$`|g!!X:RS]Ia,i!kI!@T<217`J-1c6e,/' );
define( 'NONCE_SALT',        'ta<UeEAr4Gd}!Ce.T)p3(}j(md)(`_r&;E<M}+qP}>o9oe&> kS7hl=VTnSl`u8w' );
define( 'WP_CACHE_KEY_SALT', 'R.l0TG6@bGYL!3bCB%#%05S#Gu!1rcUiaxr?x)+$jCCQ}#$<>>$Ygbi2a]aUIrk ' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_9e73ifu2ty_';


/* Add any custom values between this line and the "stop editing" line. */



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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
