<?php
namespace App\Controller;

use App\Entity\Contact;
use App\Entity\PhoneNumber;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class PhoneNumberController extends ApiController
{
     /**
    * @Route("/phoneNumbers/add/{id}", methods="POST")
    */
    public function addPhoneNumber($id, Request $request, ContactRepository $contactRepository, EntityManagerInterface $em)
    {
        $contact = $contactRepository->find($id);
        //Add the phone number
        $phoneNumber = new PhoneNumber;
        $phoneNumber->setContact($contact);
        $phoneNumber->setNumber($request->getContent());

        //Persist the entity
        $em->persist($phoneNumber);
        $em->flush();

        //redirect to view the current contact
        //redirect to view the current contact
        return $this->respond($contact);
    }
}