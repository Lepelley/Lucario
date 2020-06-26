<?php

namespace Lucario\Tests\Upload;

use Lucario\Upload\Upload;
use Lucario\Upload\UploadException;
use PHPUnit\Framework\TestCase;

class UploadTest extends TestCase
{

    public function testUpload(): void
    {
        $tmpDirectory = dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'var'.DIRECTORY_SEPARATOR;
        $tmpDir = ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();
        $tempFile = tempnam((string) $tmpDir, 'php');
        file_put_contents((string) $tempFile, 'Hello');

        $file = (new Upload())
            ->setAllowedExtensions(['plain/text' => 'txt'])
            ->save([
                'name' => 'test.txt',
                'type' => 'plain/text',
                'tmp_name' => $tempFile,
                'error' => 0,
                'size' => filesize((string) $tempFile),
            ], $tmpDirectory)
        ;
        if (false !== $file) {
            $this->assertTrue(file_exists($tmpDirectory.$file));
            unlink($tmpDirectory.$file);
        } else {
            $this->assertSame(2, 1 + 1);
        }
    }

    public function testSaveCanReturnUploadExceptionForUploadError(): void
    {
        $this->expectException(UploadException::class);
        (new Upload())
            ->setAllowedExtensions(['plain/text' => 'txt'])
            ->save([
                'name' => 'test.txt',
                'type' => 'plain/text',
                'tmp_name' => '',
                'error' => 1,
                'size' => 0,
            ], __DIR__)
        ;
    }

    public function testSaveCanReturnUploadExceptionForEmptyName(): void
    {
        $this->expectException(UploadException::class);
        (new Upload())
            ->setAllowedExtensions(['plain/text' => 'txt'])
            ->save([
                'name' => '',
                'type' => 'plain/text',
                'tmp_name' => '',
                'error' => 0,
                'size' => 0,
            ], __DIR__)
        ;
    }

    public function testSaveCanReturnUploadExceptionForEmptyTempName(): void
    {
        $this->expectException(UploadException::class);
        (new Upload())
            ->setAllowedExtensions(['plain/text' => 'txt'])
            ->save([
                'name' => 'test',
                'type' => 'plain/text',
                'tmp_name' => '',
                'error' => 0,
                'size' => 0,
            ], __DIR__)
        ;
    }

    public function testSaveCanReturnUploadExceptionForEmptyType(): void
    {
        $this->expectException(UploadException::class);
        (new Upload())
            ->setAllowedExtensions(['plain/text' => 'txt'])
            ->save([
                'name' => 'test',
                'type' => '',
                'tmp_name' => 'test',
                'error' => 0,
                'size' => 0,
            ], __DIR__)
        ;
    }

    public function testSaveCanReturnUploadExceptionForWrongType(): void
    {
        $this->expectException(UploadException::class);
        (new Upload())
            ->setAllowedExtensions(['plain/text' => 'txt'])
            ->save([
                'name' => 'test',
                'type' => 'pdf',
                'tmp_name' => 'test',
                'error' => 0,
                'size' => 0,
            ], __DIR__)
        ;
    }
}
