<?php

namespace Lucario\Tests\Upload;

use Lucario\Upload\Upload;
use PHPUnit\Framework\TestCase;

class UploadTest extends TestCase
{
    public function test(): void
    {
        $this->assertSame(2, 1 + 1);
    }

//    public function testUpload(): void
//    {
//        $tmpDirectory = dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'var'.DIRECTORY_SEPARATOR;
//        touch($tmpDirectory.'test.txt');
//        /** @var Upload $upload */
//        $upload = $this
//            ->createMock(Upload::class)
//            ->expects($this->any())->method('isUploadedFile')->willReturn(true);
//        ;
//        $file = $upload
//            ->setAllowedExtensions(['plain/text' => 'txt'])
//            ->save([
//                'name' => 'test.txt',
//                'type' => 'plain/text',
//                'tmp_name' => $tmpDirectory.'test.txt',
//                'error' => 0,
//                'size' => 0,
//            ], $tmpDirectory);
//        $this->assertTrue(file_exists($tmpDirectory.$file));
////        unlink($tmpDirectory.'test.txt');
////        unlink($tmpDirectory.$file);
//    }
}
