<?php

namespace Magenest\Notification\Controller\Adminhtml\Notifications;

class Save extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $magenestNotificationFactory;
    protected $eventManager;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magenest\Notification\Model\MagenestNotificationFactory $magenestNotificationFactory,
        \Magento\Framework\EntityManager\EventManager $eventManager
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->magenestNotificationFactory = $magenestNotificationFactory;
        $this->eventManager = $eventManager;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $request = $this->getRequest()->getParams();
        $magenestNotification = $this->magenestNotificationFactory->create();
        if (!empty($request['entity_id'])) {
            $magenestNotification->setid($request['entity_id']);
        }
        try {
            $magenestNotification->setname($request['name']);
            $magenestNotification->setstatus($request['status']);
            $magenestNotification->setshort_description($request['short_description']);
            $magenestNotification->setredirect_url($request['redirect_url']);
            $magenestNotification->save();
            $idNotification = $magenestNotification->getId();

            $this->eventManager->dispatch('sent_notification', ['eventData' => [$idNotification, $request['status']]]);

            $this->messageManager->addSuccessMessage(__('Record saved successfully'));
            $this->resultPageFactory->create();
            return $resultRedirect->setPath('notification/notifications/index');

        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('We can\'t submit your request, Please try again.'));
            return $resultRedirect->setPath('notification/notifications');
        }
    }
}
