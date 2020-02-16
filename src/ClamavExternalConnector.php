<?php

namespace Drupal\clamav_external;

use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\Client;

/**
 * Clamav External Connector.
 */
class ClamavExternalConnector {

  /**
   * Config factory service.
   *
   * @var Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * HTTP Client Service.
   *
   * @var GuzzleHttp\Client
   */
  protected $client;

  /**
   * The external endpoint for the scanner.
   *
   * @var string
   */
  protected $externalEndpoint;

  /**
   * Basic Auth for POST request to scanner.
   *
   * @var array
   */
  protected $auth;

  /**
   * Constructor for Clamav External Connector.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \GuzzleHttp\Client $http_client
   *   The http client service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, Client $http_client) {
    $this->configFactory = $config_factory->get('clamav_external.settings');
    $this->client = $http_client;
    $this->externalEndpoint = $this->configFactory->get('external_scanner_endpoint');
    $username = $this->configFactory->get('external_scanner_username');
    $password = $this->configFactory->get('external_scanner_pw');
    $this->auth = [$username, $password];
  }

  /**
   * Scan the file on the external host.
   *
   * @param string $fileUri
   *   The uri to the file being uploaded.
   *
   * @return string
   *   The response of the scan.
   */
  public function scanFileOnExternalHost($fileUri) {
    $file_stream = fopen($fileUri, 'r');
    $response = $this->client->request('POST', $this->externalEndpoint, [
      'timeout' => 180,
      'auth' => $this->auth,
      'multipart' => [
          [
            'name' => 'file_to_scan',
            'contents' => $file_stream,
          ],
      ],
    ]
    );
    return $response;
  }

}
