<?php
/*
 * @copyright Open Software License (OSL 3.0)
 */

namespace Blackbird\VatCheckerProxyClient\Model;

use Magento\Framework\DataObject;
use Psr\Log\LoggerInterface as PsrLogger;
use Magento\Framework\HTTP\Client\Curl;

class Vat
{
    /**
     * @var PsrLogger
     */
    protected $logger;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Curl
     */
    protected $curl;

    public function __construct(
        Config $config,
        PsrLogger $logger,
        Curl $curl
    ) {
        $this->curl = $curl;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * If checking failed, call with proxy
     *
     * @param \Magento\Customer\Model\Vat $subject
     * @param callable $proceed
     * @param $countryCode
     * @param $vatNumber
     * @param string $requesterCountryCode
     * @param string $requesterVatNumber
     * @return mixed
     */
    public function aroundCheckVatNumber(\Magento\Customer\Model\Vat $subject, callable $proceed, $countryCode, $vatNumber, $requesterCountryCode = '', $requesterVatNumber = '')
    {
        if (!$this->config->isModuleEnable()) {
            return $proceed($countryCode, $vatNumber, $requesterCountryCode, $requesterVatNumber);
        }

        if ($this->config->useOnlyOnFailure()) {
            $previousRes = $proceed($countryCode, $vatNumber, $requesterCountryCode, $requesterVatNumber);
        } else {
            $previousRes = new DataObject([
                'is_valid' => false,
                'request_date' => '',
                'request_identifier' => '',
                'request_success' => false,
                'request_message' => __('Error during VAT Number verification.'),
            ]);
        }

        //if request is not a success :
        if (!$previousRes->getRequestSuccess() &&
            $subject->canCheckVatNumber($countryCode, $vatNumber, $requesterCountryCode, $requesterVatNumber)) {
            $countryCodeForVatNumber = $this->getCountryCodeForVatNumber($countryCode);
            $requesterCountryCodeForVatNumber = $this->getCountryCodeForVatNumber($requesterCountryCode);

            $requestParams = [];
            $requestParams['countryCode'] = $countryCodeForVatNumber;
            $vatNumberSanitized = $subject->isCountryInEU($countryCode)
                ? str_replace([' ', '-', $countryCodeForVatNumber], ['', '', ''], $vatNumber)
                : str_replace([' ', '-'], ['', ''], $vatNumber);
            $requestParams['vatNumber'] = $vatNumberSanitized;
            $requestParams['requesterCountryCode'] = $requesterCountryCodeForVatNumber;
            $reqVatNumSanitized = $subject->isCountryInEU($requesterCountryCode)
                ? str_replace([' ', '-', $requesterCountryCodeForVatNumber], ['', '', ''], $requesterVatNumber)
                : str_replace([' ', '-'], ['', ''], $requesterVatNumber);
            $requestParams['requesterVatNumber'] = $reqVatNumSanitized;
            $requestParams['token'] = $this->config->getToken();

            $url = $this->config->getProxyUrl();

            try {
                $this->curl->post($url, $requestParams);
                $result= $this->curl->getBody();

                if ($result === false) { /* Handle error */
                    $this->logger->warning('Vat Checker Proxy Error : no result from host');
                    return $previousRes;
                }
                $resultDecoded = json_decode($result, true);
            } catch (\Exception $exception) {
                $this->logger->warning('Vat Checker Proxy Error : ' . $exception->getMessage());
            }

            if (array_key_exists('error', $resultDecoded)) {
                $this->logger->warning('Vat Checker Proxy Error : ' . $result['error']);
                return $previousRes;
            }

            if (array_key_exists('is_valid', $resultDecoded)) {
                $previousRes->setIsValid((bool) $resultDecoded['is_valid']);
                $previousRes->setRequestDate((string) $resultDecoded['request_date']);
                $previousRes->setRequestIdentifier((string) $resultDecoded['request_identifier']);

                if ($previousRes->getIsValid()) {
                    $previousRes->setRequestMessage(__('VAT Number is valid.'));
                    $previousRes->setRequestSuccess(true);
                } else {
                    $previousRes->setRequestMessage(__('Please enter a valid VAT number.'));
                    $previousRes->setRequestSuccess(false);
                }
            }
        }
        return $previousRes;
    }

    /**
     * @param $countryCode
     * @return mixed|string
     */
    public function getCountryCodeForVatNumber($countryCode)
    {
        // Greece uses a different code for VAT numbers then its country code
        // See: http://ec.europa.eu/taxation_customs/vies/faq.html#item_11
        // And https://en.wikipedia.org/wiki/VAT_identification_number:
        // "The full identifier starts with an ISO 3166-1 alpha-2 (2 letters) country code
        // (except for Greece, which uses the ISO 639-1 language code EL for the Greek language,
        // instead of its ISO 3166-1 alpha-2 country code GR)"

        return $countryCode === 'GR' ? 'EL' : $countryCode;
    }
}
