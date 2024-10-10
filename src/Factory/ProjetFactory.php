<?php

namespace App\Factory;

use App\Entity\Company;
use App\Entity\Projet;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Projet>
 */
final class ProjetFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {

    }

    public static function class(): string
    {
        return Projet::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $date = \DateTimeImmutable::createFromMutable(self::faker()->dateTime());
        return [
            'creationDate' => $date,
            'updatedAt' => $date,
            'Description' => self::faker()->text(255),
            'title' => self::faker()->domainName,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Projet $projet): void {})
        ;
    }
}
