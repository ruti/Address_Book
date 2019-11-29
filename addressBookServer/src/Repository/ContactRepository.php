<?php

namespace App\Repository;

use App\Entity\Contact;
use App\Entity\PhoneNumber;
use App\Entity\Email;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function transform(Contact $contact)
    {
        return [
                'id'    => (int) $contact->getId(),
                'firstName' => (string) $contact->getFirstName(),
                'lastName' => (string) $contact->getLastName(),
                'phoneNumber' => (string) $contact->getPhoneNumbers()[0]->getNumber(),
                'email' => (string) $contact->getEmails()[0]->getEmail()
        ];
    }

    public function transformTotally(Contact $contact)
    {
        $phoneNumbersArr = $this->transformPhoneNumbers($contact);
        $emailsArr = $this->transformEmails($contact);
        return [
                'id'    => (int) $contact->getId(),
                'firstName' => (string) $contact->getFirstName(),
                'lastName' => (string) $contact->getLastName(),
                'phoneNumbers' => (array) $phoneNumbersArr,
                'emails' => (array) $emailsArr
        ];
    }

    public function transformPhoneNumbers(Contact $contact){
        $phoneNumbers = $contact->getPhoneNumbers();
        $phoneNumbersArr = [];
        foreach ($phoneNumbers as $phoneNumber){
            $phoneNumbersArr[] =  $this->transformPhoneNumber($phoneNumber);
        }
        return $phoneNumbersArr;
    }

    public function transformEmails(Contact $contact){
        $emails = $contact->getEmails();
        $emailsArr = [];
        foreach ($emails as $email){
            $emailsArr[] =  $this->transformEmail($email);
        }
        return $emailsArr;

    }

    public function transformPhoneNumber(PhoneNumber $phoneNumber)
    {
        return [
                'id'    => (int) $phoneNumber->getId(),
                'phoneNumber' => (string) $phoneNumber->getNumber(),
        ];
    }

    public function transformEmail(Email $email)
    {
        return [
                'id'    => (int) $email->getId(),
                'email' => (string) $email->getEmail()
        ];
    }

    public function transformAll()
    {
        $contacts = $this->findAll();
        $contactsArray = [];

        foreach ($contacts as $contact) {
            $contactsArray[] = $this->transform($contact);
        }

        return $contactsArray;
    }

    // /**
    //  * @return Contact[] Returns an array of Contact objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Contact
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
