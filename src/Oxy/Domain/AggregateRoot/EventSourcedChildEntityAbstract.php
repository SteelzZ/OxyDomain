<?php
/**
 * @category Oxy
 * @package  Oxy\Domain\AggregateRoot
 * @author   Tomas Bartkus <to.bartkus@gmail.com>
 */

namespace Oxy\Domain\AggregateRoot;

use Oxy\Domain\Entity\EventSourcedAbstract;
use Oxy\Core\Guid;
use Oxy\EventStore\Event\EventInterface;

/**
 * Event sourced child entity
 * Base class
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Entity
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
abstract class EventSourcedChildEntityAbstract extends EventSourcedAbstract implements ChildEntityInterface
{    
    /**
     * @var AggregateRootInterface
     */
    protected $_aggregateRoot;
    
    /**
     * @return Guid
     */
    public function getGuid()
    {
        return $this->_guid;
    }

    /**
     * @return AggregateRootInterface
     */
    public function getAggregateRoot()
    {
        return $this->_aggregateRoot;
    }

    /**
     * Init
     *
     * @param Guid                   $guid
     * @param string                 $realIdentifier
     * @param AggregateRootInterface $aggregateRoot
     *
     * @return EventSourcedChildEntityAbstract
     */
    public function __construct(
        Guid $guid,
        $realIdentifier,
        AggregateRootInterface $aggregateRoot = null
    ) 
    {
        parent::__construct($guid, $realIdentifier);
        $this->_aggregateRoot = $aggregateRoot;
    }
 
	/**
     * Handle events
     *
     * @param EventInterface $event
     *
     * @return void
     */
    protected function _handleEvent(EventInterface $event)
    {
        // This should not be called when loading from history
        // because if event was applied and we are loading from history
        // just load it do not add it to applied events collection
        // Add event to to applied collection
        // those will be persisted
        $this->_aggregateRoot->registerChildEntityEvent($this, $event);
        // Apply event - change state
        $this->_apply($event);
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->_guid;
    }
}