<?php

namespace App\Controller;


use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistryController extends AbstractController
{
    /**
     * @Route("/reg", name="reg")
     */
    public function reg(Request $request, UserPasswordEncoderInterface $passEncode): Response
    {
        $regform = $this->createFormBuilder()
            ->add('username', TextType::class,[
                'label' => 'Username'])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => ' Password'],
                'second_options' => ['label' => 'Password repeat']])
            ->add('Registry', SubmitType::class)
            ->getForm();

        $regform->handleRequest($request);

        if ($regform->isSubmitted())
        {
            $eintry = $regform->getData();

            $user = new User();
            $user->setUsername($eintry['username']);
            $user->setPassword(
                $passEncode->encodePassword($user, $eintry['password'])
            );

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('home'));

        }

        return $this->render('registry/index.html.twig', [
            'regform' => $regform->createView(),
        ]);
    }
}
