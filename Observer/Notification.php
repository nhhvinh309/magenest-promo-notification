<?php

namespace Magenest\Notification\Observer;

use Magento\Framework\Event\ObserverInterface;

class Notification implements ObserverInterface
{
    protected $collectionCustomer;
    protected $customerRepository;

    public function __construct
    (
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $collectionCustomer
    )
    {
        $this->customerRepository = $customerRepository;
        $this->collectionCustomer = $collectionCustomer;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $eventData = $observer->getData();
        $idNotification = $eventData["eventData"][0];
        $status = $eventData["eventData"][1];
        if ($status == "enable") {
            $listCustomer = $this->collectionCustomer->create()->getData();
            forEach ($listCustomer as $item) {
                $customerId = $item['entity_id'];
                $customer = $this->customerRepository->getById($customerId);
                $current = $customer->getCustomAttribute('notification_received');
                if(empty($current))
                {
                    $customer->setCustomAttribute('notification_received', $idNotification);
                    $customer->setCustomAttribute('notification_viewed', " ");
                }
                else
                    $customer->setCustomAttribute('notification_received', $current->getValue() .",".$idNotification);
                $this->customerRepository->save($customer);
            }
        }
    }
}