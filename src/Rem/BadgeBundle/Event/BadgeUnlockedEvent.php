<?php

namespace Rem\BadgeBundle\Event;

use AppBundle\Entity\User;
use Rem\BadgeBundle\Entity\Badge;
use Rem\BadgeBundle\Entity\BadgeUnlock;
use Symfony\Component\EventDispatcher\Event;

class BadgeUnlockedEvent extends Event{
    const NAME = 'badge.unlock';
    
    /**
     *
     * @var BadgeUnlock
     */
    private $badgeUnlock;
    
    public function __construct(BadgeUnlock $badgeUnlock){
        $this->badgeUnlock = $badgeUnlock;
    }
    
    /**
     * 
     * @return BadgeUnlock
     */
    public function getBadgeUnlock(): BadgeUnlock{
        return $this->badgeUnlock;
    }
    
    public function getBadge(): Badge{
        return $this->badgeUnlock->getBadge();
    }
    
    public function getUser(): User{
        return $this->badgeUnlock->getUser();
    }
    
}