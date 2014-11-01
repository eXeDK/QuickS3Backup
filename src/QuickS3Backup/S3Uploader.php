<?php
	namespace QuickS3Backup;

	use Aws\Common\Aws;

	/**
	 * Class S3Uploader handles all upload to AWS S3
	 * @package QuickS3Backup
	 */
	class S3Uploader {
		/**
		 * @var $s3 \Aws\S3\S3Client
		 */
		private $s3;

		/**
		 * @param array $s3Config   Array with S3 configuration.
		 *                          Example:
		 *                          array(
		 *                               'key'       => [KEY],
		 *                               'secret'    => [SECRET]
		 *                          )
		 */
		public function __construct(array $s3Config) {
			$this->s3 = Aws::factory($s3Config)->get('s3');
			date_default_timezone_set('Europe/Copenhagen');
		}

		/**
		 * Upload a single file to S3
		 *
		 * @param string $bucket     Name of the bucket to upload to
		 * @param string $filePath   Path of the file to be uploaded
		 * @param string $s3Path     Path to save the file in the bucket
		 * @param bool   $overwrite  Whether or not to overwrite an exsting file
		 *
		 * @return \Guzzle\Service\Resource\Model
		 */
		public function uploadFile($bucket, $filePath, $s3Path, $overwrite = true) {
			if ($overwrite) {
				if ($this->s3->doesObjectExist($bucket, $s3Path)) {
					$this->s3->deleteObject(array(
						'Bucket' => $bucket,
						'Key'    => $s3Path
					));
				}
			}
			return $this->s3->upload($bucket, $s3Path, fopen($filePath, 'r'));
		}

		/**
		 * Recursively uploads all files in a given directory to a given bucket.
		 *
		 * @param string $bucket            Name of the bucket to upload to
		 * @param string $directotyPath     Path of the directory to upload
		 * @param string $s3Path            Path where to save the directory content
		 */
		public function uploadDirectory($bucket, $directotyPath, $s3Path) {
			return $this->s3->uploadDirectory($directotyPath, $bucket, $s3Path);
		}
	}