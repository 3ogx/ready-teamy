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
    /**
     * 顯示存儲的數據
     *
     * @param null
     * @author 3ogx.com@gmail.com
     * @return void
     **/
    public function showAllData() {
        $_data = $this->swoole->model->Monitor->all();
        $this->swoole->tpl->display('page_index.html');
    }

    /**
     * 創建數據表
     *
     * @param null
     * @author 3ogx.com@gmail.com
     * @return void
     **/
    public function showTable() {
        $this->swoole->model->Monitor->createTableSQL();
    }

    /**
     * 開始接收控制器數據
     **/
    public function requestTerminal() {
        import("@phpbuffer.BigEndianBytesBuffer");
        $buffer = new BigEndianBytesBuffer(file_get_contents('db'));
        debug($buffer);
        $data = $buffer->readBytes(1);
        debug($data);
        /* $data = $buffer->readShort(); */
        /* debug($data); */
    }

    /**
     * 接收完數據後答復控制器
     *
     * @return Bytes
     **/
    public function responseTerminal() {
        import("@phpbuffer.BigEndianBytesBuffer");
        $buffer = new BigEndianBytesBuffer();
        $buffer->clear();
        $buffer->writeInt(3);
        debug($buffer);
    }

    /**
     * 服務器端建立socket監聽, 默認為9001端口
     *
     * @return void
     **/
    public function server(){
        import('#net.driver.SelectTCP');
        import('#net.protocol.TcpServer');
        $protocol = new TcpServer();
        $server = new SelectTCP('localhost', $protocol->default_port);
        $server->setProtocol($protocol);
        $server->run();
        /* $server->shutdown(); */
        debug($server);
    }

    public function client(){
        $fp = stream_socket_client("tcp://127.0.0.1:9001", $errno, $errstr);
        $data = $argv[1];
        if(!$fp) {
            echo "ERROR: $errno - $errstr<br />\n";
        } else {
            for ($i = 0; $i < 10; $i++) {
                fwrite($fp, $i);
                usleep(10);
                echo 'W';
            }
            fwrite($fp, "\r\n");
            /* $ret = fwrite($fp, "$data\n"); */
            echo fread($fp, 1024);

            fclose($fp);
        }
    }

    public function dependServer(){
        global $errno, $errstr;
        $socket = stream_socket_server("tcp://127.0.0.1:9002", $errno, $errstr);
        if (!$socket) {
            die("$errstr");
        }

        while ($conn = stream_socket_accept($socket, -1)) {
            $id = 0;
            $ct = 0;
            $ct_data = '';
            $buffer = '';
            $id++;
            echo "Client id is:". $id."\n";
            while (!preg_match('/\r?\n/', $buffer)) {
                $buffer = fread($conn, 1024);
                echo 'R';
                $ct += strlen($buffer);
                $ct_data .= preg_match('/\r?\n/', '', $buffer);
            }
            $ct_size = ($ct - $ct_data) * 8;
            echo "[$id] " . __METHOD__ . " > " . $ct_data . "/n";
            fwrite($conn, "Received $ct_size byte data. \n");
            fclose($conn);
        }

        flcose($socket);
    }

    public function dependClient() {
        $socket_client = stream_socket_client("tcp://127.0.0.1:9002", $errno, $errstr);

        if (!socket_client) {
            die("$errstr $errno");
        } else {
            $msg = trim($argv[1]);
            for ($i = 0; $i < 10; $i++) {
                $res = fwrite($socket_client, "$msg($i)");
                usleep(1000);
                echo 'WR';
            }
            fwrite($socket_client, "\r\n");
            $this->log(fread($socket_client, 1024));
            fclose($socket_client);
        }
    }

    public function log($msg) {
        error_log($msg, 3, "/tmp/socket.log");
    }

    public function insertdemodata() {
        $_data['tel'] = '18436788987';
        $_data['mac'] = '60:33:4b:2a:73:ca';
        $_data['data'] = $data;
        $_data['created'] = date("Y-m-d H:i:s");
        $php->db->insert($data, 'pwn_monitor');
        exit;
        $_model = $this->swoole->model->Monitor;

        $_data['tel'] = '13510971473';
        $_data['mac'] = '78:ca:39:ff:fe:0c:b0:8c';
        $_data['data'] = 'sfsdfds';
        $_data['created'] = date("Y-m-d H:i");
        $_model->put($_data);
    }

    public function reqDemoData() {
        $_map['tel'] = '13510971473';
        /* $_data = $this->swoole->model->Monitor->gets($_map); */
        /* var_dump($_data); */

        $_ret = $this->swoole->model->Monitor->all();
        var_dump($_ret);
    }
}