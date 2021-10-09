<?php

namespace App\Dto;

class UserDto implements \JsonSerializable
{
    public int $id;
    
    public string $username;
    
    public \DateTimeImmutable $createdAt;
    
    public \DateTimeImmutable $updatedAt;
    
    public function __construct(
        int $id, 
        string $username, 
        \DateTimeImmutable $createdAt, 
        \DateTimeImmutable $updatedAt 
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
    
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
