<?php
namespace Magenest\Notification\Controller\Adminhtml\Notifications;

use Magento\Framework\App\Action\Context;

class Delete extends \Magento\Framework\App\Action\Action
{
    protected $_model;
    protected $messageManager;
    public function __construct(Context $context,
                                \Magenest\Notification\Model\MagenestNotificationFactory $model,
                                \Magento\Framework\Message\ManagerInterface $messageManager)
    {
        parent::__construct($context);
        $this->messageManager = $messageManager;
        $this->_model = $model;
    }

    public function execute()
    {
        $requestData = $this->getRequest()->getParams();
        $delete_id= $requestData['id'];
        $model = $this->_model->create();
        $model->load($delete_id);
        $model->delete();
        $this->messageManager->addSuccessMessage( __('Record deleted Successfully !'));
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }
}


