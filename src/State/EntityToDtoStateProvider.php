<?php

namespace App\State;

use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use UserApi;

class EntityToDtoStateProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: CollectionProvider::class)] private ProviderInterface $collectionProvider
    ) {
    }
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        // Retrieve the state from somewhere
        $entities = $this->collectionProvider->provide($operation, $uriVariables, $context);
        assert($entities instanceof Paginator);
        // dd($entities);
        // dd(iterator_to_array($entities));
        $dtos = [];
        foreach ($entities as $entity) {
            $dtos[] = $this->mapEntityToDto($entity);
        }
        // return $dtos;
        return new TraversablePaginator(
            new \ArrayIterator($dtos),
            $entities->getCurrentPage(),
            $entities->getItemsPerPage(),
            $entities->getTotalItems()
        );
    }

    private function mapEntityToDto(object $entity): object
    {
        $dto = new UserApi();
        $dto->id = $entity->getId();
        $dto->email = $entity->getEmail();
        $dto->username = $entity->getUsername();
        $dto->treasures = $entity->getDragonTreasures()->toArray();
        $dto->throwingDistance = rand(1, 10);
        return $dto;
    }
}
