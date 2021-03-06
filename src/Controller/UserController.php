<?php

namespace App\Controller;

use App\Entity\Benutzer;
use App\Form\UserType;
use App\Repository\BenutzerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user", name="user.")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="bearbeiten")
     */
    public function index(BenutzerRepository $users): Response
    {
        $usrs = $users->findAll();
        return $this->render('user/index.html.twig', [
            'benutzer' => $usrs,
        ]);
    }
    /**
     * @Route("/anlegen", name="anlegen")
     */
    public function anlegen(Request $request){
        $user = new Benutzer();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //EntityManager
            $em = $this->getDoctrine()->getManager();
            $bild = $request->files->get('user')['anhang'];

            if($bild){
                $dateiname = md5(uniqid()). '.' . $bild->guessClientExtension();
            }
            $bild->move(
                $this->getParameter('bilder_ordner'),
                $dateiname
            );

            $user->setBild($dateiname);
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('user.bearbeiten'));
        }


        //Response
        return $this->render('user/anlegen.html.twig', [
            'anlegenForm' => $form->createView(),
        ]);

    }
    /**
     * @Route("/entfernen/{id}", name="entfernen")
     */
    public function entfernen($id, BenutzerRepository $usr){
        $em = $this->getDoctrine()->getManager();
        $user = $usr->find($id);
        $em->remove($user);
        $em->flush();
        return $this->redirect($this->generateUrl('user.bearbeiten'));
    }
    /**
     * @Route("/anzeigen/{id}", name="anzeigen")
     */
    public function anzeigen(Benutzer $benutzer) {
        return $this->render('user/anzeigen.html.twig', [
            'benutz' => $benutzer
        ]);
    }
}
