<?php
namespace CpmsForm;

/**
 * Class Util
 *
 * @package CpmsForm
 */
class Util
{
    /**
     * Method to append any additional data to the clientUrl
     *
     * @param $url
     * @param $requiredParams
     *
     * @return string
     */
    public static function appendQueryString($url, array $requiredParams = null)
    {
        if (!empty($url) and stripos($url, 'http') !== 0) {
            $url = 'http://' . $url;
        }

        if (empty($requiredParams)) {
            return $url;
        }

        if (strpos($url, '?') === false) {
            return $url . '?' . http_build_query($requiredParams);
        } else {
            return $url . '&' . http_build_query($requiredParams);
        }
    }
}
