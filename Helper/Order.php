<?php

namespace EasyAndFaster\SuccessTester\Helper;

use Magento\Eav\Model\Entity\Collection\AbstractCollection;


class Order
{
    
    protected $orderFactory;

    protected $orderRepository;

    protected $searchCriteriaBuilder;

    public function __construct(
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Framework\Api\Search\SearchCriteriaBuilder $searchCriteriaBuilder
    )
    {
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @return \Magento\Sales\Api\Data\OrderInterface
     */
    public function getEmptyOrder()
    {
        return $this->orderFactory->create();
    }

    /**
     * @param $orderId
     *
     * @return \Magento\Sales\Api\Data\OrderInterface
     */
    public function getOrderById($orderId)
    {
        if (empty($orderId)) {
            echo 'Order id is not exsist.';
        }

        try {
            return $this->orderRepository->get($orderId);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * @return \Magento\Sales\Api\Data\OrderSearchResultInterface
     */
    protected function getOrderCollection()
    {
        $searchCriteriaBuilder = $this->searchCriteriaBuilder;
        $searchCriteriaBuilder->addSortOrder('created_at', AbstractCollection::SORT_ORDER_DESC);

        $searchCriteria = $searchCriteriaBuilder->create();
        $searchCriteria->setPageSize(1);
        $searchCriteria->setCurrentPage(0);
        $searchCriteria->getSortOrders();

        $orders = $this->orderRepository->getList($searchCriteria);

        return $orders;
    }
}