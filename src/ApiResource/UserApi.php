<?php

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\User;
use App\State\EntityClassDtoStateProcessor;
use App\State\EntityToDtoStateProvider;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[ApiResource(
    shortName: 'User',
    // provider: CollectionProvider::class
    provider: EntityToDtoStateProvider::class,
    processor: EntityClassDtoStateProcessor::class,
    paginationItemsPerPage: 5,
    stateOptions: new Options(entityClass: User::class),
    // normalizationContext: [AbstractNormalizer::IGNORED_ATTRIBUTES => ['throwingDistance']],
    // denormalizationContext: [AbstractNormalizer::IGNORED_ATTRIBUTES => ['throwingDistance']],
)]
class UserApi
{
    #[ApiProperty(readable: false, writable: false, identifier: true)]
    public ?int $id = null;

    public ?string $email = null;

    public ?string $username = null;

    #[ApiProperty(readable: false)]
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
