<?php

declare(strict_types=1);

namespace WMZ\Bypass2FA\Model;

use Magento\Framework\Exception\AuthenticationException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Integration\Api\AdminTokenServiceInterface;
use Magento\TwoFactorAuth\Api\TfaInterface;
use Magento\TwoFactorAuth\Api\UserConfigRequestManagerInterface;
use Magento\TwoFactorAuth\Model\AdminAccessTokenService as MagentoAdminAccessTokenService;
use Magento\User\Model\UserFactory;
use WMZ\Bypass2FA\Helper\Data;

class AdminAccessTokenService extends MagentoAdminAccessTokenService
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @var AdminTokenServiceInterface
     */
    private $adminTokenService;

    /**
     * AdminAccessTokenService constructor.
     * @param TfaInterface $tfa
     * @param UserConfigRequestManagerInterface $configRequestManager
     * @param UserFactory $userFactory
     * @param AdminTokenServiceInterface $adminTokenService
     * @param Data $helperData
     */
    public function __construct(
        TfaInterface $tfa,
        UserConfigRequestManagerInterface $configRequestManager,
        UserFactory $userFactory,
        AdminTokenServiceInterface $adminTokenService,
        Data $helperData
    ) {
        parent::__construct($tfa, $configRequestManager, $userFactory, $adminTokenService);
        $this->adminTokenService = $adminTokenService;
        $this->helperData = $helperData;
    }

    /**
     * @param string $username
     * @param string $password
     * @return string
     * @throws AuthenticationException
     * @throws LocalizedException
     * @throws InputException
     */
    public function createAdminAccessToken(
        $username,
        $password
    ): string {
        $twoFAByPassUsers = explode(",", $this->helperData->get2FAByPassUsers());
        if (in_array($username, $twoFAByPassUsers)) {
            return $this->adminTokenService->createAdminAccessToken($username, $password);
        } else {
            return parent::createAdminAccessToken($username, $password);
        }
    }
}
