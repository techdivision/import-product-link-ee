<?php

/**
 * TechDivision\Import\Product\Variant\Ee\Observers\EeCleanUpLinkObserver
 *
 * PHP version 7
 *
 * @author    Martin Eisenführer <m.eisenfuehrer@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-variant
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Link\Ee\Observers;

use TechDivision\Import\Product\Link\Observers\CleanUpLinkObserver;

/**
 * Observer that cleaned up a product's link information.
 *
 * @author    Martin Eisenführer <m.eisenfuehrer@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-variant
 * @link      http://www.techdivision.com
 */
class EeCleanUpLinkObserver extends CleanUpLinkObserver
{
    /**
     * Return's the PK to create the product => link relation.
     *
     * @return integer The PK to create the relation with
     */
    protected function getLastPrimaryKey()
    {
        return $this->getSubject()->getLastRowId();
    }
}
