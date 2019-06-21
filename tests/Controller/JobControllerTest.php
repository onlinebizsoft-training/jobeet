<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class JobControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('h4')->count());
    }

    public function testShow()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/job/28');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertGreaterThanOrEqual(1, $crawler->filter('strong')->count());
    }

    public function testCreate()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/job/create');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('button:contains("Create")')->form();

        $form['job[category]'] = 5;
        $form['job[type]'] = 'part-time';
        $form['job[company]'] = '_COMPANY_';
        $form['job[logo]'] = '_LOGO_';
        $form['job[url]'] = 'http://url.com';
        $form['job[position]'] = '_POSITION_';
        $form['job[location]'] = '_LOCATION_';
        $form['job[description]'] = '_DESCRIPTION_';
        $form['job[howToApply]'] = 'email';
        $form['job[public]'] = true;
        $form['job[activated]'] = true;

        // Test create without email field
        $crawler = $client->submit($form);
        $this->assertFalse($client->getResponse()->isRedirect());

        // Test create with full fields
        $form['job[email]'] = 'hello@email.com';
        $crawler = $client->submit($form);
        $url = $client->getResponse()->headers->get('location');
        $this->assertTrue($client->getResponse()->isRedirect($url));
    }

    public function testEdit()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'job/job_1/edit');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('button:contains("Edit")')->form();

        $this->assertNotEmpty($form['job[email]']);

        // Test edit with full fields
        $crawler = $client->submit($form);
        $url = $client->getResponse()->headers->get('location');
        $this->assertTrue($client->getResponse()->isRedirect($url));

        // Test edit email field is empty
        $form['job[email]'] = '';
        $crawler = $client->submit($form);
        $this->assertFalse($client->getResponse()->isRedirect());
    }

    public function testPreview()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'job/job_1');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertContains('Control Panel', $client->getResponse()->getContent());
    }
}