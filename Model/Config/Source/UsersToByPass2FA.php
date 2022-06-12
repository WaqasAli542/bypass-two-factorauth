<?php

declare(strict_types=1);

namespace WMZ\Bypass2FA\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\User\Model\ResourceModel\User\CollectionFactory;

class UsersToByPass2FA implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    protected $userCollectionFactory;

    /**
     * UsersToByPass2FA constructor.
     * @param CollectionFactory $userCollectionFactory
     */
    public function __construct(
        CollectionFactory $userCollectionFactory
    ) {
        $this->userCollectionFactory = $userCollectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $adminUsers = [];
        $userCollection = $this->userCollectionFactory->create();

        foreach ($userCollection as $user) {
            $adminUsers[] = [
                'label' => $user->getName() . ' (' . $user->getUserName() . ')',
                'value' => $user->getUserName()
            ];
        }
        return $adminUsers;
    }
}
