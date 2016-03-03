<?php

namespace SocialiteProviders\Jira;

use League\OAuth1\Client\Credentials\TokenCredentials;
use League\OAuth1\Client\Server\Server as BaseServer;
use League\OAuth1\Client\Signature\SignatureInterface;
use League\OAuth1\Client\Credentials\ClientCredentialsInterface;

class Server extends BaseServer
{
    public $baseUrl = 'http://example.jira.com';

    /**
     * Create a new server instance.
     *
     * !! RsaSha1Signature
     *
     * @param ClientCredentialsInterface|array $clientCredentials
     * @param SignatureInterface               $signature
     */
    public function __construct($clientCredentials, SignatureInterface $signature = null)
    {
        // Pass through an array or client credentials, we don't care
        if (is_array($clientCredentials)) {
            if(isset($clientCredentials['url'])) {
                $this->baseUrl = $clientCredentials['url'];
            }
            $clientCredentials = $this->createClientCredentials($clientCredentials);
        } elseif (!$clientCredentials instanceof ClientCredentialsInterface) {
            throw new \InvalidArgumentException('Client credentials must be an array or valid object.');
        }

        $this->clientCredentials = $clientCredentials;

        // !! RsaSha1Signature for Jira
        $this->signature = $signature ?: new RsaSha1Signature($clientCredentials);
    }

    /**
     * Generate the OAuth protocol header for a temporary credentials
     * request, based on the URI.
     *
     * @param string $uri
     *
     * @return string
     */
    protected function temporaryCredentialsProtocolHeader($uri)
    {
        $parameters = $this->baseProtocolParameters();

        // without 'oauth_callback'
        $parameters['oauth_signature'] = $this->signature->sign($uri, $parameters, 'POST');

        return $this->normalizeProtocolParameters($parameters);
    }

    /**
     * {@inheritDoc}
     */
    public function urlTemporaryCredentials()
    {
        return $this->baseUrl.'/plugins/servlet/oauth/request-token?oauth_callback='.
            rawurlencode($this->clientCredentials->getCallbackUri());
    }

    /**
     * {@inheritDoc}
     */
    public function urlAuthorization()
    {
        return $this->baseUrl.'/plugins/servlet/oauth/authorize';
    }

    /**
     * {@inheritDoc}
     */
    public function urlTokenCredentials()
    {
        return $this->baseUrl.'/plugins/servlet/oauth/access-token';
    }

    /**
     * {@inheritDoc}
     */
    public function urlUserDetails()
    {
        return $this->baseUrl.'/rest/api/2/myself';
    }

    /**
     * {@inheritDoc}
     */
    public function userDetails($data, TokenCredentials $tokenCredentials)
    {
        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function userUid($data, TokenCredentials $tokenCredentials)
    {
        return $data['key'];
    }

    /**
     * {@inheritDoc}
     */
    public function userScreenName($data, TokenCredentials $tokenCredentials)
    {
        return $data['name'];
    }

    /**
     * {@inheritDoc}
     */
    public function userEmail($data, TokenCredentials $tokenCredentials)
    {
        return $data['email'];
    }
}
