<?php
//namespace VinhNguyen\HelloWorld\Controller\Adminhtml\Subscription;
//
//use Magento\Framework\Controller\ResultFactory;
//
//class NewAction extends \Magento\Backend\App\Action
//{
//    /**
//     * @return \Magento\Backend\Model\View\Result\Page
//     */
//    public function execute()
//    {
//        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
//        return $resultPage;
//    }
//}


namespace Magenest\Notification\Controller\Adminhtml\Notifications;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class NewAction extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('New Notification'));
        return $resultPage;
    }
}