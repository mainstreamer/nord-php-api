<?php

namespace App\Dto;

class ItemDto implements \JsonSerializable
{
    public int $id;
    
    public string $data = '';
    
    public \DateTimeImmutable $createdAt;
    
    public \DateTimeImmutable $updatedAt;
    
    public  UserDto $userDto;
    
    public function __construct(
        int $id, 
        string $data, 
        \DateTimeImmutable $createdAt, 
        \DateTimeImmutable $updatedAt, 
        UserDto $userDto
    ) {
        $this->id = $id;
        $this->data = $data;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->userDto = $userDto;
    }
    
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
