<!-- TABLE OF CONTENTS -->
<details open="open">
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#acerca-del-proyecto">Descripcion</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#uso">Uso</a></li>
    <li><a href="#contacto">Contacto</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## Acerca del proyecto

Paquete composer Auth-saml2 para starter de Visyon360.

<!-- GETTING STARTED -->
## Getting Started

### Prerequisitos

Composer 2.0 será necesario para la correcta instalación de este paquete

### Installation & SAML Configuration

[aacotroneo/laravel-saml2](https://github.com/aacotroneo/laravel-saml2)

1. The configuration of the SAML implementation it is done with the next library (for more information you could look for it at documentation library site): [aacotroneo/laravel-saml2](https://github.com/aacotroneo/laravel-saml2) To implement this library we will install package via composer:
   
   ```sh
   composer require visyon360/authsaml2-module
   ```
   
2. Then you must publish the config files with:

    ```bash
    php artisan vendor:publish --provider="Aacotroneo\Saml2\Saml2ServiceProvider"
    ```

3. You will need to configure your Service Provider and your Identity Provider(s).
We will act as Service Provider role, and the main functionality of that is almost done by default but, if you want, you could modify that information with your needs (if we change the ACS and the SLS URLs we will have to notify the IP with the changes).
In order to configure the properties of the SP and IP you must change on config/saml2_settings.
Add the name of the Controller in thge Module:

    ```php
    'saml2_controller' => 'Modules\AuthSaml2\Http\Controllers\AuthController',
    ```

4. And then add the others idp that the proyect requires, for example:

    ```php
    'idpNames' => ['develop','staging','metaverso'],
    ```

5. Then we need to create in the config/saml2 folder, all of idp's configuration files like for example develop_idp_settings.php, in this file we must change all the setting properties that you have to adapt. For example at the 'sp' array you will have the Service Provider properties:

    ```php
     'sp' => array(
            'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:transient',
            'x509cert' => env('SAML2_'.$this_idp_env_id.'_SP_x509',''),
            'privateKey' => env('SAML2_'.$this_idp_env_id.'_SP_PRIVATEKEY',''),
            'entityId' => env('SAML2_'.$this_idp_env_id.'_SP_ENTITYID',''),
            'assertionConsumerService' => array(
                'url' => '',
            ),
            'singleLogoutService' => array(
                'url' => '',
            ),
    ```

6. The 'NameIDFormat' is the format of the identifier of requested subject. You must have the same as the Identity Provider.
Certs are not need by default at Service Provider.
The 'entityID' is the URI which identifies the identity of Service Provider (blank by default).
'ACS' and 'SLS' are the URL from callbacks response from the IP for login and logout, respectively (blank by default).
At 'idp' array you will have the Identity Provider properties:

    ```php
        'idp' => array(
            'entityId' => env('SAML2_'.$this_idp_env_id.'_IDP_ENTITYID', $idp_host . '/saml2/idp/metadata.php'),
            'singleSignOnService' => array(
                'url' => env('SAML2_'.$this_idp_env_id.'_IDP_SSO_URL', $idp_host . '/saml2/idp/SSOService.php'),
            ),
            'singleLogoutService' => array(
                'url' => env('SAML2_'.$this_idp_env_id.'_IDP_SL_URL', $idp_host . '/saml2/idp/SingleLogoutService.php'),
            ),
            'x509cert' => env('SAML2_'.$this_idp_env_id.'_IDP_x509', 'MIID/TCCAuWgAwIBAgIJAI4R3WyjjmB1MA0GCSqGSIb3DQEBCwUAMIGUMQswCQYDVQQGEwJBUjEVMBMGA1UECAwMQnVlbm9zIEFpcmVzMRUwEwYDVQQHDAxCdWVub3MgQWlyZXMxDDAKBgNVBAoMA1NJVTERMA8GA1UECwwIU2lzdGVtYXMxFDASBgNVBAMMC09yZy5TaXUuQ29tMSAwHgYJKoZIhvcNAQkBFhFhZG1pbmlAc2l1LmVkdS5hcjAeFw0xNDEyMDExNDM2MjVaFw0yNDExMzAxNDM2MjVaMIGUMQswCQYDVQQGEwJBUjEVMBMGA1UECAwMQnVlbm9zIEFpcmVzMRUwEwYDVQQHDAxCdWVub3MgQWlyZXMxDDAKBgNVBAoMA1NJVTERMA8GA1UECwwIU2lzdGVtYXMxFDASBgNVBAMMC09yZy5TaXUuQ29tMSAwHgYJKoZIhvcNAQkBFhFhZG1pbmlAc2l1LmVkdS5hcjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAMbzW/EpEv+qqZzfT1Buwjg9nnNNVrxkCfuR9fQiQw2tSouS5X37W5h7RmchRt54wsm046PDKtbSz1NpZT2GkmHN37yALW2lY7MyVUC7itv9vDAUsFr0EfKIdCKgxCKjrzkZ5ImbNvjxf7eA77PPGJnQ/UwXY7W+cvLkirp0K5uWpDk+nac5W0JXOCFR1BpPUJRbz2jFIEHyChRt7nsJZH6ejzNqK9lABEC76htNy1Ll/D3tUoPaqo8VlKW3N3MZE0DB9O7g65DmZIIlFqkaMH3ALd8adodJtOvqfDU/A6SxuwMfwDYPjoucykGDu1etRZ7dF2gd+W+1Pn7yizPT1q8CAwEAAaNQME4wHQYDVR0OBBYEFPsn8tUHN8XXf23ig5Qro3beP8BuMB8GA1UdIwQYMBaAFPsn8tUHN8XXf23ig5Qro3beP8BuMAwGA1UdEwQFMAMBAf8wDQYJKoZIhvcNAQELBQADggEBAGu60odWFiK+DkQekozGnlpNBQz5lQ/bwmOWdktnQj6HYXu43e7sh9oZWArLYHEOyMUekKQAxOK51vbTHzzw66BZU91/nqvaOBfkJyZKGfluHbD0/hfOl/D5kONqI9kyTu4wkLQcYGyuIi75CJs15uA03FSuULQdY/Liv+czS/XYDyvtSLnu43VuAQWN321PQNhuGueIaLJANb2C5qq5ilTBUw6PxY9Z+vtMjAjTJGKEkE/tQs7CvzLPKXX3KTD9lIILmX5yUC3dLgjVKi1KGDqNApYGOMtjr5eoxPQrqDBmyx3flcy0dQTdLXud3UjWVW3N0PYgJtw5yBsS74QTGD4='),
        ),
    ```

7. The 'entityID' is the URI which identifies the Identity Provider identity.
'SSOS' and 'SLS' are the URL endpoints from login and logout from the Identity Provider, respectively.
The cert, in this case, is needed to authenticate from the IP.
The environment variables that you need to add are these:

    ```bash
    FRONTEND_URL=
    SAML2_DEVELOP_IDP_HOST=
    SAML2_DEVELOP_IDP_x509=
    ```
    We must change the idp name when we are in a different environment, like if we are using this in staging, the name of the variable it must be SAML2_STAGING_IDP_HOST and SAML2_STAGING_IDP_x509.

<!-- USAGE EXAMPLES -->
## Usage

At config/saml2_settings.php you could change the routes, and the controller flow for the SAML2 implementation.
If you leave it blank the default URLs will be the used. In this case, we are using the UserController as SAML2Controller.

There are four methods and routes that we must keep in mind, the login (login and acs) and the logout (logout and sls) ones.
When you want to login, you are redirected to login method:

```php
public function login(Saml2Auth $saml2Auth)
{
    $saml2Auth->login(config('saml2_settings.loginRoute'));
}
```

When the login finish, the user will be redirected to acs method.
On this method, we have the status of the authentication, and the user data if it is logged correctly.
If we need change some Atributtes of the user object returned by the idp, those are in the method getUser() over the LoginUserService class in the same name file on Modules\AuthSaml2\Services route.
The logout methods works exactly as the login methods, you can control the user before doing the request and after receiving the response.

<!-- CONTACT -->
## Contacto

Juan Alberto Zamarbide - jzamarbide@kiteris.com
Project Link: [https://github.com/visyon360/authsaml2-module](https://github.com/visyon360/authsaml2-module)
