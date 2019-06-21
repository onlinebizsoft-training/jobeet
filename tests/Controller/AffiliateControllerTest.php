<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AffiliateControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/affiliate/create');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $form = $crawler->filter('button:contains("Create")')->form();
        $form['affiliate[url]'] = 'http://url.com';
        $form['affiliate[email]'] = 'hello@email.com';
        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/affiliate/wait'));
    }
}