<?php

namespace Lucario\Tests\Upload;

use Lucario\Upload\Upload;
use PHPUnit\Framework\TestCase;

class UploadTest extends TestCase
{
    public function testCanChangeAllowedExtensions(): void
    {
        $file = (new Upload())
            ->setAllowedExtensions(['plain/text' => 'txt'])
            ->save([
                'name' => 'test.txt',

            ], __DIR__.'/var');
    }
}
