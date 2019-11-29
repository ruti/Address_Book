<?php
namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Email;
use App\Entity\PhoneNumber;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Psr\Log\LoggerInterface;

class ContactController extends ApiController
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    /**
    * @Route("/contacts", methods="GET")
    */
    public function list(ContactRepository $contactRepository)
    {
        $contacts = $contactRepository->transformAll();
        return $this->respond($contacts);
    }

    /**
    * @Route("/contacts", methods="POST")
    */
    public function create(Request $request, ContactRepository $contactRepository, EntityManagerInterface $em)
    {
        //$this->logger->info('Inside list*********************' . $request->getContent());
        $request = $this->transformJsonBody($request);
        //create the new contact
        $contact = new Contact;
        $contact->setFirstName($request->get('firstName'));
        $contact->setLastName($request->get('lastName'));

        //Add the phone number
        $phoneNumber = new PhoneNumber;
        $phoneNumber->setContact($contact);
        $phoneNumber->setNumber($request->get('phoneNumber'));

        //Add the email
        $email = new Email;
        $email->setContact($contact);
        $email->setEmail($request->get('email'));

        //Persist the entities
        $em->persist($contact);
        $em->persist($phoneNumber);
        $em->persist($email);
        $em->flush();

        return $this->redirect('/contacts/');
    }

    /**
    * @Route("/contacts/update/{id}", methods="PUT")
    */
    public function update($id, ContactRepository $contactRepository, EntityManagerInterface $em, Request $request)
    {
        $this->logger->info('Inside Before*********************' . $request->getContent());
        
        $request = $this->transformJsonBody($request);
        $contact = $contactRepository->find($id);
        $this->logger->info('Inside list*********************' . $request->getContent());

        //update the contact
        $contact->setFirstName($request->get('firstName'));
        $contact->setLastName($request->get('lastName'));
        $em->persist($contact);

        //Update the phoneNumbers
        $phoneNumbers = $request->get('phoneNumbers');
        foreach ($phoneNumbers as $key =>$number) {
            $phoneNumber = new PhoneNumber;
            $phoneNumber->setId($number['id']);
            $phoneNumber->setContact($contact);
            $phoneNumber->setNumber($number["phoneNumber"]);
            $em->merge($phoneNumber);
        }

        //Update the emails
        $emails = $request->get('emails');
        foreach ($emails as $key =>$e) {
            $email = new Email;
            $email->setId($e['id']);
            $email->setContact($contact);
            $email->setEmail($e["email"]);
            $em->merge($email);
        }
        
        $em->flush();
        //redirect to list
        return $this->respond($contact);
    }

    /**
    * @Route("/contacts/find/{id}", methods="GET")
    */
    public function find($id, ContactRepository $contactRepository)
    {
        $contact = $contactRepository->find($id);
        if (! $contact) {
            return $this->respondNotFound();
        }
        $contactTransformed = $contactRepository->transformTotally($contact);
        
        return $this->respond($contactTransformed);
    }


    /**
    * @Route("/contacts/delete/{id}", methods="DELETE")
    */
    public function delete($id, ContactRepository $contactRepository, EntityManagerInterface $em)
    {
        $contact = $contactRepository->find($id);
        if (! $contact) {
            return $this->respondNotFound();
        }
        $phoneNumbers = $contact->getPhoneNumbers();
        $emails = $contact->getEmails();
        
        foreach ($phoneNumbers as $phoneNumber) {
            $em->remove($phoneNumber);
        }
        foreach ($emails as $email) {
            $em->remove($email);
        }
        $em->remove($contact);
        $em->flush();
        //redirect to list
        return $this->respond([]);
    }

    /**
    * @Route("/contacts/search/", methods="POST")
    */
   /* public function search($id, ContactRepository $contactRepository)
    {
        $contact = $contactRepository->find($id);
        if (! $contact) {
            return $this->respondNotFound();
        }
        $contactTransformed = $contactRepository->transform($contact);
        return $this->respond($$contactTransformed);
    }*/

    
}