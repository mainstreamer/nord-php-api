<?php

namespace App\Service;

use App\Dto\UserDto;
use App\Entity\User;

class UserService
{
    public function getDto(User $item): UserDto
    {
        return new UserDto(
            $item->getId(),
            $item->getUsername(),
            \DateTimeImmutable::createFromMutable($item->getCreatedAt()),
            \DateTimeImmutable::createFromMutable($item->getUpdatedAt())
        );
    }
} 
