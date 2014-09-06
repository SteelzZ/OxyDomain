<?php
/**
 * @category Oxy
 * @package  Oxy\Domain\Repository
 * @author   Tomas Bartkus <to.bartkus@gmail.com>
 */

namespace Oxy\Domain\Repository;

use Oxy\Core\Guid;
use Oxy\EventStore\EventProvider\EventProviderInterface;
use Oxy\Domain\AggregateRoot\AggregateRootInterface;

/**
 * Domain repository interface
 *
 * @category Oxy
 * @package  Oxy\Domain\Repository
 * @author   Tomas Bartkus <to.bartkus@gmail.com>
 */
interface EventStoreInterface
{
    /**
     * Get Aggregate root by GUID
     * 
     * @param string $aggregateRootClassName
     * @param Guid   $aggregateRootGuid
     * @param string $realIdentifier
     * 
     * @return AggregateRootInterface
     */
    public function getById($aggregateRootClassName, Guid $aggregateRootGuid, $realIdentifier);

    /**
     * Save aggregate root
     *
     * @param EventProviderInterface $aggregateRoot
     * 
     * @return void
     */
    public function add(EventProviderInterface $aggregateRoot);
}