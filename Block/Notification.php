<?php

namespace Magenest\Notification\Block;

use Amazon\Core\Helper\ClientIp\Proxy;
use Magento\Framework\View\Element\Template;

class Notification extends Template
{
    protected $notificationFactory;
    protected $customerSession;
    protected $customerRepository;
    protected $redirectFactory;

    public function __construct(Template\Context $context, array $data = [],
                                \Magenest\Notification\Model\ResourceModel\MagenestNotification\CollectionFactory $notificationFactory,
                                \Magento\Customer\Model\Session $customerSession,
                                \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
                                \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory

    )
    {
        $this->notificationFactory = $notificationFactory;
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->redirectFactory = $redirectFactory;
        parent::__construct($context, $data);
    }

    // return array notification
    public function getNotification()
    {
        $customerId = $this->customerSession->getId();
        $customer = $this->customerRepository->getById($customerId);

        $customAttr = $customer->getCustomAttributes();
        if(isset($customAttr["notification_received"]) && isset($customAttr["notification_viewed"])){
            $notificationReceived = $customAttr["notification_received"]->getValue();
            $notificationViewed = $customAttr["notification_viewed"]->getValue();

            $arrayNotificationReceivedId = array_filter(array_unique(explode(",", $notificationReceived), SORT_STRING), 'strlen');
            $arrayNotificationViewedId = array_unique(explode(",", $notificationViewed), SORT_STRING);
            $notifications = array();
            foreach ($arrayNotificationReceivedId as $item) {
                if(is_numeric($item))
                {
                    $notification = $this->notificationFactory->create()->getItemById($item)->getData();
                    if (in_array($item, $arrayNotificationViewedId))
                        array_push($notification, "viewed");
                    else
                        array_push($notification, "unviewed");
                    array_push($notifications, $notification);
                }
            }
            return $notifications;
        }

    }

    public function viewDetailUrl($url)
    {
        $resultRedirect = $this->redirectFactory->create();
        return $resultRedirect->setPath($url);
    }

    public function setNotification($actionName, $actionID)
    {
        if (!empty($actionName) && !empty($actionID)) {
            $customerId = $this->customerSession->getId();
            $customer = $this->customerRepository->getById($customerId);
            $customAttr = $customer->getCustomAttributes();
            if ($actionName == "maskasread") {
                $notificationViewed = $customAttr["notification_viewed"]->getValue();
                $customer->setCustomAttribute("notification_viewed", $notificationViewed . "," . $actionID);
            }
            if ($actionName == "delete") {
                $notificationReceived = $customAttr["notification_received"]->getValue();
                $notificationViewed = $customAttr["notification_viewed"]->getValue();
                $notificationReceived = str_replace($actionID, "", $notificationReceived);
                $notificationViewed = str_replace($actionID, "", $notificationViewed);
                $customer->setCustomAttribute("notification_received", $notificationReceived);
                $customer->setCustomAttribute("notification_viewed", $notificationViewed);
            }
            $this->customerRepository->save($customer);
        }
        return;
    }
}