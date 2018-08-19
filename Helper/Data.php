<?php

namespace EasyAndFaster\SuccessTester\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    )
    {
        return parent::__construct($context);
    }

   
    public function isEnabled()
    {
        if ((bool)$this->getConfigValue('enabled')) {
            return true;
        }

        return false;
    }
   
    public function getConfigValue(string $key = '', $defaultValue = null, $prefix = true)
    {
        if ($prefix) {
            $key = 'successtester/settings/' . $key;
        }

        $value = $this->scopeConfig->getValue(
            $key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if (empty($value)) {
            $value = $defaultValue;
        }

        return $value;
    }
}
