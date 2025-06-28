<?php
// File: src/Infrastructure/WordPress/Database/Migrations/0.0.1.php

return array(
	'0.0.1' => function () {
		global $wpdb;

		// Ensure dbDelta() is available
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$charset_collate = $wpdb->get_charset_collate();

		// Fetch the DB version string (e.g. "10.4.17-MariaDB" or "8.0.23")
		$version_string = $wpdb->get_var( 'SELECT VERSION()' ) ?? '';

		// Detect MariaDB by checking for "mariadb" in the version string
		$is_maria = stripos( $version_string, 'mariadb' ) !== false;
		// Extract the numeric portion for MySQL comparison
		$mysql_ver     = preg_replace( '/[^0-9\.]/', '', $version_string );
		$supports_json = (
		( ! $is_maria && version_compare( $mysql_ver, '5.7.8', '>=' ) ) ||
		( $is_maria && version_compare( $mysql_ver, '10.2.7', '>=' ) )
		);

		$json_type = $supports_json ? 'JSON' : 'LONGTEXT';

		//
		// 1) wp_roko_events
		//
		$events_table = $wpdb->prefix . 'roko_events';
		$sql          = "
    CREATE TABLE {$events_table} (
      id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
      site_id       BIGINT UNSIGNED NOT NULL,
      integration   VARCHAR(100)     NOT NULL,
      event_type    VARCHAR(100)     NOT NULL,
      payload       {$json_type}     NOT NULL,
      occurred_at   DATETIME(6)      NOT NULL,
      sent_at       DATETIME(6)      NULL,
      PRIMARY KEY  (id),
      KEY site_event_idx (site_id, integration, event_type)
    ) {$charset_collate};
    ";
		dbDelta( $sql );

		//
		// 2) wp_roko_advice
		//
		$advice_table = $wpdb->prefix . 'roko_advice';
		$sql          = "
    CREATE TABLE {$advice_table} (
      id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
      site_id     BIGINT UNSIGNED NOT NULL,
      message     TEXT             NOT NULL,
      context     {$json_type}     NULL,
      severity    VARCHAR(20)      NOT NULL,
      created_at  DATETIME(6)      NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
      read_at     DATETIME(6)      NULL,
      PRIMARY KEY (id),
      KEY site_msg_idx (site_id, severity, created_at)
    ) {$charset_collate};
    ";
		dbDelta( $sql );

		//
		// 3) wp_roko_user_settings
		//
		$settings_table = $wpdb->prefix . 'roko_user_settings';
		$sql            = "
    CREATE TABLE {$settings_table} (
      user_id    BIGINT UNSIGNED NOT NULL,
      prefs      {$json_type}     NOT NULL,
      updated_at DATETIME(6)      NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
      PRIMARY KEY (user_id)
    ) {$charset_collate};
    ";
		dbDelta( $sql );
	},
);
