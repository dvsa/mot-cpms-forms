<?php
namespace CpmsFormTest\Utility;

use CpmsForm\Util;
use PHPUnit\Framework\TestCase;

/**
 * Class UtilTest
 *
 * @package CpmsFormTest\Utility
 */
class UtilTest extends TestCase
{
    public function testAppendQueryParam()
    {
        $url  = 'http://google.com';
        $url2 = 'http://google.com?home=1';
        $url3 = 'google.com?home=1';

        $time = time();

        $output = Util::appendQueryString($url, array('time' => $time));
        $this->assertSame($url . '?time=' . $time, $output);

        $output = Util::appendQueryString($url2, array('time' => $time));
        $this->assertSame($url2 . '&time=' . $time, $output);

        $output = Util::appendQueryString($url3, array('time' => $time));
        $this->assertSame($url2 . '&time=' . $time, $output);
    }
}
