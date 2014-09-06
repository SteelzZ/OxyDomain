<?php
/**
 * @category Oxy
 * @package  Oxy\Domain
 * @author   Tomas Bartkus <to.bartkus@gmail.com>
 */

namespace Oxy\Domain;

use Oxy\Core\Guid;

/**
 * Interface EntityInterface
 *
 * @category Oxy
 * @package Oxy\Domain
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface EntityInterface
{
   /**
     * Returns unique identifier
     * 
     * @return Guid
     */
    public function getGuid();
}