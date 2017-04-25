<?php

/**
 * TechDivision\Import\Product\Link\Ee\Subjects\EeLinkSubjectTest
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
use TechDivision\Import\Utils\EntityTypeCodes;

/**
 * Test class for the link subject implementation for th Magento 2 EE.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-link-ee
 * @link      http://www.techdivision.com
 */
class EeLinkSubjectTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The subject we want to test.
     *
     * @var \TechDivision\Import\Product\Ling\Ee\Subjects\EeLinkSubject
     */
    protected $subject;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {


        // create a mock configuration instance
        $mockConfiguration =  $this->getMockBuilder('TechDivision\Import\ConfigurationInterface')
                                   ->setMethods(get_class_methods('TechDivision\Import\ConfigurationInterface'))
                                   ->getMock();
        $mockConfiguration->expects($this->once())
                          ->method('getEntityTypeCode')
                          ->willReturn(EntityTypeCodes::CATALOG_PRODUCT);

        // create a mock subject configuration
        $mockSubjectConfiguration = $this->getMockBuilder('TechDivision\Import\Configuration\SubjectConfigurationInterface')
                                         ->setMethods(get_class_methods('TechDivision\Import\Configuration\SubjectConfigurationInterface'))
                                         ->getMock();
        $mockSubjectConfiguration->expects($this->any())
                                 ->method('getConfiguration')
                                 ->willReturn($mockConfiguration);
        $mockSubjectConfiguration->expects($this->any())
                                 ->method('getCallbacks')
                                 ->willReturn(array());
        // create a mock registry processor
        $mockRegistryProcessor = $this->getMockBuilder('TechDivision\Import\Services\RegistryProcessorInterface')
                                      ->setMethods(get_class_methods('TechDivision\Import\Services\RegistryProcessorInterface'))
                                      ->getMock();

        // create a mock product processor
        $mockProductProcessor = $this->getMockBuilder('TechDivision\Import\Product\Services\ProductBunchProcessorInterface')
                                     ->setMethods(get_class_methods('TechDivision\Import\Product\Services\ProductBunchProcessorInterface'))
                                     ->getMock();

        // create a generator
        $mockGenerator = $this->getMockBuilder('TechDivision\Import\Utils\Generators\GeneratorInterface')
                              ->setMethods(get_class_methods('TechDivision\Import\Utils\Generators\GeneratorInterface'))
                              ->getMock();

        // create the subject to be tested
        $this->subject = new EeLinkSubject(
            $mockSubjectConfiguration,
            $mockRegistryProcessor,
            $mockGenerator,
            array(),
            $mockProductProcessor
        );
    }

    /**
     * Test's the persistUrlRewrite() method successfull.
     *
     * @return void
     */
    public function testMapSkuToRowIdSuccessufull()
    {

        // initialize a mock status
        $status = array(
            RegistryKeys::SKU_ROW_ID_MAPPING => array($sku = 'TEST-01' => $rowId = 1000),
            RegistryKeys::SKU_ENTITY_ID_MAPPING => array(),
            RegistryKeys::GLOBAL_DATA => array(
                RegistryKeys::SKU_ENTITY_ID_MAPPING => array(),
                RegistryKeys::ATTRIBUTE_SETS => array(),
                RegistryKeys::STORE_WEBSITES => array(),
                RegistryKeys::EAV_ATTRIBUTES => array(),
                RegistryKeys::STORES => array(),
                RegistryKeys::LINK_TYPES => array(),
                RegistryKeys::LINK_ATTRIBUTES => array(),
                RegistryKeys::TAX_CLASSES => array(),
                RegistryKeys::CATEGORIES => array(),
                RegistryKeys::ROOT_CATEGORIES => array(),
                RegistryKeys::DEFAULT_STORE => array(),
                RegistryKeys::CORE_CONFIG_DATA => array(),
                RegistryKeys::EAV_USER_DEFINED_ATTRIBUTES => array(
                    EntityTypeCodes::CATALOG_PRODUCT => array()
                )
            )
        );

        // load a mock processor
        $mockProcessor = $this->subject->getRegistryProcessor();
        $mockProcessor->expects($this->any())
                      ->method('getAttribute')
                      ->with($serial = uniqid())
                      ->willReturn($status);

        // set-up the processor
        $this->subject->setUp($serial);

        // test the mapSkuToRowId() method
        $this->assertSame($rowId, $this->subject->mapSkuToRowId($sku));
    }
}
