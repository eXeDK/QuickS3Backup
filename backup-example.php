<?php
	/**
	 * Settings for AWS, S3 AND THE DATABASE
	 */
	$aws_key        = 'YOUR S3 KEY';
	$aws_secret     = 'YOUR S3 SECRET';

	$bucket         = 'S3 BUCKET NAME';
	$backupFolder   = 'ABSOLUTE PATH TO BACKUP';
	$backupName     = 'NAME OF THE BACKUP';

	$db_user        = 'DATABASE USERNAME';
	$db_pass        = 'DATABASE PASSWORD';
	$db_db          = 'DATABASE DATABASE';

	/**
	 * This is where the magic happens
	 */
	set_time_limit(30 * 60);
	require 'vendor/autoload.php';
	use QuickS3Backup\S3Uploader;
	use QuickS3Backup\DatabaseExporter;

	$uploader = new S3Uploader(array(
		'key'       => $aws_key,
	    'secret'    => $aws_secret
	));
	$uploader->uploadDirectory($bucket, $backupFolder, $backupName);

	$uploader->uploadFile(
		$bucket,
		DatabaseExporter::exportDatabase($db_db, $db_user, $db_pass, __DIR__ . DIRECTORY_SEPARATOR . $db_db . '.sql'),
		$backupName . '/' . $db_db . '.sql');
	unlink(__DIR__ . DIRECTORY_SEPARATOR . $db_db . '.sql');