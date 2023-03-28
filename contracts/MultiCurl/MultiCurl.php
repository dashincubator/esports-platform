<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Extremely Simple Feed/Data Fetcher Using Curl
 *
 */

namespace Contracts\MultiCurl;

use Contracts\Http\ResponseCollection;

interface MultiCurl
{

    /**
     *  Delete Curl Handlers
     *
     *  @return void
     */
    public function clear() : void;


    /**
     *  @return ResponseCollection
     */
    public function execute() : ResponseCollection;


    /**
     *  Set A New Curl Resource To Multi Curl
     *
     *  @param string $key Array Key For Data Returned By $this->get()
     *  @param string $url Url Used For curl_init($url)
     *  @param array $options Curl Options
     *  @return void
     */
    public function set(string $key, string $url, array $options = []) : void;
}
