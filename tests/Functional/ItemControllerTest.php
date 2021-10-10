<?php

namespace App\Tests;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class ItemControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $itemRepository = static::$container->get(ItemRepository::class);
        $user = $userRepository->findOneByUsername('john');
        $client->loginUser($user);
        $data = 'very secure new item data';
        $newItemData = ['data' => $data];
        $client->request('POST', '/items', $newItemData);
        $client->request('GET', '/items');
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('very secure new item data', $client->getResponse()->getContent());
        $itemRepository->findOneByData($data);
    }
    
    public function testCreateNegative()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('john');
        $client->loginUser($user);
        $newItemData = ['random' => 'data'];
        $client->request('POST', '/items', $newItemData);
        $this->assertResponseStatusCodeSame(400);
    }
    
    public function testUpdate()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $itemRepository = static::$container->get(ItemRepository::class);
        $em = static::$container->get(EntityManagerInterface::class);
        $user = $userRepository->findOneByUsername('john');
    
        $data = 'to be updated';
        $newItem = new Item();
        $newItem->setData($data);
        $newItem->setUser($user);
        $em->persist($newItem);
        $em->flush($newItem);
        $item = $itemRepository->findOneBydata($data);
    
        self::assertEquals($data, $itemRepository->findOneByData($data)->getData());
        
        $user = $userRepository->findOneByUsername('john');
        $client->loginUser($user);
        $client->request('PUT', '/items/' . $item->getId(), ['data' => 'changed data']);
        
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('changed data', $client->getResponse()->getContent());
        
        self::assertEquals('changed data', $itemRepository->findOneById($item->getId())->getData());
    }
    
    public function testUpdateNegative()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $itemRepository = static::$container->get(ItemRepository::class);
        $em = static::$container->get(EntityManagerInterface::class);
        $user = $userRepository->findOneByUsername('john');
        
        $data = 'to be updated';
        $newItem = new Item();
        $newItem->setData($data);
        $newItem->setUser($user);
        $em->persist($newItem);
        $em->flush($newItem);
        $item = $itemRepository->findOneBydata($data);
        
        self::assertEquals($data, $itemRepository->findOneByData($data)->getData());
        
        $user = $userRepository->findOneByUsername('stefan');
        $client->loginUser($user);
        $client->request('PUT', '/items/' . $item->getId(), ['data' => 'changed data']);
        $this->assertResponseStatusCodeSame(403);
        
        self::assertEquals($data, $itemRepository->findOneById($item->getId())->getData());
    }
    
    public function testDelete()
    {
        $client = static::createClient();
        
        $userRepository = static::$container->get(UserRepository::class);
        $itemRepository = static::$container->get(ItemRepository::class);
        $em = static::$container->get(EntityManagerInterface::class);
    
        $user = $userRepository->findOneByUsername('john');
        
        $data = 'to be deleted';
        $newItem = new Item();
        $newItem->setData($data);
        $newItem->setUser($user);
        $em->persist($newItem);
        $em->flush($newItem);
        $item = $itemRepository->findOneBydata($data);
        
        self::assertEquals($data, $itemRepository->findOneByData($data)->getData());
        
        $user = $userRepository->findOneByUsername('john');
        $client->loginUser($user);
        
        $client->request('DELETE', '/items/' . $item->getId());
    
        self::assertEquals(null, $itemRepository->findOneById($item->getId()));
    }
    
    public function testDeleteNegative()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $itemRepository = static::$container->get(ItemRepository::class);
        $em = static::$container->get(EntityManagerInterface::class);
        $user = $userRepository->findOneByUsername('john');
        $data = 'to be deleted';
        $newItem = new Item();
        $newItem->setData($data);
        $newItem->setUser($user);
        $em->persist($newItem);
        $em->flush($newItem);
        $item = $itemRepository->findOneBydata($data);
        
        self::assertEquals($data, $itemRepository->findOneByData($data)->getData());
        $user = $userRepository->findOneByUsername('stefan');
        $client->loginUser($user);
        $client->request('DELETE', '/items/' . $item->getId());
        $this->assertResponseStatusCodeSame(403);
        
        self::assertEquals($data, $itemRepository->findOneById($item->getId())->getData());
    }
     
    public function testUnauthenticatedAccess(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $itemRepository = static::$container->get(ItemRepository::class);
        $em = static::$container->get(EntityManagerInterface::class);
        $user = $userRepository->findOneByUsername('john');
        $data = 'to be checked';
        $newItem = new Item();
        $newItem->setData($data);
        $newItem->setUser($user);
        $em->persist($newItem);
        $em->flush($newItem);
        $item = $itemRepository->findOneBydata($data);
        
        $client->request('DELETE', '/items/' . $item->getId());
        $this->assertResponseStatusCodeSame(401);
    
        $client->request('GET', '/items');
        $this->assertResponseStatusCodeSame(401);
    
        $client->request('POST', '/items');
        $this->assertResponseStatusCodeSame(401);
    
        $client->request('PUT', '/items/' . $item->getId());
        $this->assertResponseStatusCodeSame(401);
    }
}
