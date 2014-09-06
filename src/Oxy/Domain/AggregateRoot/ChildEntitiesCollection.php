<?php
/**
 * @category Oxy
 * @package  Oxy\Domain\AggregateRoot
 * @author   Tomas Bartkus <to.bartkus@gmail.com>
 */

namespace Oxy\Domain\AggregateRoot;

use Oxy\Core\Collection;

/**
 * Entities collection
 *
 * @category Oxy
 * @package  Oxy\Domain\AggregateRoot
 * @author   Tomas Bartkus <to.bartkus@gmail.com>
 **/
class ChildEntitiesCollection extends Collection
{
	/**
	 * @param array $collectionItems
	 */
	public function __construct(array $collectionItems = array())
	{
		parent::__construct(
			'Oxy\Domain\AggregateRoot\ChildEntityInterface',
			$collectionItems
		);		
	}    
	
    /**
     * Add collection
     *
     * @param Collection $collection $collection
     * @throws \InvalidArgumentException when wrong type
     *
     * @return void
     */
    public function addCollection(Collection $collection)
    {
        foreach($collection as $value){
            /** @var ChildEntityInterface $value */
            $this->set(
                (string)$value->getGuid(), 
                $value
            );
        }
    }
}