<?php


namespace App\Tests\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CategoryControllerTest extends WebTestCase
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

        // Test show list category
        $crawler = $client->request('GET', '/admin/category/list');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('th:contains("Name")')->count());
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
        $crawler = $client->request('POST', '/admin/category/create');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $form = $crawler->filter('button:contains("Save")')->form();

        // Test create without name field
        $crawler = $client->submit($form);
        $this->assertFalse($client->getResponse()->isRedirect());

        // Test create with name field
        $form['category[name]'] = 'Tester';
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

        $crawler = $client->request('GET', '/admin/category/5/edit');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $form = $crawler->filter('button:contains("Save")')->form();

        // Test edit with name field
        $crawler = $client->submit($form);
        $url = $client->getResponse()->headers->get('location');
        $this->assertTrue($client->getResponse()->isRedirect($url));

        // Test edit without name field
        $form['category[name]'] = '';
        $crawler = $client->submit($form);
        $this->assertFalse($client->getResponse()->isRedirect());
    }
}