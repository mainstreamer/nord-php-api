<?php

namespace App\Security;

use App\Entity\Item;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ItemVoter extends Voter
{
    public const ACCESS = 'ACCESS';
    
    protected function supports(string $attribute, $subject): bool
    {
        return ($subject instanceof Item) && (self::ACCESS === $attribute);
    }
    
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        
        if (!$user instanceof User) {
            return false;
        }
        
        return $subject->getUser() === $user;
    }
}
