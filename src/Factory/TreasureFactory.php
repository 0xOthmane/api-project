<?php

namespace App\Factory;

use App\Entity\Treasure;
use App\Repository\TreasureRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Treasure>
 *
 * @method        Treasure|Proxy create(array|callable $attributes = [])
 * @method static Treasure|Proxy createOne(array $attributes = [])
 * @method static Treasure|Proxy find(object|array|mixed $criteria)
 * @method static Treasure|Proxy findOrCreate(array $attributes)
 * @method static Treasure|Proxy first(string $sortedField = 'id')
 * @method static Treasure|Proxy last(string $sortedField = 'id')
 * @method static Treasure|Proxy random(array $attributes = [])
 * @method static Treasure|Proxy randomOrCreate(array $attributes = [])
 * @method static TreasureRepository|RepositoryProxy repository()
 * @method static Treasure[]|Proxy[] all()
 * @method static Treasure[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Treasure[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Treasure[]|Proxy[] findBy(array $attributes)
 * @method static Treasure[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Treasure[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class TreasureFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'coolFactor' => self::faker()->randomNumber(),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'description' => self::faker()->text(),
            'isPublished' => self::faker()->boolean(),
            'name' => self::faker()->text(255),
            'value' => self::faker()->randomNumber(),
            'owner'=> UserFactory::new()
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Treasure $treasure): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Treasure::class;
    }
}
