<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /**
     * @var string
     */
    private $targetDirectory;

    /**
     * FileUploader constructor.
     * @param string $targetDirectory
     */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * Get target directory
     *
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file): string
    {
        // Create random file name
        $fileName = md5(uniqid()) . '.' . $file->guessClientExtension();
        // Move file to target directory
        $file->move($this->targetDirectory, $fileName);
        return $fileName;
    }
}