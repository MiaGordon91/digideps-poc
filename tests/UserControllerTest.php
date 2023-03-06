<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testSpreadsheetGenerationSucceeds()
    {
        $client = static::createClient();
        $client->request('GET', '/download_spreadsheet');

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }
}
