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
use TechDivision\Import\Product\Ee\Subjects\EeBunchSubject;

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
class EeLinkSubject extends EeBunchSubject
{

    /**
     * The temporary persisted last link ID.
     *
     * @var integer
     */
    protected $lastLinkId;

    /**
     * Intializes the previously loaded global data for exactly one variants.
     *
     * @param string $serial The serial of the actual import
     *
     * @return void
     */
    public function setUp($serial)
    {

        // invoke the parent method
        parent::setUp($serial);

        // load the entity manager and the registry processor
        $registryProcessor = $this->getRegistryProcessor();

        // load the status of the actual import process
        $status = $registryProcessor->getAttribute(RegistryKeys::STATUS);

        // load the SKU => row/entity ID mapping
        $this->skuRowIdMapping = $status[RegistryKeys::SKU_ROW_ID_MAPPING];
        $this->skuEntityIdMapping = $status[RegistryKeys::SKU_ENTITY_ID_MAPPING];
    }

    /**
     * Temporary persist the last link ID.
     *
     * @param integer $lastLinkId The last link ID
     *
     * @return void
     */
    public function setLastLinkId($lastLinkId)
    {
        $this->lastLinkId = $lastLinkId;
    }

    /**
     * Load the temporary persisted the last link ID.
     *
     * @return integer The last link ID
     */
    public function getLastLinkId()
    {
        return $this->lastLinkId;
    }
}
