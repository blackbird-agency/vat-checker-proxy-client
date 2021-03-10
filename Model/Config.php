<?php
/*
 * Blackbird Vat Checker Proxy Client
 *
 * NOTICE OF LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@bird.eu so we can send you a copy immediately.
 * @category        Blackbird
 * @package         Blackbird_VatCheckerProxyClient
 * @copyright       Copyright (c) Blackbird (https://black.bird.eu)
 * @author          Blackbird Team
 * @license         MIT
 * @support         https://github.com/blackbird-agency/vat-checker-proxy-client/issues/new
 */

namespace Blackbird\VatCheckerProxyClient\Model;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Return config proxy url
     *
     * @return string
     */
    public function getProxyUrl()
    {
        return $this->scopeConfig->getValue(
            'blackbird_vat_checker_proxy_client/general/proxy_url',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Return config proxy url
     *
     * @return string
     */
    public function getToken()
    {
        return $this->scopeConfig->getValue(
            'blackbird_vat_checker_proxy_client/general/token',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Return config module enabled
     *
     * @return bool
     */
    public function isModuleEnable()
    {
        return $this->scopeConfig->getValue(
            'blackbird_vat_checker_proxy_client/general/enable',
            ScopeInterface::SCOPE_STORE
        ) ? true : false;
    }

    /**
     * @return bool
     */
    public function useOnlyOnFailure()
    {
        return $this->scopeConfig->getValue(
            'blackbird_vat_checker_proxy_client/general/use_only_on_failure',
            ScopeInterface::SCOPE_STORE
        ) ? true : false;
    }

}
