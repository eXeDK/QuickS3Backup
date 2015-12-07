<?php
	namespace QuickS3Backup;

	/**
	 * Class Zipper zips a file or folder for the uploader
	 * @package QuickS3Backup
	 */
	class Zipper {

		/**
		 * @param string $content   Name of the file/folder to zip
		 * @param string $dumpPath  Path where to dump the database
		 *
		 * @return string           Returns the $dumpPath
		 */
		public static function zip($content, $dumpPath) {
			var_dump(exec('zip -r -9 ' . $dumpPath . '  ' . $content));

			return $dumpPath;
		}
	}