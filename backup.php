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
    set_time_limit(6000);
    require 'vendor/autoload.php';
    use Aws\Common\Aws;
    use Aws\S3\Exception\S3Exception;

    // Get an S3 client and upload the directory recursively
	/**
	 * @var $s3 \Aws\S3\S3Client
	 */
	$s3 = Aws::factory(array(
                            'key'       => $aws_key,
                            'secret'    => $aws_secret
                       ))->get('s3');

    // Backup the database and upload it, forcing overwrite
    // First uploading the database to have a backup if the upload of uploads folder fails
    exec('mysqldump --user=' . $db_user . ' --password=' . $db_pass . ' ' . $db_db . ' > dump.sql');
    if ($s3->doesObjectExist($bucket, $backupName . '/db_backup.sql')) {
        $s3->deleteObject(array(
                               'Bucket' => $bucket,
                               'Key'    => $backupName . '/db_backup.sql'
                          ));
    }
    $r = $s3->upload($bucket, $backupName . '/db_backup.sql', fopen('dump.sql', 'r'));

    // Clean up db dump
    unlink('dump.sql');

    $s3->uploadDirectory($backupFolder, $bucket, $backupName);


