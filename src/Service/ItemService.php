<?php

namespace App\Service;

use App\Dto\ItemDto;
use App\Entity\Item;
use Symfony\Component\Security\Core\User\UserInterface;

class ItemService
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function create(UserInterface $user, string $data): Item
    {
        $item = new Item();
        $item->setUser($user);
        $item->setData($data);
        
        return $item;
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
    
    public function getDtos(array $items): array
    {
        $allItems = [];
        
        foreach ($items as $item) {
            $allItems[] = $this->getDto($item);
        }
        
        return $allItems;
    }
} 
