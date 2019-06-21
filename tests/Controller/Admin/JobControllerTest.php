<?php


namespace App\Tests\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class JobControllerTest extends WebTestCase
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

        // Test show list job
        $crawler = $client->request('GET', '/admin/job/list');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('th:contains("Position")')->count());
    }

    public function testCreate()
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

        // Test create a category
        $crawler = $client->request('GET', '/admin/job/create');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $form = $crawler->filter('button:contains("Save")')->form();

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

        // Test login
        $crawler = $client->request('GET', '/login');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $form = $crawler->filter('form')->form();
        $form['_username'] = 'admin';
        $form['_password'] = 'admin';
        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());

        $crawler = $client->request('GET', '/admin/job/28/edit');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('button:contains("Save")')->form();

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
}