<?php

namespace Test\AppBundle\Controller;

use AppBundle\DataFixtures\ORM\LoadBadgeData;
use AppBundle\DataFixtures\ORM\LoadUserData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Rem\BadgeBundle\Event\BadgeUnlockedEvent;

Class CommentControllerTest extends WebTestCase{
    public function testUnAuthorized(){
        $client = $this->makeClient();
        $client->request('GET', '/create');
        $this->assertStatusCode('302', $client);
    }
    
    public function testPostComment(){
        $em = $this->getContainer()->get('doctrine')->getManager();
        $references = $this->loadFixtures([
            LoadUserData::class,
            LoadBadgeData::class            
         ])->getReferenceRepository();
        $user = $references->getReference('user');
        /*$calls=0;
      $this->getContainer()->get('event_dispatcher')->addListener(BadgeUnlockedEvent::NAME, function() use(&$calls){
           $calls++;
       } );*/
        
        //On obtient une 200
         $this->loginAs($user,'main');
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/create');
        $this->assertStatusCode('200', $client);
        
        //Poste un commentaire
        $form = $crawler->selectButton('Commenter')->form();
        $form->setValues([
            'appbundle_comment[content]' => 'Salut les gens !'
        ]);
        $client->enableProfiler();
        $client->submit($form);
        $mailCollector= $client->getProfile()->getCollector('swiftmailer');
        /*$collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];*/
        
        /*$mailCollector->getMessages()[0];
        var_dump($mailCollector);*/
        $this->assertStatusCode('200', $client);
        $this->assertCount(1, $em->getRepository('AppBundle:Comment')->findAll());
        $this->assertCount(1, $em->getRepository('BadgeBundle:BadgeUnlock')->findAll());
        /*$this->assertEquals(1, $calls);*/
        $this->assertEquals(1, $mailCollector->getMessageCount());
       /* $this->assertEquals(
            'Vous avez débloqué le badgeTimide',
            $message->getSubject()
            //$message->getBody()
        );*/
        
        
    } 
}
