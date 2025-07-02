<?php
namespace JosephG\Roko\Infrastructure\WordPress\Security\I18n;

/**
 * FileSecurity strings collection.
 *
 * @package Roko
 * @subpackage Infrastructure
 * @subpackage WordPress
 * @subpackage Security
 * @subpackage I18n
 */
final class FileSecurityI18n {

	public static function description( string $key ): string {
		return self::stringsCollection()[ $key ];
	}

	public static function stringsCollection(): array {
		return array(
			'directoryListing'  => __( "Lets visitors see a list of your site's files. Hiding this keeps your stuff better protected.", 'roko' ),
			'wpDebug'           => __( "Shows technical messages to help fix issues. Turn it off when not troubleshooting so visitors don't see behind-the-scenes details.", 'roko' ),
			'editor'            => __( "Allows editing core site files right from your dashboard. It's safer to switch this off unless you're sure you need it.", 'roko' ),
			'dashboardInstalls' => __( 'Lets anyone with the right access install plugins or themes from the dashboard. Restrict this to prevent surprise changes.', 'roko' ),
			'backupExposed'     => __( 'Special keys can be used to back up or download your site. Keep these locked down so only you can access them.', 'roko' ),
			'sensitiveFiles'    => __( "These are files that could give away secrets or setup details. Keeping them private is best for your site's safety.", 'roko' ),
			'htAccessPerm'      => __( 'This file controls who can do what on your site. Make sure only trusted hands can edit it.', 'roko' ),
			'logsExposed'       => __( "Log files record what's happening behind the scenes. Keep them hidden so outsiders can't snoop on your site's activity.", 'roko' ),
			'phpExecUploads'    => __( 'Prevents running scripts in your uploads folder. Blocking this helps stop sneaky hacks through images or docs.', 'roko' ),
			'wpConfigPerm'      => __( "Your site's core settings file. Keep it tightly locked down so only you or trusted folks can make changes.", 'roko' ),
			'xmlrpc'            => __( "If you're not sure you need XML-RPC, it's usually best to switch it off and let the REST API handle things.", 'roko' ),
		);
	}
}
