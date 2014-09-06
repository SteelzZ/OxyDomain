<?php
/**
 * @category Oxy
 * @package  Oxy\Domain\AggregateRoot
 * @author   Tomas Bartkus <to.bartkus@gmail.com>
 */

namespace Oxy\Domain\AggregateRoot;

use Oxy\Domain\EntityInterface;

/**
 * @category Oxy
 * @package  Oxy\Domain\AggregateRoot
 * @author   Tomas Bartkus <to.bartkus@gmail.com>
 */
interface ChildEntityInterface extends EntityInterface
{
    /**
     * @return AggregateRootInterface
     */
    public function getAggregateRoot();
}