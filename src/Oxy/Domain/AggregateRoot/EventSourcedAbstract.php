<?php
/**
 * @category Oxy
 * @package  Oxy\Domain\AggregateRoot
 * @author   Tomas Bartkus <to.bartkus@gmail.com>
 */

namespace Oxy\Domain\AggregateRoot;

use Oxy\Domain\Entity\EventSourcedAbstract as EntityEventSourcedAbstract;
use Oxy\Core\Guid;
use Oxy\EventStore\Event\EventInterface;
use Oxy\EventStore\Event\StoreableEvent;
use Oxy\EventStore\Event\StoreableEventsCollectionInterface;
use Oxy\EventStore\EventProvider\EventProviderInterface;
use Oxy\EventStore\Event\StoreableEventsCollection;
use Oxy\Domain\Exception as OxyDomainException;


/**
 * Event sourced Aggregate Root 
 * Base class
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage AggregateRoot
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
abstract class EventSourcedAbstract extends EntityEventSourcedAbstract implements AggregateRootInterface
{    
    /**
     * @var ChildEntitiesCollection
     */
    protected $_childEntities;
        
    /**
     * @return ChildEntitiesCollection
     */
    public function getChildEntities()
    {
        return $this->_childEntities;
    }
        
    /**
     * Initialize aggregate root
     * 
     * @param Guid   $guid
     * @param string $realIdentifier
     *
     * @return EventSourcedAbstract
     */
    public function __construct(Guid $guid, $realIdentifier)
    {
    	parent::__construct($guid, $realIdentifier);
        $this->_childEntities = new ChildEntitiesCollection();
    }

    /**
     * Register child entity event
     *
     * @param ChildEntityInterface $childEntity
     * @param EventInterface       $event
     *
     * @return void
     */
    public function registerChildEntityEvent(ChildEntityInterface $childEntity, EventInterface $event)
    {
        $this->_childEntities->set((string)$childEntity->getGuid(), $childEntity);
        $this->_appliedEvents->addEvent(
            new StoreableEvent($childEntity->getGuid(), $event)
        );
    }

    /**
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
        $this->_appliedEvents->addEvent(
            new StoreableEvent( $this->_guid, $event)
        );
                
        // Apply event - change state
        $this->_apply($event);
    }

    /**
     * Load events
     *
     * @param StoreableEventsCollectionInterface $domainEvents
     *
     * @throws \Oxy\Domain\Exception
     *
     * @return void
     */
    public function loadEvents(StoreableEventsCollectionInterface $domainEvents)
    {
        foreach ($domainEvents as $storeableEvent) {
            /** @var \Oxy\EventStore\Event\StoreableEventInterface $storeableEvent */
            $eventGuid = (string)$storeableEvent->getProviderGuid();
            if ($eventGuid === (string)$this->_guid) {
                $this->_apply($storeableEvent->getEvent());
            } else if ($this->_childEntities->exists($eventGuid)) {
                $childEntity = $this->_childEntities->get($eventGuid);
                if($childEntity instanceof EventProviderInterface){
                    $childEntity->loadEvents(
                        new StoreableEventsCollection(
                            array(
                                (string)$storeableEvent->getProviderGuid() => $storeableEvent->getEvent()
                            )
                        )
                    );
                } else {
                    throw new OxyDomainException(
                        sprintf(
                        	'Child entity must implement %s interface', 
                            'Oxy\EventStore\EventProvider\EventProviderInterface'
                        )
                    );
                }
            } else {
                throw new OxyDomainException(
                    sprintf('Child entity with guid %s does not exists', $storeableEvent->getProviderGuid())
                );
            }
        }
    }
}