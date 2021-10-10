<?php

namespace App\Tests\Unit;

use App\Dto\UserDto;
use App\Entity\User;
use App\Service\UserService;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $userService;

    public function setUp(): void
    {
        $this->userService = new UserService();
    }
    
    public function testGetDto(): void
    {
        $date = new \DateTime();
        $expectedObject = new UserDto(
            15,
            'jack',
            \DateTimeImmutable::createFromMutable($date),
            \DateTimeImmutable::createFromMutable($date)
        );
        
        $user = new User();
        $reflection = new \ReflectionClass($user);
        $reflectionProperty = $reflection->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($user,15);
        $user->setUpdatedAt($date);
        $user->setCreatedAt($date);
        $user->setUsername('jack');
        
        $itemDto = $this->userService->getDto($user);
        $this->assertEquals($expectedObject, $itemDto);
    }
}
