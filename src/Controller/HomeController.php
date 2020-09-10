<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController{

    public function home(){
        $user = $this->getUser();
        return $this->render('home.html.twig', [
            "user" => $user
        ]);
    }
}