<?php
namespace Magenest\Notification\Controller\Adminhtml\Notifications;

use Magento\Framework\App\Action\Context;

class Delete extends \Magento\Framework\App\Action\Action
{
    protected $collectionCustomer;
    protected $customerRepository;
    protected $_model;
    protected $messageManager;
    public function __construct(Context $context,
                                \Magenest\Notification\Model\MagenestNotificationFactory $model,
                                \Magento\Framework\Message\ManagerInterface $messageManager,
                                \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
                                \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $collectionCustomer)
    {
        parent::__construct($context);
        $this->messageManager = $messageManager;
        $this->_model = $model;
        $this->customerRepository = $customerRepository;
        $this->collectionCustomer = $collectionCustomer;
    }

    public function execute()
    {
        $requestData = $this->getRequest()->getParams();
        $delete_id= $requestData['id'];
        $model = $this->_model->create();
        $model->load($delete_id);
        $model->delete();
        $this->updateCustomer($delete_id);
        $this->messageManager->addSuccessMessage( __('Record deleted Successfully !'));
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }
    public function updateCustomer($idNotification)
    {
        $listCustomer = $this->collectionCustomer->create()->getData();
        forEach ($listCustomer as $item) {
            $customerId = $item['entity_id'];
            $customer = $this->customerRepository->getById($customerId);
            $currentReceived = $customer->getCustomAttribute('notification_received')->getValue();
            $currentViewed = $customer->getCustomAttribute('notification_viewed')->getValue();
            $currentReceived = str_replace($idNotification,"",$currentReceived);
            $currentViewed = str_replace($idNotification,"",$currentViewed);
            $customer->setCustomAttribute('notification_received', $currentReceived);
            $customer->setCustomAttribute('notification_viewed', $currentViewed);
            $this->customerRepository->save($customer);
        }
    }
}


