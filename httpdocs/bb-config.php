<?php
    /** 
     * The base configurations of bbPress.
     *
     * This file has the following configurations: MySQL settings, Table Prefix,
     * Secret Keys and bbPress Language. You can get the MySQL settings from your
     * web host.
     *
     * This file is used by the installer during installation.
     *
     * @package bbPress
     */
    
    // ** MySQL settings - You can get this info from your web host ** //
    /** The name of the database for bbPress */
    define( 'BBDB_NAME', 'mydomains_mysql767a3777065ed229924dfe1bd6374bc4' );
    
    /** MySQL database username */
    define( 'BBDB_USER', 'usrf8c0a1a479f9' );
    
    /** MySQL database password */
    define( 'BBDB_PASSWORD', 'carolinepratt' );
    
    /** MySQL hostname */
    define( 'BBDB_HOST', 'localhost' );
    
    /** Database Charset to use in creating database tables. */
    define( 'BBDB_CHARSET', 'utf8' );
    
    /** The Database Collate type. Don't change this if in doubt. */
    define( 'BBDB_COLLATE', '' );
    
    /**#@+
     * Authentication Unique Keys.
     *
     * Change these to different unique phrases!
     * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/bbpress/ WordPress.org secret-key service}
     *
     * @since 1.0
     */
    define( 'BB_AUTH_KEY', 'AUTH_KEY' );
    define( 'BB_SECURE_AUTH_KEY', 'SECURE_AUTH_KEY' );
    define( 'BB_LOGGED_IN_KEY', 'LOGGED_IN_KEY' );
    define( 'BB_NONCE_KEY', 'NONCE_KEY' );
    /**#@-*/
    
    /**
     * bbPress Database Table prefix.
     *
     * You can have multiple installations in one database if you give each a unique
     * prefix. Only numbers, letters, and underscores please!
     */
    $bb_table_prefix = 'wp_bb_';
    
    /**
     * bbPress Localized Language, defaults to English.
     *
     * Change this to localize bbPress. A corresponding MO file for the chosen
     * language must be installed to a directory called "my-languages" in the root
     * directory of bbPress. For example, install de.mo to "my-languages" and set
     * BB_LANG to 'de' to enable German language support.
     */
    define( 'BB_LANG', 'en_US' );
    $bb->custom_user_table = 'wp_users';
    $bb->custom_user_meta_table = 'wp_usermeta';
    
    $bb->uri = 'http://facesofolympus.org/wp-content/plugins/buddypress/bp-forums/bbpress/';
    $bb->name = 'Faces of Olympus Forums';
    
    define('WP_AUTH_COOKIE_VERSION', 2);
    
    ?>