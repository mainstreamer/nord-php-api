<?php

namespace App\Service;

use App\Dto\ItemDto;
use App\Entity\Item;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ItemService
{
    private EntityManagerInterface $entityManager;
    
    private UserService $userService;

    public function __construct(EntityManagerInterface $entityManager, UserService $userService)
    {
        $this->entityManager = $entityManager;
        $this->userService = $userService;
    }

    public function create(User $user, string $data): void
    {
        $item = new Item();
        $item->setUser($user);
        $item->setData($data);

        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }
    
    public function getDto(Item $item): ItemDto
    {
        return new ItemDto(
            $item->getId(),
            $item->getData(),
            \DateTimeImmutable::createFromMutable($item->getCreatedAt()),
            \DateTimeImmutable::createFromMutable($item->getUpdatedAt()),
            $this->userService->getDto($item->getUser())
        );
    }
} 
