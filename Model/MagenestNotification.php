<?php
namespace Magenest\Notification\Model;
class MagenestNotification extends
    \Magento\Framework\Model\AbstractModel {
    public function _construct() {
        $this->_init('Magenest\Notification\Model\ResourceModel\MagenestNotification');
    }
}