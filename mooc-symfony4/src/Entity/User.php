<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="user", indexes={@ORM\Index(name="IDX_8D93D6495A2DB2A0", columns={"sex_id"}), @ORM\Index(name="IDX_8D93D649D60322AC", columns={"role_id"}), @ORM\Index(name="IDX_8D93D649D81022C0", columns={"preference_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="forename", type="string", length=255, nullable=false)
     */
    private $forename;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255, nullable=false)
     */
    private $mail;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth", type="date", nullable=false)
     */
    private $birth;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="places", type="string", length=255, nullable=false)
     */
    private $places;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var \Sex
     *
     * @ORM\ManyToOne(targetEntity="Sex")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sex_id", referencedColumnName="id")
     * })
     */
    private $sex;

    /**
     * @var \Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     * })
     */
    private $role;

    /**
     * @var \Preference
     *
     * @ORM\ManyToOne(targetEntity="Preference")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="preference_id", referencedColumnName="id")
     * })
     */
    private $preference;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profil_picture;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Hobbis", mappedBy="user")
     */
    private $hobbis;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Messages", mappedBy="author")
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Messages", mappedBy="receiver")
     */
    private $messages_rec;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="friends")
     */
    private $friends;

    public function __construct()
    {
        $this->friends = new ArrayCollection();
    }

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

    public function getForename(): ?string
    {
        return $this->forename;
    }

    public function setForename(string $forename): self
    {
        $this->forename = $forename;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getBirth(): ?\DateTimeInterface
    {
        return $this->birth;
    }

    public function setBirth(\DateTimeInterface $birth): self
    {
        $this->birth = $birth;

        return $this;
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

    public function getPlaces(): ?string
    {
        return $this->places;
    }

    public function setPlaces(string $places): self
    {
        $this->places = $places;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSex(): ?Sex
    {
        return $this->sex;
    }

    public function setSex(?Sex $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getPreference(): ?Preference
    {
        return $this->preference;
    }

    public function setPreference(?Preference $preference): self
    {
        $this->preference = $preference;

        return $this;
    }

    public function getProfilPicture(): ?string
    {
        return $this->profil_picture;
    }

    public function setProfilPicture(?string $profil_picture): self
    {
        $this->profil_picture = $profil_picture;

        return $this;
    }

    public function getUsername()
    {
        return $this->mail;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return Collection|Hobbis[]
     */
    public function getHobbis(): Collection
    {
        return $this->hobbis;
    }

    public function addHobbi(Hobbis $hobbi): self
    {
        if (!$this->hobbis->contains($hobbi)) {
            $this->hobbis[] = $hobbi;
            $hobbi->setUser($this);
        }

        return $this;
    }

    public function removeHobbi(Hobbis $hobbi): self
    {
        if ($this->hobbis->contains($hobbi)) {
            $this->hobbis->removeElement($hobbi);
            // set the owning side to null (unless already changed)
            if ($hobbi->getUser() === $this) {
                $hobbi->setUser(null);
            }
        }

        return $this;
    }

        /** @see \Serializable::serialize() */
        public function serialize()
        {
            return serialize(array(
                $this->id,
                $this->mail,
                $this->password,
                // see section on salt below
                // $this->salt,
            ));
        }
    
        /** @see \Serializable::unserialize() */
        public function unserialize($serialized)
        {
            list (
                $this->id,
                $this->mail,
                $this->password,
                // see section on salt below
                // $this->salt
            ) = unserialize($serialized, array('allowed_classes' => false));
        }

    /**
     * @return Collection|Messages[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Messages $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setAuthor($this);
        }

        return $this;
    }

    public function removeMessage(Messages $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getAuthor() === $this) {
                $message->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Messages[]
     */
    public function getMessagesRec(): Collection
    {
        return $this->messages_rec;
    }

    public function addMessagesRec(Messages $messagesRec): self
    {
        if (!$this->messages_rec->contains($messagesRec)) {
            $this->messages_rec[] = $messagesRec;
            $messagesRec->setReceiver($this);
        }

        return $this;
    }

    public function removeMessagesRec(Messages $messagesRec): self
    {
        if ($this->messages_rec->contains($messagesRec)) {
            $this->messages_rec->removeElement($messagesRec);
            // set the owning side to null (unless already changed)
            if ($messagesRec->getReceiver() === $this) {
                $messagesRec->setReceiver(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFriends(): Collection
    {
        return $this->friends;
    }

    public function addFriend(self $friend): self
    {
        if (!$this->friends->contains($friend)) {
            $this->friends[] = $friend;
        }

        return $this;
    }

    public function removeFriend(self $friend): self
    {
        if ($this->friends->contains($friend)) {
            $this->friends->removeElement($friend);
        }

        return $this;
    }

}
