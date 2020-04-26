<?php

namespace App\Entity;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlogRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Blog
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="blog")
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tag", mappedBy="blog")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="blogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\Column(type="string")
     */
    private $origin;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Subscriber", mappedBy="blog")
     */
    private $subscribers;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->subscribers = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created = new DateTime('now');
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated = new DateTime('now');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function getUpdated(): ?DateTime
    {
        return $this->updated;
    }

    public function getPosts(): ArrayCollection
    {
        return $this->posts;
    }

    public function setPosts(ArrayCollection $posts): void
    {
        $this->posts = $posts;
    }

    public function getTags(): ArrayCollection
    {
        return $this->tags;
    }

    public function setTags(ArrayCollection $tags): void
    {
        $this->tags = $tags;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function setOrigin(string $origin): void
    {
        $this->origin = $origin;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSubscribers(): ArrayCollection
    {
        return $this->subscribers;
    }

    public function setSubscribers(ArrayCollection $subscribers): void
    {
        $this->subscribers = $subscribers;
    }
}