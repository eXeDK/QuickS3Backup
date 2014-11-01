<?php
	namespace QuickS3Backup;

	/**
	 * Class DatabaseExporter handles all database exports
	 * @package QuickS3Backup
	 */
	class DatabaseExporter {

		/**
		 * @param string $database  Name of the database to export
		 * @param string $user      Database user to use
		 * @param string $password  Password of the user
		 * @param string $dumpPath  Path where to dump the database
		 *
		 * @return string           Returns the $dumpPath
		 */
		public static function exportDatabase($database, $user, $password, $dumpPath) {
			exec('mysqldump --user=' . $user . ' --password=' . $password . ' ' . $database . ' > ' . $dumpPath);

			return $dumpPath;
		}
	}