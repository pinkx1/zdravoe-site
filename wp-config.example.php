<?php
/**
 * Example configuration for WordPress.
 *
 * Copy this file to "wp-config.php" and fill in
 * the values for your environment (local, staging, production).
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'your_database_name_here' );

/** Database username */
define( 'DB_USER', 'your_database_user_here' );

/** Database password */
define( 'DB_PASSWORD', 'your_database_password_here' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Generate these using:
 * https://api.wordpress.org/secret-key/1.1/salt/
 *
 * Copy/paste fresh values for each environment.
 */
define( 'AUTH_KEY',          'put_your_unique_phrase_here' );
define( 'SECURE_AUTH_KEY',   'put_your_unique_phrase_here' );
define( 'LOGGED_IN_KEY',     'put_your_unique_phrase_here' );
define( 'NONCE_KEY',         'put_your_unique_phrase_here' );
define( 'AUTH_SALT',         'put_your_unique_phrase_here' );
define( 'SECURE_AUTH_SALT',  'put_your_unique_phrase_here' );
define( 'LOGGED_IN_SALT',    'put_your_unique_phrase_here' );
define( 'NONCE_SALT',        'put_your_unique_phrase_here' );
define( 'WP_CACHE_KEY_SALT', 'put_your_unique_phrase_here' );
/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/* Add any custom values between this line and the "stop editing" line. */

// Contact form recipient for theme
define( 'ZDRAVOE_CONTACT_EMAIL', 'you@example.com' );

// Optional: force site URLs (usually for production)
// define( 'WP_HOME', 'https://example.com' );
// define( 'WP_SITEURL', 'https://example.com' );

/**
 * For developers: WordPress debugging mode.
 *
 * Set to true on local environments during development.
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

// Environment type: 'local', 'staging', 'production', etc.
define( 'WP_ENVIRONMENT_TYPE', 'production' );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

