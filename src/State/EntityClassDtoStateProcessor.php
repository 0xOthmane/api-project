<?php

namespace App\State;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Doctrine\Common\State\RemoveProcessor;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\UserApi;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfonycasts\MicroMapper\MicroMapperInterface;

class EntityClassDtoStateProcessor implements ProcessorInterface
{

    public function __construct(
        #[Autowire(service: PersistProcessor::class)] private ProcessorInterface $persistProcessor,
        #[Autowire(service: RemoveProcessor::class)] private ProcessorInterface $removeProcessor,
        // private UserPasswordHasherInterface $userPasswordHasher,
        // private UserRepository $userRepository,
        private MicroMapperInterface $microMapper
    ) {
    }
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        // dd($operation);
        $stateOptions = $operation->getStateOptions();
        assert($stateOptions instanceof Options);
        $entityClass = $stateOptions->getEntityClass();
        // Handle the state
        // dd($data);
        assert($data instanceof UserApi);
        $entity = $this->mapDtoToEntity($data, $entityClass);
        if ($operation instanceof DeleteOperationInterface) {
            $this->removeProcessor->process($entity, $operation, $uriVariables, $context);
            return null;
        }
        $this->persistProcessor->process($entity, $operation, $uriVariables, $context);
        $data->id = $entity->getId();
        return $data;
    }

    private function mapDtoToEntity(object $dto, string $entityClass): object
    {
        // assert($dto instanceof UserApi);
        // if ($dto->id) {
        //     $entity = $this->userRepository->find($dto->id);
        //     if (!$entity) {
        //         throw new \Exception(sprintf('Entity %d not found', $dto->id));
        //     }
        // } else {
        //     $entity = new User();
        // }
        // $entity->setEmail($dto->email);
        // $entity->setUsername($dto->username);
        // if ($dto->password) {
        //     $entity->setPassword($this->userPasswordHasher->hashPassword($entity, $dto->password));
        // }
        // return $entity;
        return $this->microMapper->map($dto, $entityClass);
    }
}
