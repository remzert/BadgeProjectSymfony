<?php

namespace Rem\BadgeBundle\Manager;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NoResultException;
use Rem\BadgeBundle\count;
use Rem\BadgeBundle\Entity\BadgeUnlock;
use Rem\BadgeBundle\Event\BadgeUnlockedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;




Class BadgeManager{

        /**
         *
         * @var ObjectManager    
         */
        private $em;
        
        /**
         *
         * @var EventDispatcherInterface
         */
        private $dispatcher;
    
        public function __construct(ObjectManager $manager, EventDispatcherInterface $dispatcher){
            $this->em = $manager;
            $this->dispatcher = $dispatcher;
        }
    
    
    
        /**
         * Check if a badge exists for this action and action occurence and unlock it for the user
         * 
         * @param User $user
         * @param string $action
         * @param int $action_count
         */
        public function checkAndUnlock(User $user, string $action, int $action_count){
            //Vérifier si un badge correspond à action et action count
            try{
                $badge = $this->em
                    ->getRepository('BadgeBundle:Badge')
                    ->findWithUnlockForAction($user->getId(), $action, $action_count);
                //dump($badge);
               

                 //Vérifier si l'utilisateur a déjà ce badge
                if($badge->getUnlocks()->isEmpty())
                {
                    //Débloquer le badge pour l'utilisateur en question
                    $unlock= new BadgeUnlock();
                    $unlock->setBadge($badge);
                    $unlock->setUser($user);
                    
                    $this->em->persist($unlock);
                    $this->em->flush();
                    
                   /* $count = count::getInstance();                    
                    if(null ===$count->getCount()){
                        $count->setCount(0);
                        
                    }         
                                 
                    $i=$count->getCount();
                    $i += 1;
                    $count->setCount($i);
                                        
                     var_dump($i);  */ 
                    
                    //Emetter un évenement pour informer l'application du déblocage du badge
                    $this->dispatcher->dispatch(BadgeUnlockedEvent::NAME, new BadgeUnlockedEvent($unlock));
                }
            } catch (NoResultException $e) {
                    
            }
            
        }
        
        /**
         * Get Badges unlocked for the current user
         * @param User $user
         */
        public function getBadgeFor(User $user){
          return $this->em->getRepository('BadgeBundle:Badge')->findUnlockedFor($user->getId());
        }
}