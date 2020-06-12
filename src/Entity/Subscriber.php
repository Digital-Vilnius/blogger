<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubscriberRepository")
 * @ORM\Table(uniqueConstraints={
 *     @UniqueConstraint(columns={"application_id", "email"}),
 *     @UniqueConstraint(columns={"application_id", "phone"})
 * })
 * @UniqueEntity(fields={"application", "email"})
 * @UniqueEntity(fields={"application", "phone"})
 * @ORM\HasLifecycleCallbacks()
 */
class Subscriber
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $phone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $smsNotification;

    /**
     * @ORM\Column(type="boolean")
     */
    private $emailNotification;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Application", inversedBy="subscribers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $application;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notification", mappedBy="subscriber")
     */
    private $notifications;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->email ? $this->email : $this->phone;
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

    public function getApplication(): ?Application
    {
        return $this->application;
    }

    public function setApplication(Application $application): void
    {
        $this->application = $application;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getSmsNotification(): ?bool
    {
        return $this->smsNotification;
    }

    public function setSmsNotification(bool $smsNotification): void
    {
        $this->smsNotification = $smsNotification;
    }

    public function getEmailNotification(): ?bool
    {
        return $this->emailNotification;
    }

    public function setEmailNotification(bool $emailNotification): void
    {
        $this->emailNotification = $emailNotification;
    }

    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function setNotifications(Collection $notifications): void
    {
        $this->notifications = $notifications;
    }
}