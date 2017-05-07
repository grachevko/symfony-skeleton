<?php

declare(strict_types=1);

namespace App\Entity;

use App\Uuid\UuidGenerator;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid_binary")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();

        $this->id = UuidGenerator::generate();
    }

    public function setEmail($email)
    {
        $this->username = $email;

        return parent::setEmail($email);
    }
}
