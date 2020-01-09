<?php
namespace Magenest\Notification\Model\ResourceModel\MagenestNotification;

class Collection extends
    \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

    public function _construct() {
        $this->_init('Magenest\Notification\Model\MagenestNotification',
            'Magenest\Notification\Model\ResourceModel\MagenestNotification');
    }
}