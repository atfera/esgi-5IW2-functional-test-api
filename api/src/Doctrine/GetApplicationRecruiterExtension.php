<?php
// api/src/Doctrine/CurrentUserExtension.php

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Application;
use App\Entity\Offer;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

final class GetApplicationRecruiterExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        // TODO: Implement applyToCollection() method.
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = []): void
    {
        if (Application::class === $resourceClass && $this->security->isGranted('ROLE_RECRUITER') && $operationName === 'get') {
            $user = $this->security->getUser();
            $rootAlias = $queryBuilder->getRootAliases()[0];
            $queryBuilder->innerJoin(sprintf('%s.offer ', $rootAlias), 'offer' );
            $queryBuilder->innerJoin('offer.recruiter ', 'r','WITH','r.id = :current_user' );
            $queryBuilder->setParameter('current_user', $user->getId());
        }

    }

}
