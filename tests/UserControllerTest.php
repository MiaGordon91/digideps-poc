<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserControllerTest extends WebTestCase
{
    public function testSpreadsheetGenerationSucceeds()
    {
        $client = static::createClient();
        $client->request('GET', '/download_spreadsheet');

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPostRequestRedirectsSuccessfully()
    {
        $client = static::createClient();

        $moneyOutSpreadsheet = new UploadedFile(
            __DIR__.'/fixtures/money_out_template.xlsx',
            'money_out_template.xlsx',
            'xlsx',
            123
        );

        $client->request(
            'POST',
            '/money_out',
            ['name' => 'money_out_template'],
            ['money_out_template' => $moneyOutSpreadsheet],
        );

        $this->assertTrue($client->getResponse()->isRedirect());
    }
}
