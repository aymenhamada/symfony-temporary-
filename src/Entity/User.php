<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="Email already taken")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=191)
     * @Assert\NotBlank()
     */
    private $prename;

    /**
     * @ORM\Column(type="string", length=191)
     * @Assert\NotBlank()
     */
    private $name;
    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */
    private $birthday;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $picture;

     /**
     * @ORM\Column(type="string", length=191)
     */
    private $adresses;

     /**
     * @ORM\Column(type="datetime")
     */
    private $inscriptionDate;

    public function __construct()
    {
        $this->roles = array('ROLE_USER');
    }


    // other properties and methods

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function getPrename(){
        return $this->prename;
    }

    public function setPrename($prename){
        $this->prename = $prename;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getBirthday(){
        return $this->birthday;
    }

    public function setBirthday($birthday){

        $this->birthday = $birthday;
    }

    public function getPlainPassword(){
        return $this->plainPassword;
    }

    public function setPlainPassword($password){
        $this->plainPassword = $password;
    }

    public function getPassword(){
        return $this->password;
    }

    public function setPassword($password){
        $this->password = $password;
    }

    public function setPicture($picture){
        $this->picture = $picture;
    }

    public function getPicture(){
        return $this->picture;
    }

    public function setAdresses($adresses){
        $this->adresses = $adresses;
    }

    public function getAdresses(){
        return $this->adresses;
    }

    public function setInscriptionDate($inscriptionDate){
        $this->inscriptionDate = $inscriptionDate;
    }

    public function getInscriptionDate(){
        return $this->inscriptionDate;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getUsername(){
        return $this->email;
    }

    public function getSalt(){
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }


    public function eraseCredentials(){
    }
}
?>