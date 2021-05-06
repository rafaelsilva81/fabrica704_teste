<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Ignore;
use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 */
class Customer implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $document;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $ip_location;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $timestamps;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(string $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getIpLocation(): ?string
    {
        return $this->ip_location;
    }

    public function setIpLocation(?string $ip_location): self
    {
        $this->ip_location = $ip_location;

        return $this;
    }

    public function getTimestamps(): ?\DateTimeInterface
    {
        return $this->timestamps;
    }

    public function setTimestamps(?\DateTimeInterface $timestamps): self
    {
        $this->timestamps = $timestamps;

        return $this;
    }
    
    /**
     * @Ignore()
     */
    public function getUsername()
    {
        return $this->id;
    }

    
    /**
     * @Ignore()
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * @Ignore()
     */
    public function getSalt()
    {
    }
    public function eraseCredentials()
    {
    }
}
