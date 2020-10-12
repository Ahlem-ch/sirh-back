<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 */
class Notification
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"public","notification"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"public","notification"})
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"public","notification"})
     */
    private $commentaire;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"public","notification"})
     */
    private $is_read;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"public","notification"})
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"notification"})
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"public","notification"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"public","notification"})
     */
    private $send_to;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user")
     * @Groups({"public","notification"})
     */
    private $sent_to;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->is_read;
    }

    public function setIsRead(bool $is_read): self
    {
        $this->is_read = $is_read;

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

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSendTo(): ?string
    {
        return $this->send_to;
    }

    public function setSendTo(?string $send_to): self
    {
        $this->send_to = $send_to;

        return $this;
    }

    public function getSentTo(): ?user
    {
        return $this->sent_to;
    }

    public function setSentTo(?user $sent_to): self
    {
        $this->sent_to = $sent_to;

        return $this;
    }
}
