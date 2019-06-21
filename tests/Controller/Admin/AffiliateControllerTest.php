<?php


namespace App\Tests\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AffiliateControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = static::createClient();

        // Test login
        $crawler = $client->request('GET', '/login');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $form = $crawler->filter('form')->form();
        $form['_username'] = 'admin';
        $form['_password'] = 'admin';
        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());

        // Test list
        $crawler = $client->request('GET', '/admin/affiliate/list');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('th:contains("Email")')->count());
    }
}