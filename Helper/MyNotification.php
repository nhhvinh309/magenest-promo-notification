<?php

namespace Magenest\Notification\Helper;

use Magento\Framework\App\Helper\Context;

class MyNotification extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $customerRepository;
    protected $customerSession;
    public function __construct(Context $context,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\Session $customerSession
    )
    {
        $this->customerRepository = $customerRepository;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    public function getTitle()
    {
        $customerId = $this->customerSession->getId();
        $customerData = $this->customerRepository->getById($customerId);
        $customerAttr = $customerData->getCustomAttributes();
        if(isset( $customerAttr["notification_received"]) && isset( $customerAttr["notification_viewed"]))
        {
            $notificationReceived = $customerAttr["notification_received"]->getValue();
            $notificationViewed = $customerAttr["notification_viewed"]->getValue();


            $countNotificationReceived = count(array_filter(array_unique(explode(",",$notificationReceived)), function($arrayEntry) {
                return is_numeric($arrayEntry);
            }));
            $countNotificationViewed = count(array_filter(array_unique(explode(",",$notificationViewed)), function($arrayEntry) {
                return is_numeric($arrayEntry);
            }));
            $countNotificationUnViewed = $countNotificationReceived - $countNotificationViewed;
            return "My Notification (" .$countNotificationUnViewed . ")";
        }
        return "My Notification" ;
    }

}