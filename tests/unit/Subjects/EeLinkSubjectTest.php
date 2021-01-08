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

use PHPUnit\Framework\TestCase;
use League\Event\EmitterInterface;
use TechDivision\Import\Utils\CacheKeys;
use TechDivision\Import\Utils\RegistryKeys;
use TechDivision\Import\Utils\EntityTypeCodes;
use Doctrine\Common\Collections\ArrayCollection;
use TechDivision\Import\Configuration\ExecutionContextInterface;
use TechDivision\Import\Configuration\PluginConfigurationInterface;
use TechDivision\Import\Configuration\SubjectConfigurationInterface;
use TechDivision\Import\Services\RegistryProcessorInterface;
use TechDivision\Import\Utils\Generators\GeneratorInterface;
use TechDivision\Import\Loaders\LoaderInterface;
use TechDivision\Import\Utils\Mappings\MapperInterface;

/**
 * Test class for the link subject implementation for th Magento 2 EE.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-link-ee
 * @link      http://www.techdivision.com
 */
class EeLinkSubjectTest extends TestCase
{

    /**
     * The subject we want to test.
     *
     * @var \TechDivision\Import\Product\Link\Ee\Subjects\EeLinkSubject
     */
    protected $subject;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    protected function setUp()
    {

        // create a mock registry processor
        $mockRegistryProcessor = $this->getMockBuilder(RegistryProcessorInterface::class)
            ->setMethods(get_class_methods(RegistryProcessorInterface::class))
            ->getMock();

        // create a generator
        $mockGenerator = $this->getMockBuilder(GeneratorInterface::class)
            ->setMethods(get_class_methods(GeneratorInterface::class))
            ->getMock();

        // mock the event emitter
        $mockEmitter = $this->getMockBuilder(EmitterInterface::class)
            ->setMethods(\get_class_methods(EmitterInterface::class))
            ->getMock();

        // create a mock loader instance
        $mockLoader = $this->getMockBuilder(LoaderInterface::class)->getMock();

        // create a mock mapper instance
        $mockMapper = $this->getMockBuilder(MapperInterface::class)->getMock();
        $mockMapper->method('map')->willReturn(EntityTypeCodes::CATALOG_PRODUCT);

        // create the subject to be tested
        $this->subject = new EeLinkSubject(
            $mockRegistryProcessor,
            $mockGenerator,
            new ArrayCollection(),
            $mockEmitter,
            $mockLoader,
            $mockMapper
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
                RegistryKeys::IMAGE_TYPES => array(),
                RegistryKeys::LINK_ATTRIBUTES => array(),
                RegistryKeys::TAX_CLASSES => array(),
                RegistryKeys::CATEGORIES => array(),
                RegistryKeys::ROOT_CATEGORIES => array(),
                RegistryKeys::DEFAULT_STORE => array(),
                RegistryKeys::CORE_CONFIG_DATA => array(),
                RegistryKeys::ENTITY_TYPES => array(),
                RegistryKeys::EAV_USER_DEFINED_ATTRIBUTES => array(
                    EntityTypeCodes::CATALOG_PRODUCT => array()
                )
            )
        );

        // load a mock processor
        $mockProcessor = $this->subject->getRegistryProcessor();
        $mockProcessor->expects($this->any())
            ->method('getAttribute')
            ->with(CacheKeys::STATUS)
            ->willReturn($status);

        // mock the execution context
        $mockExecutionContext = $this->getMockBuilder(ExecutionContextInterface::class)
            ->setMethods(get_class_methods(ExecutionContextInterface::class))
            ->getMock();
        $mockExecutionContext->expects($this->any())
            ->method('getEntityTypeCode')
            ->willReturn(EntityTypeCodes::CATALOG_PRODUCT);

        // mock the plugin configuration
        $mockPluginConfiguration = $this->getMockBuilder(PluginConfigurationInterface::class)
            ->setMethods(get_class_methods(PluginConfigurationInterface::class))
            ->getMock();
        $mockPluginConfiguration->expects($this->any())
            ->method('getExecutionContext')
            ->willReturn($mockExecutionContext);

        // create a mock subject configuration
        $mockSubjectConfiguration = $this->getMockBuilder(SubjectConfigurationInterface::class)
            ->setMethods(get_class_methods(SubjectConfigurationInterface::class))
            ->getMock();
        $mockSubjectConfiguration->expects($this->any())
            ->method('getPluginConfiguration')
            ->willReturn($mockPluginConfiguration);
        $mockSubjectConfiguration->expects($this->any())
            ->method('getCallbacks')
            ->willReturn(array());
        $mockSubjectConfiguration->expects($this->any())
            ->method('getHeaderMappings')
            ->willReturn(array());
        $mockSubjectConfiguration->expects($this->any())
            ->method('getImageTypes')
            ->willReturn(array());
        $mockSubjectConfiguration->expects($this->any())
            ->method('getFrontendInputCallbacks')
            ->willReturn(array());

        // set the configuration
        $this->subject->setConfiguration($mockSubjectConfiguration);

        // set-up the processor
        $this->subject->setUp(uniqid());

        // test the mapSkuToRowId() method
        $this->assertSame($rowId, $this->subject->mapSkuToRowId($sku));
    }
}
