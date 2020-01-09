<?php
namespace Magenest\Notification\Setup;

use \Magento\Framework\Setup\UpgradeDataInterface;
use \Magento\Framework\Setup\ModuleContextInterface;
use \Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Setup\EavSetup;
use Magento\Customer\Model\ResourceModel\Attribute as CustomerAttributeResourceModel;

class UpgradeData implements UpgradeDataInterface
{
    private $customerSetupFactory;
    private $eavConfig;
    private $eavSetup;
    private $customerAttributeResourceModel;

    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        EavConfig $eavConfig,
        EavSetup $eavSetup,
        CustomerAttributeResourceModel $customerAttributeResourceModel
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->eavConfig = $eavConfig;
        $this->eavSetup = $eavSetup;
        $this->customerAttributeResourceModel = $customerAttributeResourceModel;
    }
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '2.0.1', '<')) {
            $this->createMobileAttribute($setup);
        }
    }
    public function createMobileAttribute($setup)
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->addAttribute(
            Customer::ENTITY,
            'notification_received',
            [

                'label'                 => 'notification received',
                'input'                 => 'text',
                'required'              => false,
                'sort_order'            => 1000,
                'position'              => 1000,
                'visible'               => true,
                'system'                => false,
                'is_used_in_grid'       => true,
                'is_visible_in_grid'    => false,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => false
            ]
        );
        $customerSetup->addAttribute(
            Customer::ENTITY,
            'notification_viewed',
            [

                'label'                 => 'notification viewed',
                'input'                 => 'text',
                'required'              => false,
                'sort_order'            => 1001,
                'position'              => 1001,
                'visible'               => true,
                'system'                => false,
                'is_used_in_grid'       => true,
                'is_visible_in_grid'    => false,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => false
            ]
        );

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'notification_received');
        $attribute->setData('used_in_forms', ['adminhtml_customer', 'customer_account_create', 'customer_account_edit']);
        $attribute2 = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'notification_received');
        $attribute2->setData('used_in_forms', ['adminhtml_customer', 'customer_account_create', 'customer_account_edit']);

        $this->customerAttributeResourceModel->save($attribute);
        $this->customerAttributeResourceModel->save($attribute2);

        $setup->endSetup();
    }
}