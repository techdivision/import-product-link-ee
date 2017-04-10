<?php

/**
 * TechDivision\Import\Product\Link\Ee\Subjects\EeLinkSubject
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-link-ee
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Link\Ee\Subjects;

use TechDivision\Import\Utils\RegistryKeys;
use TechDivision\Import\Product\Link\Subjects\LinkSubject;

/**
 * A subject implementation that provides extended functionality for importing
 * product links in Magento 2 EE edition.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-link-ee
 * @link      http://www.techdivision.com
 */
class EeLinkSubject extends LinkSubject
{

    /**
     * The mapping for the SKUs to the created entity IDs.
     *
     * @var array
     */
    protected $skuRowIdMapping = array();

    /**
     * Intializes the previously loaded global data for exactly one variants.
     *
     * @param string $serial The serial of the actual import
     *
     * @return void
     * @see \Importer\Csv\Actions\ProductImportAction::prepare()
     */
    public function setUp($serial)
    {

        // load the entity manager and the registry processor
        $registryProcessor = $this->getRegistryProcessor();

        // load the status of the actual import process
        $status = $registryProcessor->getAttribute($this->serial);

        // load the attribute set we've prepared intially
        $this->skuRowIdMapping = $status[RegistryKeys::SKU_ROW_ID_MAPPING];

        // prepare the callbacks
        parent::setUp($serial);
    }

    /**
     * Return the row ID for the passed SKU.
     *
     * @param string $sku The SKU to return the row ID for
     *
     * @return integer The mapped row ID
     * @throws \Exception Is thrown if the SKU is not mapped yet
     */
    public function mapSkuToRowId($sku)
    {

        // query weather or not the SKU has been mapped
        if (isset($this->skuRowIdMapping[$sku])) {
            return $this->skuRowIdMapping[$sku];
        }

        // throw an exception if the SKU has not been mapped yet
        throw new \Exception(sprintf('Found not mapped SKU %s', $sku));
    }
}
