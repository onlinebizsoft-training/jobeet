<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JobRepository")
 * @ORM\Table(name="jobs")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMS\ExclusionPolicy("all")
 */
class Job
{
    public const FULL_TIME_TYPE = 'full-time';
    public const PART_TIME_TYPE = 'part-time';
    public const FREELANCE_TYPE = 'freelance';

    public const TYPES = [
        self::FULL_TIME_TYPE,
        self::PART_TIME_TYPE,
        self::FREELANCE_TYPE
    ];

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Expose()
     * @JMS\Type("int")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    private $company;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $howToApply;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $token;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $public;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $activated;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @JMS\Expose()
     * @JMS\Type("DateTime")
     */
    private $expiresAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="jobs")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     */
    private $category;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Job
     */
    public function setType(string $type): Job
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }

    /**
     * @param string $company
     * @return Job
     */
    public function setCompany(string $company): Job
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @return string|null|UploadedFile
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string|null|UploadedFile $logo
     * @return Job
     */
    public function setLogo(?string $logo): Job
    {
        $this->logo = $logo;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return Job
     */
    public function setUrl(?string $url): Job
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    /**
     * @param string $position
     * @return Job
     */
    public function setPosition(string $position): Job
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return Job
     */
    public function setLocation(string $location): Job
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Job
     */
    public function setDescription(string $description): Job
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getHowToApply(): ?string
    {
        return $this->howToApply;
    }

    /**
     * @param string $howToApply
     * @return Job
     */
    public function setHowToApply(string $howToApply): Job
    {
        $this->howToApply = $howToApply;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return Job
     */
    public function setToken(string $token): Job
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPublic() : ?bool
    {
        return $this->public;
    }

    /**
     * @param bool $public
     * @return Job
     */
    public function setPublic(bool $public) : Job
    {
        $this->public = $public;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActivated() : ?bool
    {
        return $this->activated;
    }

    /**
     * @param bool $activated
     * @return Job
     */
    public function setActivated(bool $activated) : Job
    {
        $this->activated = $activated;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Job
     */
    public function setEmail(string $email): Job
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiresAt(): ?\DateTime
    {
        return $this->expiresAt;
    }

    /**
     * @param \DateTime $expiresAt
     * @return Job
     */
    public function setExpiresAt(\DateTime $expiresAt): Job
    {
        $this->expiresAt = $expiresAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return Job
     */
    public function setCategory(Category $category): Job
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();

        if (!$this->expiresAt) {
            $this->expiresAt = (clone $this->createdAt)->modify('+30 days');
        }
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("logo_path")
     *
     * @return string|null
     */
    public function getLogoPath()
    {
        return $this->getLogo() ? 'uploads/jobs/' . $this->getLogo() : null;
    }

    /**
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("category_name")
     *
     * @return string|null
     */
    public function getCategoryName()
    {
        return $this->getCategory()->getName();
    }
}