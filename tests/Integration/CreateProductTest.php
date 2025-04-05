<?php

namespace App\Tests\Integration;

use App\DataFixtures\AppFixtures;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader;

class CreateProductTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->entityManager = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();

        $loader = new Loader();
        $loader->addFixture(new AppFixtures());

        $purger = new ORMPurger($this->entityManager);
        $purger->purge();

        $executor = new ORMExecutor($this->entityManager, $purger);
        $executor->execute($loader->getFixtures());


    }

    public function testCreateProduct(): void
    {
        $category = $this->entityManager->getRepository(Category::class)->findOneBy([]);

        $this->client->request('POST', '/api/products', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ], json_encode([
            'name' => 'test4',
            'price' => '10.10',
            'categoryIds' => [$category->getId()],
        ]));

        $this->assertResponseIsSuccessful();

        $responseContent = $this->client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);
        $this->assertArrayHasKey('name', $responseData);
        $this->assertArrayHasKey('price', $responseData);

        $this->assertEquals('test4', $responseData['name']);
        $this->assertEquals('10.10', $responseData['price']);

        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['name' => 'test4']);
        $this->assertNotNull($product);
        $this->assertEquals('10.10', $product->getPrice());

        $this->assertEmailCount(1);

        $email = $this->getMailerMessage();
        $this->assertEmailHtmlBodyContains($email, 'Create product');
    }

    public function testFailCreateProduct(): void
    {
        $this->client->request('POST', '/api/products', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ], json_encode([
            'name' => 'test5',
        ]));

        $statusCode = $this->client->getResponse()->getStatusCode();
        $this->assertEquals(422, $statusCode);

        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['name' => 'test5']);
        $this->assertNull($product);

        $this->assertEmailCount(0);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
    }
}