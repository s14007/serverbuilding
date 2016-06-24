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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 's14007');

/** MySQL database password */
define('DB_PASSWORD', 'hoge1Fuga*');

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
define('AUTH_KEY',         '`0gI{}Bw ${~z*lrbFKqmii{d}=A9Yv%RJ5a4%Pj$VVuJyy|!;yn{x0NRYogsFx ');
define('SECURE_AUTH_KEY',  '~=9=fDu;,1.dz,iW0*4WKx>{jU_<[0R8e/c+zm&5k#p}(.ApZgxalmQ(d&6j/et2');
define('LOGGED_IN_KEY',    '}I19-v5n+MaL;&J2&:GeJWtSOud>[aT6F1vjZ,ZzlS[m_F([&fBZ7+q|gd/_K`3N');
define('NONCE_KEY',        'cAy`h=FhaCXL9T72ZKxc<!v0x@{T&f;P`GUxY28 0#M9HH;%vGx)(-ZbF^@?;lMu');
define('AUTH_SALT',        'V_|&s^~1<5w`>@0X9&[8ejtmEQ};a6`QJ|z+2iXtI@2Q&Ry#Yp3Ae`=h~+ZYPOA|');
define('SECURE_AUTH_SALT', 'x*mDsHK4:NbW13.E{y+dK;B!XrZzG8*Dp@Qh_+;_BM}DbpbS?Dk:U35wwWpssFF>');
define('LOGGED_IN_SALT',   '*80*IHDAmfgN&]iI_P<xP(s`x-ieLDO2h`6[F^C3Utj%qWm(lqFPJQ}5|Zl|oW3,');
define('NONCE_SALT',       'im`bw/d),*t)gl:30I7dGs/[N|gPdi~Z2L_CZP orMb$r~,Yr!U nhph|j*3x^A}');

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
