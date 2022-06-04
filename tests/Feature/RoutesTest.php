<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_upload_csv_form_route()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_upload_csv_post_route()
    {
        $this->withoutExceptionHandling();

        $path = public_path('test_files/test-file.csv');

        $file = new UploadedFile($path, basename($path), mime_content_type($path), filesize($path), false);

        $response = $this->postJson('/fileUpload', [
            'csv' => $file,
        ]);

        $response->assertStatus(200);
    }
}
