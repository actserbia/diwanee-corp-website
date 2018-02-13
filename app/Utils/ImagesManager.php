<?php
namespace App\Utils;

use Aws\S3\S3Client;

class ImagesManager {
    public static function upload($filePath) {
		$hash = md5_file($filePath);

		$s3Path = self::getS3Path($hash);
		self::uploadToS3($filePath, $s3Path);

		return config('images.s3amazon.amazonPath') . $s3Path;
	}

	public static function getS3Path($name, $pathOnly = true) {
		$path = config('images.s3amazon.amazonImagesFolder') . '/' . substr($name, 0, 2) . '/' . substr($name, 2, 2) . '/' . $name . '.jpg';
		if ($pathOnly) {
			return $path;
		}
		return config('images.s3amazon.amazonPath') . $path;
	}

	private static function uploadToS3($fileToSend, $s3ImgFile) {
		$s3Bucket = config('images.s3amazon.amazonBucket');

        $s3client = S3Client::factory(array(
            'key' => config('images.s3amazon.amazonAccessKey'),
			'secret' => config('images.s3amazon.amazonSecretKey')
	    ));

		$s3client->upload($s3Bucket, $s3ImgFile, fopen($fileToSend, 'r+'), 'public-read');
		$s3client->waitUntilObjectExists(array(
			'Bucket' => $s3Bucket,
			'Key'    => $s3ImgFile,
		));
	}

    public static function getHashFromS3Path($path) {
        $hash = '';

		$matches = array();
        $pattern = '/' . str_replace(array('/', '.'), array('\/', '\.'), config('images.s3amazon.amazonPath')) . config('images.s3amazon.amazonImagesFolder') . '\/(?:.*)\/(?:.*)\/(.*)\.(.*)/';
        preg_match($pattern, $path, $matches);
        if(isset($matches[1])) {
            $hash = $matches[1];
        }

        return $hash;
	}
}