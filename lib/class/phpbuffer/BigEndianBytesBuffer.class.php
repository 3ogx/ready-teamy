<?php
/**
 * PHP bytes array operation  API
 *
 * Copyright (c) 2010 sunli <sunli1223ATgmail.com>
 *
 * @version    $Id: BigEndianBytesBuffer.php 5 2010-12-14 06:20:27Z sunli1223 $
 * @author     sunli <sunli1223ATgmail.com>
 * @link       http://sunli.cnblogs.com
 */

require_once  dirname ( __FILE__ ) . '/BigEndianBuffer.class.php';
class BigEndianBytesBuffer extends BigEndianBuffer {
    private $bytes;
    private $readerIndex = 0;
    private $writeIndex = 0;
    public function __construct($bytes='') {
        $this->bytes = $bytes;
        $this->writeIndex += strlen ( $bytes );
    }
    public function readBytes($len) {
        if ($len < 1) {
            return false;
        }
        $str = substr ( $this->bytes, $this->readerIndex, $len );
        $this->readerIndex += $len;
        return $str;
    }
    public function readAllBytes() {
        return $this->bytes;
    }
    public function writeBytes($bytes) {
        $this->bytes .= $bytes;
        $this->writeIndex += strlen ( $bytes );
    }
    public function clear() {
        $this->bytes = null;
        $this->readerIndex = 0;
        $this->writeIndex = 0;
    }

}
?>