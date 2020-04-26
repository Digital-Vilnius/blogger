<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Post
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
     * @ORM\Column(type="boolean")
     */
    private $visible;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Slug(fields={"title"})
     */
    private $slug;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\Column(type="text")
     */
    private $summary;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="posts")
     * @ORM\JoinTable(
     *     joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Blog", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $blog;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->visible = true;
    }

    public function __toString(): string
    {
        return $this->title;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags(ArrayCollection $tags): void
    {
        $this->tags = $tags;
    }

    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag))
        {
            $this->tags->add($tag);
        }
    }

    public function removeTag(Tag $tag): void
    {
        if ($this->tags->contains($tag))
        {
            $this->tags->removeElement($tag);
        }
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(Blog $blog): void
    {
        $this->blog = $blog;
    }

    public function getSubscribersEmails(): array
    {
        return array_map(function (Subscriber $subscriber) {
            return $subscriber->getEmail();
        }, $this->getBlog()->getSubscribers()->toArray());
    }

    public function getVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): void
    {
        $this->visible = $visible;
    }
}