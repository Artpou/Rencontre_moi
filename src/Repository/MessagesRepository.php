<?php

namespace App\Repository;

use App\Entity\Messages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method Messages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Messages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Messages[]    findAll()
 * @method Messages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Messages::class);
    }

    // /**
    //  * @return Messages[] Returns an array of Messages objects
    //  */

    public function getMessages($author_id, $receiver_id)
    {
      $rsm = new ResultSetMapping;
      $rsm->addEntityResult('App\Entity\Messages', 'm');
      $rsm->addFieldResult('m', 'id', 'id');
      $rsm->addFieldResult('m', 'date', 'date');
      $rsm->addFieldResult('m', 'message', 'message');
      $rsm->addMetaResult('m', 'author_id', 'author_id');
      $rsm->addMetaResult('m', 'receiver_id', 'receiver_id');
        return $this->_em->createNativeQuery('SELECT * FROM messages WHERE (author_id=:author_id and receiver_id=:receiver_id) or (author_id=:receiver_id and receiver_id=:author_id) ORDER BY id desc',$rsm)
            ->setParameter('author_id', $author_id)
            ->setParameter('receiver_id', $receiver_id)
            ->getResult()
        ;
    }

    public function getNewMessages($id,$author_id, $receiver_id)
    {
      $rsm = new ResultSetMapping;
      $rsm->addEntityResult('App\Entity\Messages', 'm');
      $rsm->addFieldResult('m', 'id', 'id');
      $rsm->addFieldResult('m', 'date', 'date');
      $rsm->addFieldResult('m', 'message', 'message');
      $rsm->addMetaResult('m', 'author_id', 'author_id');
      $rsm->addMetaResult('m', 'receiver_id', 'receiver_id');
      // Ancienne requète : Posait problème en cas de nouvelle communication. SELECT * FROM messages where author_id in ((SELECT author_id FROM messages WHERE id=:id ) UNION (SELECT receiver_id FROM messages WHERE id=:id)) and receiver_id in ((SELECT author_id FROM messages WHERE id=:id ) UNION (SELECT receiver_id FROM messages WHERE id=:id )) AND id > :id ORDER BY id DESC
        return $this->_em->createNativeQuery('SELECT * FROM messages WHERE ((author_id=:author_id and receiver_id=:receiver_id) or (author_id=:receiver_id and receiver_id=:author_id)) AND id > :id ORDER BY id DESC;',$rsm)
            ->setParameter('id', $id)
            ->setParameter('author_id', $author_id)
            ->setParameter('receiver_id', $receiver_id)
            ->getResult()
        ;
    }

    // /**
    //  * @return Messages[] Returns an array of Messages objects
    //  */
    public function insertMessage($author,$receiver,$message)
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager
        $new_message = new Messages();
        $new_message->setAuthor($author);
        $new_message->setReceiver($receiver);
        $new_message->setMessage($message);
        $new_message->setDate(new \DateTime('@'.strtotime('now')));
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $this->_em->persist($new_message);
        // actually executes the queries (i.e. the INSERT query)
        $this->_em->flush();
        return new Response('Saved new product with id '.$new_message->getId());
    }
}
