<?php
/**
 * @category Oxy
 * @package  Oxy\Domain\Repository
 * @author   Tomas Bartkus <to.bartkus@gmail.com>
 */

namespace Steelzz\OxyDomain\Repository;

use Oxy\Core\Guid;
use Oxy\Domain\AggregateRoot\AggregateRootInterface;

/**
 * Interface RepositoryInterface
 *
 * @package Steelzz\OxyDomain\Repository
 * @author  Tomas Bartkus <to.bartkus@gmail.com>
 */
interface RepositoryInterface
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
     * @param AggregateRootInterface $aggregateRoot
     * 
     * @return void
     */
    public function add(AggregateRootInterface $aggregateRoot);
}