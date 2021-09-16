<?php

/**
 * TechDivision\Import\Product\Link\Ee\Observers\EeLinkObserver
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-link-ee
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Link\Ee\Observers;

use TechDivision\Import\Product\Link\Observers\LinkObserver;

/**
 * Oberserver that provides functionality for the product link replace operation for the
 * Magento 2 EE edition.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-link-ee
 * @link      http://www.techdivision.com
 */
class EeLinkObserver extends LinkObserver
{

    /**
     * Return the row ID for the passed SKU.
     *
     * @param string $sku The SKU to return the row ID for
     *
     * @return integer The mapped row ID
     * @throws \Exception Is thrown if the SKU is not mapped yet
     */
    protected function mapSku($sku)
    {
        return $this->getSubject()->mapSkuToRowId($sku);
    }
}
