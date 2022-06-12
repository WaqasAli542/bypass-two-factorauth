<?php

declare(strict_types=1);

namespace WMZ\Bypass2FA\Observer;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Backend\Model\Auth\Session as AuthSession;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\UrlInterface;
use Magento\TwoFactorAuth\Api\TfaInterface;
use Magento\TwoFactorAuth\Api\TfaSessionInterface;
use Magento\TwoFactorAuth\Api\UserConfigRequestManagerInterface;
use Magento\TwoFactorAuth\Model\UserConfig\HtmlAreaTokenVerifier;
use Magento\TwoFactorAuth\Observer\ControllerActionPredispatch as TwoFAControllerActionPredispatch;
use WMZ\Bypass2FA\Helper\Data;

class ControllerActionPredispatch extends TwoFAControllerActionPredispatch
{
    /**
     * @var AuthSession
     */
    protected $authSession;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * ControllerActionPredispatch constructor.
     * @param TfaInterface $tfa
     * @param TfaSessionInterface $tfaSession
     * @param UserConfigRequestManagerInterface $configRequestManager
     * @param HtmlAreaTokenVerifier $tokenManager
     * @param ActionFlag $actionFlag
     * @param UrlInterface $url
     * @param AuthorizationInterface $authorization
     * @param UserContextInterface $userContext
     * @param AuthSession $authSession
     * @param Data $helperData
     */
    public function __construct(
        TfaInterface $tfa,
        TfaSessionInterface $tfaSession,
        UserConfigRequestManagerInterface $configRequestManager,
        HtmlAreaTokenVerifier $tokenManager,
        ActionFlag $actionFlag,
        UrlInterface $url,
        AuthorizationInterface $authorization,
        UserContextInterface $userContext,
        AuthSession $authSession,
        Data $helperData
    ) {
        parent::__construct(
            $tfa,
            $tfaSession,
            $configRequestManager,
            $tokenManager,
            $actionFlag,
            $url,
            $authorization,
            $userContext
        );
        $this->authSession = $authSession;
        $this->helperData = $helperData;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $allTwoFAByPassUsers = $this->helperData->get2FAByPassUsers();
        if ($allTwoFAByPassUsers == '' || $allTwoFAByPassUsers == null) {
            $twoFAByPassUsers = [];
        } else {
            $twoFAByPassUsers = explode(",", $allTwoFAByPassUsers);
        }
        if ($this->authSession->isLoggedIn()) {
            $currentUserName = $this->authSession->getUser()->getUserName();
            if (in_array($currentUserName, $twoFAByPassUsers)) {
                return;
            }
        }
        parent::execute($observer);
    }
}
