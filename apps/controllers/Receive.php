<?php
/**
 * 接收數據API接口
 *
 * 使用socket 用TCP協議和控制器通信, 用swoole的擴展做PHP服務器的守護進程
 * 單片機通過網絡通信模塊向服務器發送數據, 服務器接收到數據後呼叫PHP API接口將數據入庫
 * 或服務器接收數據後直接調用數據處理工具類將數據入庫(不需要API接口)
 *
 * @package Controller
 * @author 3ogx.com@gmail.com
 * @date 2013-04-22
 * @todo
 * 必須要用LINUX內核的操作系統
 **/
class Receive extends Controller {
    public function reqData() {
        $_map['tel'] = '13510971473';
        /* $_data = $this->swoole->model->Monitor->gets($_map); */
        /* var_dump($_data); */

        $_ret = $this->swoole->model->Monitor->all();
        var_dump($_ret);
    }

    public function insertData() {
        $_model = $this->swoole->model->Monitor;

        $_data['tel'] = '13510971473';
        $_data['mac'] = '78:ca:39:ff:fe:0c:b0:8c';
        $_data['data'] = 'sfsdfds';
        $_data['created'] = date("Y-m-d H:i");
        $_model->put($_data);
    }

    public function socketServer() {
        import("@phpbuffer.BigEndianBytesBuffer");
        $buffer = new BigEndianBytesBuffer();
        $buffer->clear();
        $buffer->writeInt(5);
        $buffer->writeInt(6);
        debug($buffer);
    }
}