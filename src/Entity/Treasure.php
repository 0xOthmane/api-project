<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use App\Repository\TreasureRepository;
use Carbon\Carbon;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use function Symfony\Component\String\u;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TreasureRepository::class)]
#[ApiResource(
    // uriTemplate:'/treasure/{id}'
    operations: [
        new Get(
            normalizationContext: [
                'groups' => ['treasure:read', 'treasure:item:get'],
            ],
        ),
        new GetCollection(),
        new Post(
            security: 'is_granted("ROLE_TREASURE_CREATE")'
        ),
        new Put(
            security: 'is_granted("ROLE_TREASURE_EDIT")'
        ),
        new Patch(
            security: 'is_granted("ROLE_TREASURE_EDIT") and object.getOwner() == user',
            securityPostDenormalize: 'object.getOwner() == user',
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN")'
        )
    ],
    normalizationContext: [
        'groups' => ['treasure:read'],
    ],
    denormalizationContext: [
        'groups' => ['treasure:write'],
    ],
    formats: [
        'jsonld',
        'json',
        'html',
        'csv' => 'text/csv',
    ],
)]
#[ApiResource(
    uriTemplate: '/users/{user_id}/treasures.{_format}',
    operations: [new GetCollection()],
    uriVariables: [
        'user_id' => new Link(
            fromProperty: 'dragonTreasures',
            fromClass: User::class,
        ),
    ],
)]
#[ApiFilter(BooleanFilter::class, properties: ['isPublished'])]
#[ApiFilter(PropertyFilter::class)]
#[ApiFilter(SearchFilter::class, properties: [
    'owner.username' => 'partial',
])]
class Treasure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['treasure:read', 'treasure:write', 'user:read'])]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50, maxMessage: 'Describe your loot in 50 chars or less')]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['treasure:read', 'treasure:write'])]
    private ?string $description = null;

    /**
     * Add description to a value
     */
    #[ORM\Column]
    #[Groups(['treasure:read', 'treasure:write'])]
    #[ApiFilter(RangeFilter::class)]
    private ?int $value = null;

    #[ORM\Column]
    #[Groups(['treasure:read', 'treasure:write'])]
    private ?int $coolFactor = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column]
    private ?bool $isPublished = false;

    #[ORM\ManyToOne(inversedBy: 'treasures')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['treasure:read', 'treasure:write'])]
    private ?User $owner = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * A human readable represtation of creation date
     *
     * @return string
     */
    #[Groups(['treasure:read'])]
    public function getPlunderedAtAgo(): string
    {
        return Carbon::instance(new \DateTime($this->getCreatedAt()->format(\DateTime::ATOM)))->diffForHumans();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    #[Groups(['treasure:read'])]
    public function getShortDescription(): ?string
    {
        return u($this->description)->truncate(40, '...');
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getCoolFactor(): ?int
    {
        return $this->coolFactor;
    }

    public function setCoolFactor(int $coolFactor): static
    {
        $this->coolFactor = $coolFactor;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
