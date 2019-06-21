<?php


namespace App\Tests\Controller\API;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class JobControllerTest extends WebTestCase
{
    public function testGetJobsActionReturnJson()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/v1/sensio_labs/jobs');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
    }
}