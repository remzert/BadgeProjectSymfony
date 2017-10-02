<?php

namespace AppBundle\Subscriber;

use AppBundle\Event\CommentCreatedEvent;
use AppBundle\Mailer\AppMailer;
use Doctrine\Common\Persistence\ObjectManager;
use Rem\BadgeBundle\Event\BadgeUnlockedEvent;
use Rem\BadgeBundle\Manager\BadgeManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BadgeSubscriber implements EventSubscriberInterface{

    /**
     * @var BadgeManager
     */
    private $badgeManager;

    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var AppMailer
     */
    private $mailer;

    public function __construct(AppMailer $mailer, ObjectManager $em, BadgeManager $badgeManager) {
        
        $this->mailer = $mailer;
        $this->em = $em;
        $this->badgeManager = $badgeManager;
    }
    
    public static function getSubscribedEvents(): array {
        return [
            BadgeUnlockedEvent::NAME => 'onBadgeUnlock',
            CommentCreatedEvent::NAME => 'onNewComment'
        ];
    }
    
    public function onBadgeUnlock(BadgeUnlockedEvent $event){
        $event->stopPropagation();
        return $this->mailer->badgeUnlocked($event->getBadge(), $event->getUser());
    }
    
    public function onNewComment(CommentCreatedEvent $event){
        $event->stopPropagation();
        $user = $event->getComment()->getUser();
        $comments_count = $this->em->getRepository('AppBundle:Comment')->countForUser($user->getId());
        $this->badgeManager->checkAndUnlock($user, 'comment', $comments_count);
    }
}