# Vat Checker Proxy Client

[![Latest Stable Version](https://img.shields.io/packagist/v/blackbird/vat-checker-proxy-client.svg?style=flat-square)](https://packagist.org/packages/blackbird/vat-checker-proxy-client)
[![License: MIT](https://img.shields.io/github/license/blackbird-agency/vat-checker-proxy-server.svg?style=flat-square)](./LICENSE)

This solution is a proxy client to validate VAT Numbers with [Europa VIES](https://ec.europa.eu/taxation_customs/vies/?locale=fr) and offer a solution to Magento instance that are issuing temporary "IP BLOCKED" or "IP BANNED".
The proxy server should be installed in separate server with a distinct IP address from the server where the magento client is installed.

It aims to be used with [Vat Checker Proxy Server](https://github.com/blackbird-agency/vat-checker-proxy-server) for Magento 2.

## Setup

### Get the package

**Composer Package:**

```
composer require blackbird/vat-checker-proxy-client
```

**Zip Package:**

Unzip the package in app/code/Blackbird/VatCheckerProxyClient, from the root of your Magento instance.

### Install the module

Go to your Magento root directory and run the following magento command:
```
php bin/magento setup:upgrade
```

### Configure the module

The configuration path is Store/Configuration/Blackbird Extensions/Vat Checker Proxy Client.

- Enter the url of the proxy server and the authentification token.
- Define if you want to use it always or only on fail of Magento 2 
  default check.

> You can check if your configuration is correct by going to Store/General/General/Store Information and enter your Country, and your VAT Number, 
> then click "Validate VAT Number".

## Support

- If you have any issue with this code, feel free to [open an issue](https://github.com/blackbird-agency/vat-checker-proxy-client/issues/new).
- If you want to contribute to this project, feel free to [create a pull request](https://github.com/blackbird-agency/vat-checker-proxy-client/compare).

## Contact

For further information, contact us:

- by email: hello@bird.eu
- or by form: [https://black.bird.eu/en/contacts/](https://black.bird.eu/contacts/)

## Authors

- **Bruno FACHE** - *Maintainer* - [It's me!](https://github.com/bruno-blackbird)
- **Blackbird Team** - *Contributor* - [They're awesome!](https://github.com/blackbird-agency)

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

***That's all folks!***
