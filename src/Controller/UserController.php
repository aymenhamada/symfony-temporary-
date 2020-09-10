<?php
namespace App\Controller;

use App\Form\UserType;
use App\Form\EditProfileType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;





class UserController extends Controller{

    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder,  AuthorizationCheckerInterface $authChecker, \Swift_Mailer $mailer){
        if ($authChecker->isGranted("IS_AUTHENTICATED_FULLY")){
            return $this->redirectToRoute('home');
        }
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setPicture("");
            $user->setAdresses("");
            $user->setInscriptionDate(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $message = (new \Swift_Message('Register Sucessfull'))
                ->setFrom('aymenhamada@90gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/registration.html.twig',
                        ['user' => $user]
                    ),
                    "text/html"
                )
            ;
            $mailer->send($message);
            return $this->redirectToRoute('login');
        }

        return $this->render(
            'register.html.twig',
            array('form' => $form->createView(), "user" => "")
        );
    }


    public function editProfile(Request $request){
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class);
        $errors = [];

        $form->handleRequest($request);


        if ($form->isSubmitted()){
            $dataToGet = ["prename", "name", "birthday", "adresses"];
            $data = [];

            foreach($dataToGet as $key){
                $data[$key] = $form->get($key)->getData();
                if(is_null($data[$key]) && $key != "adresses"){
                    $errors[$key] = "Le champ " . $key . " est requis";
                }
            }

            if(count($errors) > 0){
                return $this->render(
                    "profile.html.twig",
                    ["form" => $form->createView(), "user" => $user, "errors" => $errors]
                );
            }


            $image = $form->get("picture")->getData();
            if($image){
                $size = $image->getSize();
                list($width, $height) = getimagesize($image);
                $extension = $image->guessExtension();
                if($size > 1000000){
                    $errors["size"] = "La taille du fichier est trop grande !";
                }
                if($extension !== "jpg" && $extension !== "jpeg"){
                    $errors["imageExtension"] = "L'extension du fichier n'est pas accepté ! nous acceptons les fichier jpg seulement";
                }

                if(count($errors) > 0){
                    return $this->render(
                        "profile.html.twig",
                        ["form" => $form->createView(), "user" => $user, "errors" => $errors]
                    );
                }


                $file = md5(uniqid()) . "." . $extension;

                $image_p = imagecreatetruecolor(100, 100);
                $ressource = imagecreatefromjpeg($image);

                imagecopyresampled($image_p, $ressource, 0, 0, 0, 0, 100, 100, $width, $height);


                imagejpeg($image_p, $this->getParameter("images_directory")."/".$file);

                imagedestroy($image_p);
                imagedestroy($ressource);

                $user->setPicture("uploads/". $file);
            }

            $user->setAdresses($data["adresses"]);
            $user->setPrename($data["prename"]);
            $user->setName($data["name"]);
            $user->setBirthday($data["birthday"]);
            $em = $this->getDoctrine()->getManager();
            $em->flush($user);

            $this->addFlash("message", "Profil mis à jour");
            return $this->redirectToRoute("profile");
        }

        return $this->render(
            "profile.html.twig",
            ["form" => $form->createView(), "user" => $user, "errors" => $errors]
        );
    }


}