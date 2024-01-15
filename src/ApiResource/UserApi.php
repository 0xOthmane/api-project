<?php

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\User;
use App\State\EntityClassDtoStateProcessor;
use App\State\EntityToDtoStateProvider;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'User',
    // provider: CollectionProvider::class
    provider: EntityToDtoStateProvider::class,
    processor: EntityClassDtoStateProcessor::class,
    paginationItemsPerPage: 5,
    stateOptions: new Options(entityClass: User::class),
    // normalizationContext: [AbstractNormalizer::IGNORED_ATTRIBUTES => ['throwingDistance']],
    // denormalizationContext: [AbstractNormalizer::IGNORED_ATTRIBUTES => ['throwingDistance']],
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            security: 'is_granted("PUBLIC_ACCESS")',
            validationContext: ['groups' => ['Default', 'postValidation']]
        ),
        new Patch(
            security: 'is_granted("ROLE_USER_EDIT")'
        ),
        new Delete(),
    ],
    security: 'is_granted("ROLE_USER")',
)]
class UserApi
{
    #[ApiProperty(readable: false, writable: false, identifier: true)]
    public ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    public ?string $email = null;

    #[Assert\NotBlank]
    public ?string $username = null;

    #[ApiProperty(readable: false)]
    #[Assert\NotBlank(groups: ['postValidation'])]
    public ?string $password = null;

    /**
     * @var array<int, Treasure>
     */
    #[ApiProperty(writable: false)]
    public array $treasures = [];

    // #[ApiProperty(readable: false, writable: false)]
    #[ApiProperty(writable: false)]
    public int $throwingDistance = 0;
}
