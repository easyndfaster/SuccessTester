<?php

namespace EasyAndFaster\SuccessTester\Controller\Index;

/**
 * CheckoutTester frontend controller
 *
 * @category    CheckoutTester2
 * @package Yireo\CheckoutTester2\Controller\Index
 */
class Index extends \Magento\Framework\App\Action\Action
{
    
    protected $resultPageFactory;

    protected $registry;

    protected $checkoutSession;

    protected $moduleHelper;

    protected $orderHelper;
    
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Checkout\Model\Session $checkoutSession,
        \EasyAndFaster\SuccessTester\Helper\Data $moduleHelper,
        \EasyAndFaster\SuccessTester\Helper\Order $orderHelper
    )
    {
        parent::__construct($context);

        $this->resultPageFactory = $resultPageFactory;
        $this->registry = $registry;
        $this->checkoutSession = $checkoutSession;
        $this->moduleHelper = $moduleHelper;
        $this->orderHelper = $orderHelper;
    }

   
    public function execute()
    {
       
        $orderId = (int)$this->getRequest()->getParam('order_id');

        // Fetch the order
        $order = $this->getOrder($orderId);

        // Fail when there is no valid order
        if (!$order->getEntityId()) {
            echo 'Invalid order ID';
        }
        else{
            $this->setOrder($order);
        }


        $resultPage = $this->resultPageFactory->create();
        $resultPage->addHandle('checkout_onepage_success');

        return $resultPage;
        
    }

    /**
     * Method to fetch the current order
     *
     * @return \Magento\Sales\Api\Data\OrderInterface
     */
    protected function getOrder($orderId)
    {
        
        $order = $this->orderHelper->getOrderById($orderId);

        if ($order->getEntityId()) {
            return $order;
        }
        else{
            echo 'Requested entity doesn\'t exist ';
        }
        
    }

    protected function setOrder(\Magento\Sales\Api\Data\OrderInterface $order)
    {
        
        $currentOrder = $this->registry->registry('current_order');
        if (empty($currentOrder)) {
            $this->registry->register('current_order', $order);
        }
       
        $this->checkoutSession->setLastOrderId($order->getEntityId())
            ->setLastRealOrderId($order->getIncrementId());

        $this->dispatchEvents($order);
    }

   
    public function dispatchEvents(\Magento\Sales\Api\Data\OrderInterface $order)
    {
        $this->_eventManager->dispatch('checkout_onepage_controller_success_action', array('order_ids' => array($order->getEntityId())));
    }
    
}