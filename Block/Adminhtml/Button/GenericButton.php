<?php

namespace Magenest\Notification\Block\Adminhtml\Button;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\UrlInterface;

class GenericButton
{
    protected $urlBuilder;

    protected $context;

    public function __construct(
        Context $context
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->context = $context;
    }
    public function getReviewId()
    {
        return $this->context->getRequest()->getParam('id');
    }
    public function getUrl($route = '', $params = [])
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
    public function canRender($name)
    {
        return $name;
    }
}
