<?php

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\User;
use App\State\EntityClassDtoStateProcessor;
use App\State\EntityToDtoStateProvider;

#[ApiResource(
    shortName: 'User',
    // provider: CollectionProvider::class
    provider: EntityToDtoStateProvider::class,
    processor: EntityClassDtoStateProcessor::class,
    paginationItemsPerPage: 5,
    stateOptions: new Options(entityClass: User::class)
)]
class UserApi
{
    public ?int $id = null;

    public ?string $email = null;

    public ?string $username = null;

    public ?string $password = null;

    /**
     * @var array<int, Treasure>
     */
    public array $treasures = [];

    public int $throwingDistance = 0;
}
