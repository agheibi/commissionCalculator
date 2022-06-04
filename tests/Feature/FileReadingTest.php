<?php

namespace Tests\Feature;

use App\Actions\ConvertActions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class FileReadingTest extends TestCase
{
    public function test_convert_file_to_object()
    {

        $convert_actions = new ConvertActions();

        $path  = public_path('test_files/test-file.csv');

        $file = new UploadedFile($path, basename($path), mime_content_type($path), filesize($path), false);

        $response = $convert_actions->convertFileToArray($file);

        $this->assertTrue(gettype($response) == 'array');

    }
}
