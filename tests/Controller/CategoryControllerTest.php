<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CategoryControllerTest extends WebTestCase
{
    public function testShow()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/category/programming');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h4:contains("Programming")')->count());
    }
}