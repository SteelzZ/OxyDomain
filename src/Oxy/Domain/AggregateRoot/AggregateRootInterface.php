<?php
/**
 * @category Oxy
 * @package  Oxy\Domain\AggregateRoot
 * @author   Tomas Bartkus <to.bartkus@gmail.com>
 */

namespace Oxy\Domain\AggregateRoot;

use Oxy\Domain\EntityInterface;
use Oxy\EventStore\Event\EventInterface;

/**
 * @category Oxy
 * @package  Oxy\Domain\AggregateRoot
 * @author   Tomas Bartkus <to.bartkus@gmail.com>
 */
interface AggregateRootInterface extends EntityInterface
{    
    /**
     * @return ChildEntitiesCollection
     */
    public function getChildEntities();

    /**
     * Register child entity event
     *
     * @param ChildEntityInterface $childEntity
     * @param EventInterface       $event
     *
     * @return void
     */
    public function registerChildEntityEvent(ChildEntityInterface $childEntity, EventInterface $event);
}