<?php

namespace Rem\BadgeBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Rem\BadgeBundle\Entity\Badge;

/**
 * BadgeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BadgeRepository extends EntityRepository
{
    /**
     * 
     * @param int $user_id
     * @param string $action
     * @param int $action_count
     * @return Badge
     */
    public function findWithUnlockForAction(int $user_id, string $action, int $action_count): Badge{
        
        return $this->createQueryBuilder('b')
                //->andWhere('u.user = :user_id Or u.user IS NULL')
                ->leftJoin('b.unlocks', 'u', Join::WITH, 'u.user = :user_id')
                ->select('b, u')
                ->where('b.actionName = :action_name')
                ->andWhere('b.actionCount = :action_count')
                ->setParameters([
                    'action_count' => $action_count,
                    'action_name'  => $action,
                    'user_id'      => $user_id
                ])
                ->getQuery()
                ->getSingleResult();
        /*echo 'voila' .$test->getSqlQuery();
        return $test;*/
    }
    
    /**
     * Find all badges unlocked by a specific user
     * 
     * @param int $user_id
     * @return Badge[]
     */
    
    public function findUnlockedFor(int $user_id) {
        return $this->createQueryBuilder('b')
                ->join('b.unlocks', 'u')
                ->where('u.user = :user_id')
                ->setParameter('user_id', $user_id)
                ->getQuery()
                ->getResult();
        
        /*SELECT * FROM badge, badge_unlock where badge.id = badge_unlock.badge_id and badge_unlock.user_id=1 ;*/
        /*php bin/console doctrine:query:dql "SELECT b, u FROM BadgeBundle:Badge b JOIN b.unloks u WHERE u.user = 1"*/
        
    }
}
