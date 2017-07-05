<?php 
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  
* Licensed under the MIT License.  See License in the project root 
* for license information.
* 
* Graph File
* PHP version 7
*
* @category  Library
* @package   Microsoft.Graph
* @copyright 2016 Microsoft Corporation
* @license   https://opensource.org/licenses/MIT MIT License
* @version   GIT: 0.1.0
* @link      https://graph.microsoft.io/
*/

namespace Microsoft\Graph;

use Microsoft\Graph\Core\GraphConstants;
use Microsoft\Graph\Exception\GraphException;
use Microsoft\Graph\Http\GraphCollectionRequest;
use Microsoft\Graph\Http\GraphRequest;
use GuzzleHttp\Client;

/**
 * Class Graph
 *
 * @category Library
 * @package  Microsoft.Graph
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://graph.microsoft.io/
 */
class Graph
{
    /**
    * The access_token provided after authenticating
    * with Microsoft Graph (required)
    *
    * @var string
    */
    private $_accessToken;
    /**
    * The base url to call
    * Default is "https://graph.microsoft.com"
    *
    * @var string
    */
    private $_baseUrl;
    /**
    * The base url to call
    * Default is "https://graph.microsoft.com"
    *
    * @var string
    */
    private $_client;

    /**
    * Creates a new Graph object, which is used to call the Graph API
    */
    public function __construct()
    {
        $this->_baseUrl = GraphConstants::REST_ENDPOINT;
    }

    /**
    * Sets the Base URL to call (defaults to https://graph.microsoft.com)
    *
    * @param string $baseUrl The URL to call
    *
    * @return Graph object
    */
    public function setBaseUrl($baseUrl)
    {
        $this->_baseUrl = $baseUrl;
        return $this;
    }

    /**
    * Sets the access token. A valid access token is required
    * to run queries against Graph
    *
    * @param string $accessToken The user's access token, retrieved from 
    *                     MS auth
    *
    * @return Graph object
    */
    public function setAccessToken($accessToken)
    {
        $this->_accessToken = $accessToken;
        return $this;
    }

    /**
    * Sets the default guzzle client.
    *
    * @param GuzzleHttp\Client $client The guzzle client
    *
    * @return Graph object
    */
    public function setClient($client)
    {
        $this->_client = $client;
        return $this;
    }

    /**
    * Creates a new request object with the given Graph information
    *
    * @param string $requestType The HTTP method to use, e.g. "GET" or "POST"
    * @param string $endpoint    The Graph endpoint to call
    *
    * @return GraphRequest The request object, which can be used to 
    *                      make queries against Graph
    */
    public function createRequest($requestType, $endpoint)
    {
        return new GraphRequest(
            $requestType,
            $endpoint,
            $this->getGuzzleClient()
        );
    }

    /**
    * Creates a new collection request object with the given 
    * Graph information
    * 
    * @param string $requestType The HTTP method to use, e.g. "GET" or "POST"
    * @param string $endpoint    The Graph endpoint to call
    * 
    * @return GraphCollectionRequest The request object, which can be
    *                                used to make queries against Graph
    */
    public function createCollectionRequest($requestType, $endpoint)
    {
        return new GraphCollectionRequest(
            $requestType, 
            $endpoint, 
            $this->getGuzzleClient()
        );
    }

    /**
    * Get a list of headers for the request
    *
    * @return array The headers for the request
    */
    private function _getDefaultHeaders()
    {
        return [
            'Host' => $this->_baseUrl,
            'Content-Type' => 'application/json',
            'SdkVersion' => 'Graph-php-' . GraphConstants::SDK_VERSION,
            'Authorization' => 'Bearer ' . $this->_accessToken
        ];
    }

    protected function getGuzzleClient()
    {
        if (! $this->_client) {
            $this->_client = $this->createGuzzleClient();
        }

        return $this->_client;
    }

    /**
     * Create a new Guzzle client
     * To allow for user flexibility, the
     * client is not reused. This allows the user
     * to set and changeø headers on a per-request
     * basis
     *
     * @return \GuzzleHttp\Client The new client
     * @throws \Microsoft\Graph\Exception\GraphException
     */
    protected function createGuzzleClient()
    {
        if (!$this->_accessToken) {
            throw new GraphException(GraphConstants::NO_ACCESS_TOKEN);
        }

        return new Client(
            [
                'base_uri' => $this->_baseUrl,
                'headers' => $this->_getDefaultHeaders(),
            ]
        );
    }
}
