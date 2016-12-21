<?php

namespace Isotop\Cargo\Database;

class MySQL extends Abstract_Database {

	/**
	 * Bootstrap database.
	 */
	protected function bootstrap() {
		$this->create_table();
	}

	/**
	 * Get items returns all items that exists in the database.
	 *
	 * @return array
	 */
	public function all() {
		global $wpdb;

		$value = $wpdb->get_results( "SELECT id, data FROM `{$this->get_table()}`" ); // wpcs: unprepared SQL

		if ( empty( $value ) ) {
			return [];
		}

		return $value;
	}

	/**
	 * Clear all items in database.
	 *
	 * @return mixed
	 */
	public function clear() {
		global $wpdb;

		return $wpdb->query( "TRUNCATE TABLE `{$this->get_table()}`" ); // wpcs: unprepared SQL
	}

	/**
	 * Delete item from database.
	 *
	 * @param  int $id
	 *
	 * @return false
	 */
	public function delete( int $id ) {
		global $wpdb;

		return (bool) $wpdb->delete( $this->get_table(), ['id' => $id], ['%d'] );
	}

	/**
	 * Save item with data to the database.
	 *
	 * @param  string $data
	 * @param  string $error
	 *
	 * @return bool|int
	 */
	public function save( string $data, string $error = '' ) {
		global $wpdb;

		// Bail if not a JSON string.
		if ( ! cargo_is_json( $data ) ) {
			return false;
		}

		return $wpdb->insert( $this->get_table(), ['data' => $data, 'error' => $error, 'created_at' => current_time( 'mysql' )], ['%s', '%s', '%s'] );
	}

	/**
	 * Get table name.
	 *
	 * @return mixed
	 */
	protected function get_table() {
		if ( $table = $this->cargo->config( 'database.mysql.table' ) ) {
			return $table;
		}

		global $wpdb;

		return sprintf( '%scargo', $wpdb->prefix );
	}

	/**
	 * Create table if missing or not same version.
	 */
	protected function create_table() {
		if ( ! function_exists( 'get_site_option' ) ) {
			return;
		}

		$table_version     = 2;
		$installed_version = intval( get_site_option( '_cargo_table_version', 0 ) );

		if ( $installed_version !== $table_version ) {
			global $wpdb;

			$wpdb->query( "DROP TABLE IF EXISTS `{$this->get_table()}`" ); // wpcs: unprepared SQL

			$sql = sprintf(
				'CREATE TABLE %1$s (
					id int(11) unsigned NOT NULL AUTO_INCREMENT,
					data LONGTEXT NOT NULL,
					error LONGTEXT,
					created_at DATETIME NOT NULL,
					PRIMARY KEY  (id)
				) %2$s;',
				$this->get_table(),
				$GLOBALS['wpdb']->get_charset_collate()
			);

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );

			update_site_option( '_cargo_table_version', $table_version );
		}
	}
}
