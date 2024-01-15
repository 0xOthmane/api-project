<?php

namespace App\State;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

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
        // dd($entities);
        dd(iterator_to_array($entities));
    }
}
