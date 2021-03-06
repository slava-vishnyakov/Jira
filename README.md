# Jira OAuth2 Provider for Laravel Socialite

[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/SocialiteProviders/Jira.svg?style=flat-square)](https://scrutinizer-ci.com/g/SocialiteProviders/Jira/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/socialiteproviders/jira.svg?style=flat-square)](https://packagist.org/packages/socialiteproviders/jira)
[![Total Downloads](https://img.shields.io/packagist/dt/socialiteproviders/jira.svg?style=flat-square)](https://packagist.org/packages/socialiteproviders/jira)
[![Latest Unstable Version](https://img.shields.io/packagist/vpre/socialiteproviders/jira.svg?style=flat-square)](https://packagist.org/packages/socialiteproviders/jira)
[![License](https://img.shields.io/packagist/l/socialiteproviders/jira.svg?style=flat-square)](https://packagist.org/packages/socialiteproviders/jira)

## Documentation

Full documentation for using this provider can be found at [Jira Documentation](http://socialiteproviders.github.io/providers/jira/)

## Additional information

You also need to generate key pair:

    mkdir storage/app/keys
    openssl genrsa -out storage/app/keys/jira.pem 1024
    openssl rsa -in storage/app/keys/jira.pem -pubout -out storage/app/keys/jira.pub
    cat storage/app/keys/jira.pub

Add this to `config/services.php`:

    'jira' => [
        'client_id' => env('JIRA_KEY'),
        'client_secret' => env('JIRA_SECRET'),
        'redirect' => env('JIRA_REDIRECT_URI'),
        'url' => env('JIRA_URL'),
    ],


Add this to `.env` file:

    JIRA_KEY=yourkeyfortheservice
    JIRA_SECRET=
    JIRA_REDIRECT_URI=https://yoursite.com/login
    JIRA_URL=http://example.jira.com

