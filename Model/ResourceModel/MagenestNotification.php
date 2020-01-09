<?php
namespace Magenest\Notification\Model\ResourceModel;
class MagenestNotification extends
    \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
    public function _construct() {
        $this->_init('magenest_notification','entity_id');
    }
}