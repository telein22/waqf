<?php

namespace Application\ThirdParties\AWS;

use Application\Models\User;
use Aws\S3\S3Client;
use System\Core\Application;

class AWS
{
    private array $config;
    private S3Client $s3;
    private string $bucketName;

    public const IMAGES_DIRECTORY = 'images';
    public const INVOICE_DIRECTORY = 'invoices';
    public const FREELANCE_DOCUMENTS_DIRECTORY = 'freelanceDocs';

    public function __construct()
    {
        $this->config = Application::config()->AWS;
        $this->bucketName = $this->config['bucket'];

        $this->s3 = new S3Client([
            'version' => 'latest',
            'region' => $this->config['region'],
            'credentials' => [
                'key' => $this->config['key'],
                'secret' => $this->config['secret']
            ]
        ]);
    }

    public static function syncFileWithS3(string $fileName, string $path, string $cloudDirectory = null): void
    {
        $cloudDirectory = $cloudDirectory ?? self::IMAGES_DIRECTORY;
        $handler = self::init();

        $userId  = User::getId();
        $key = "old_file{$userId}";
        if (isset($_SESSION[$key])) {
            $handler->s3->deleteObject([
                'Bucket' => $handler->bucketName,
                'Key' => "{$cloudDirectory}/{$_SESSION[$key]}",
            ]);

            unset($_SESSION[$key]);
        }

        $handler->s3->putObject([
            'Bucket' => $handler->bucketName,
            'Key' => "{$cloudDirectory}/{$fileName}",
            'Body' => fopen($path, 'r'),
        ]);
    }

    public static function getFileURL(string $fileName, string $cloudDirectory = null): string
    {
        $handler = self::init();
        $cloudDirectory = $cloudDirectory ?? self::IMAGES_DIRECTORY;

        return "{$handler->config['base_url']}/{$cloudDirectory}/{$fileName}";
    }

    private static function init()
    {
        return new static();
    }
}