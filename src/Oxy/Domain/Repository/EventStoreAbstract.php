<?php
/**
 * @category Oxy
 * @package  Oxy\Domain\Repository
 * @author   Tomas Bartkus <to.bartkus@gmail.com>
 */

namespace Oxy\Domain\Repository;

use Oxy\EventStore\EventStoreInterface as EventStoreEventStoreInterface;
use Oxy\EventStore\EventPublisher\EventPublisherInterface;
use Oxy\EventStore\EventProvider\EventProviderInterface;
use Oxy\Core\Guid;


/**
 * Event store domain repository
 *
 * @category Oxy
 * @package  Oxy\Domain\Repository
 * @author   Tomas Bartkus <to.bartkus@gmail.com>
 */
abstract class EventStoreAbstract implements EventStoreInterface
{
    /**
     * @var EventStoreEventStoreInterface
     */
    protected $_eventStore;

    /**
     * @var EventPublisherInterface
     */
    protected $_eventsPublisher;

    /**
     * @param EventStoreEventStoreInterface     $eventStore
     * @param EventPublisherInterface           $eventsPublisher
     */
    public function __construct(EventStoreEventStoreInterface $eventStore, EventPublisherInterface $eventsPublisher)
    {
        $this->_eventStore = $eventStore;
        $this->_eventsPublisher = $eventsPublisher;
    }

    /**
     * @see EventStoreInterface::add()
     */
    public function add(EventProviderInterface $aggregateRoot)
    {
        $this->_eventStore->add($aggregateRoot);
        $this->_eventStore->commit();
        $this->_eventsPublisher->notifyListeners($aggregateRoot->getChanges());
    }

    /**
     * @see EventStoreInterface::getById()
     */
    public function getById($aggregateRootClassName, Guid $aggregateRootGuid, $realIdentifer)
    {
        try{
            // State will be loaded on this object
            $aggregateRoot = new $aggregateRootClassName(
                $aggregateRootGuid,
                $realIdentifer
            );
        } catch(Exception $ex) {
            throw new Exception(
                sprintf('Class of this entity was not found - %s', $aggregateRootClassName)
            );
        }
        
        try{
            $this->_eventStore->getById(
                $aggregateRootGuid,
                $aggregateRoot
            );
        } catch(Exception $ex){
            throw new Exception(
                sprintf('Could not load events on this entity - %s', $aggregateRootClassName)
            );
        }
        
        // OK return
        return $aggregateRoot;
    }
}