<?php

namespace App\Entity;

use App\Repository\FeedbackRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FeedbackRepository::class)
 */
class Feedback
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Assert\Length(
     *     max=100,
     *     maxMessage="The name it too long"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @Assert\NotNull
     * @Assert\Length(
     *     max=100,
     *     maxMessage="The email it too long"
     * )
     * * @Assert\Email(
     *     message="The emai '{{ value }}' is not valid email."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull
     * @Assert\Length(
     *     max=500,
     *     maxMessage="The message it too long"
     * )
     */
    private $text;

    /**
     * @ORM\Column(type="datetime",
     *     options={"default": "CURRENT_TIMESTAMP"})
     *
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $remote_addr;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getRemoteAddr(): ?string
    {
        return $this->remote_addr;
    }

    public function setRemoteAddr(string $remote_addr): self
    {
        $this->remote_addr = $remote_addr;

        return $this;
    }
}
