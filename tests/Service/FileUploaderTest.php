<?php


namespace App\Tests\Service;

use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use PHPUnit\Framework\TestCase;

class FileUploaderTest extends TestCase
{
    public function testUploadFile(): void
    {
        // Mock up an UploadedFile object
        $em = $this->getMockBuilder(UploadedFile::class)
                   ->disableOriginalConstructor()
                   ->getMock();

        // Get extension of file
        $em->expects($this->once())
           ->method('guessClientExtension')
           ->willReturn('_EXT_');

        // Save file
        $em->expects($this->once())
           ->method('move')
           ->with(
               $this->equalTo('_DIR_'),
               $this->stringEndsWith('_EXT_')
           );

        $service = new FileUploader('_DIR_');
        $fileName = $service->upload($em);
        $this->assertRegExp('/.+?\._EXT_/', $fileName);
        $this->assertEquals('_DIR_', $service->getTargetDirectory());
    }
}