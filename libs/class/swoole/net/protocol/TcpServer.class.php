<?php
/**
 * Tcp 接口
 *
 * @author 3ogx.com@gmail.com
 * @date 2013-04-27
 * @package protocol
 **/
class TcpServer implements Swoole_TCP_Server_Protocol {
    /**
     * 默認端口號
     **/
    public $default_port = 9001;

    /**
     * 記錄客戶端的socket id
     **/
    public $tcp_client;

    /**
     * php私有對象
     **/
    private $model;

    public function __construct($php, $model) {
        $this->model = $model;
    }

    /**
     * 數據接收
     *
     * @param $client 客戶端的socket id
     * @param $data
     **/
    public function onRecive($clent_id, $data) {
        /* 接收數據 */
        $_data['tel'] = '18436788987';
        $_data['mac'] = '60:33:4b:2a:73:ca';
        $_data['data'] = $data;
        $_data['created'] = date("Y-m-d H:i:s");
        /* 數據入庫 */
        $this->model->put($_data);

        $this->server->send($clent_id, $data);
    }

    /**
     * 數據接收
     *
     * @param $client 客戶端的socket id
     * @param $data
     **/
    public function onStart(){}

    /**
     * 關閉
     **/
    public function onShutdown(){}

    /**
     * 關閉當前客戶端連接
     *
     * @param $client 客戶端的socket id
     **/
    public function onclose($client_id){
        $uid = array_search($client_id, $this->tcp_client);
        unset($this->tcp_client[$uid]);
    }

    /**
     * 連接
     *
     * @param $client 客戶端的socket id
     * @param $data
     **/
    public function onConnect($client_id){}
}