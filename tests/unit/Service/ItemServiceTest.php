<?php

namespace App\Tests\Unit;

use App\Dto\ItemDto;
use App\Dto\UserDto;
use App\Entity\Item;
use App\Entity\User;
use App\Service\ItemService;
use App\Service\UserService;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;

class ItemServiceTest extends TestCase
{
    private ItemService $itemService;
    private UserService $userService;
    private UserDto $userDtoMock;

    public function setUp(): void
    {
        /** @var EntityManagerInterface */
        $this->userDtoMock = $this->createMock(UserDto::class);
        $this->userService = $this->createMock(UserService::class);
        $this->userService->method('getDto')->willReturn($this->userDtoMock);
        $this->itemService = new ItemService($this->userService);
    }

    public function testCreate(): void
    {
        /** @var User */
        $user = $this->createMock(User::class);
        $data = 'secret data';
        $expectedObject = new Item();
        $expectedObject->setUser($user);
        $expectedObject->setData($data);
        $item = $this->itemService->create($user, $data);
        
        $this->assertEquals($expectedObject, $item);
    }
    
    public function testCreateNegative(): void
    {
        /** @var User */
        $user = $this->createMock(User::class);
        $this->expectException(\Throwable::class);
        $this->itemService->create($user, null);
    }
    
    public function testGetDto(): void
    {
        $date = new \DateTime();
        $item = new Item();
        $item->setUser($this->createMock(User::class));
        $item->setData('secret data');
        $item->setCreatedAt($date);
        $item->setUpdatedAt($date);
        
        $expectedObject = new ItemDto(
            0, 
            'secret data',
            \DateTimeImmutable::createFromMutable($date),
            \DateTimeImmutable::createFromMutable($date),
            $this->userDtoMock
        );
        
        $itemDto = $this->itemService->getDto($item);
        $this->assertEquals($expectedObject, $itemDto);
    }
}
