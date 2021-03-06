<?php
/**
 *  This file is part of the Lego project.
 *
 *   (c) Joris Saenger <joris.saenger@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Idk\LegoBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Idk\LegoBundle\Annotation\Entity as Lego;
/**
 *
 * @MappedSuperclass
 */
class LegoUserModel implements UserInterface, EquatableInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var integer
     *
     * @Lego\Field(label="lego.user.name")
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    protected $name;

    /**
     * @var integer
     *
     * @Lego\Field(label="lego.user.email")
     * @ORM\Column(name="email", type="string", nullable=true)
     */
    protected $email;

    /**
     * @var integer
     *
     * @Lego\Field(label="lego.user.username")
     * @ORM\Column(name="username", type="string", nullable=true)
     */
    protected $username;

    /**
     * @var integer
     * @Lego\Field(label="lego.user.enable", edit_in_place=true)
     * @ORM\Column(name="enable", type="boolean", nullable=true)
     */
    protected $enable;

    /**
     * @var integer
     *
     * @Lego\Field(label="lego.user.roles")
     * @ORM\Column(name="roles", type="array", nullable=true)
     */
    protected $roles;

    /**
     * @var integer
     *
     * @ORM\Column(name="password", type="string", nullable=true)
     */
    protected $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="salt", type="string", nullable=true)
     */
    protected $salt;

    protected $plainpassword = null;

    public function __construct() {
        $this->roles = array("ROLE_USER");
    }

    public function getRoles() {
        return $this->roles;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getSalt() {
        return $this->salt;
    }

    public function getUsername() {
        return $this->username;
    }

    public function eraseCredentials() {
    }

    /**
     * @param int $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @param int $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * @param int $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param int $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param int $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPlainPassword($password){
        $this->password = null;
        $this->plainpassword = $password;
    }

    public function getPlainPassword(){
        return $this->plainpassword;
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof LegoUserModel) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->salt !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     * @return int
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * @param int $enable
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
    }




}
