<?php


namespace Tools;

class SocketServer
{
    private $ip;
    private $port;

    private $currentSockets = array();
    private $msocket;

    public function __construct($ip, $port)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->init();
    }

    protected function init()
    {
        $socket_m = \socket_create(AF_INET,SOCK_STREAM,0);
        \socket_set_option($socket_m, SOL_SOCKET,SO_REUSEADDR, 1);
        $this->currentSockets[] = $socket_m;
        $this->msocket = $socket_m;

        \socket_bind($this->msocket,$this->ip,$this->port);
        \socket_listen($this->msocket);
    }

    public function run(\Closure $response)
    {
        $write  = NULL;
        $except = NULL;
        while (true) {

            $changed_sockets = $this->currentSockets;
            $num_changed_sockets = socket_select($changed_sockets, $write , $except , 0);

            if ($num_changed_sockets === false) {
                $errorcode = \socket_last_error();
                $errormsg = \socket_strerror($errorcode);
                echo "code: [$errorcode] " . $errormsg . "\n";
            } else if ($num_changed_sockets > 0) {

                var_dump(count($changed_sockets));
                foreach($changed_sockets as $socket)
                {
                    if ($socket == $this->msocket) { //is master socket changed?
                        if ( !($client = socket_accept($this->msocket)) ) {
                            echo "[SocketServer] socket_accept() failed: reason: " . socket_strerror($socket);
                            continue;
                        } else {
                            //
                            $this->currentSockets[] = $client;
                            $input = socket_read($client, 4096);

                            /*

                            adding code here , to response to input

                            response is a funciton which react to user input.
                            $output = response($user_input); //response to input

                            */
                            $output = $response($input);
                            socket_write($client,$output,4096);
                        }
                    } else {
                        /* 
                        socket_recv returns FALSE if there is no data and 0 if the socket is widowed 
                        (disconnected by remote side)
                        $bytes = socket_recv($socket, $buffer, 4096, 0);
                        ***/
                        $bytes = socket_recv($socket, $buffer, 4096, MSG_WAITALL);

                        if ($bytes == 0) {
                            $index = array_search($socket, $this->currentSockets);
                            // Remove Socket from List
                            unset($this->currentSockets[$index]);
                            socket_close($socket);
                        }else{
                            $output = $response($buffer);
                            socket_write($socket,$output,4096);
                        }

                    }
                }//foreach
            }

        }//while
    }

    public function __destruct()
    {
        \socket_close($this->msocket);
    }

}

/**
$response = function($input)
{
return "input is: " . $input . "\n";
};

$server = new SocketServer("127.0.0.1", 5789);
$server->run($response);
 ****/

/*
<?php
$er = error_reporting(0);
$bytes    = socket_recv($socket,$buffer,1,MSG_WAITALL);
error_reporting($er);

// MEGA BUG HERE
// this statuses are wrong and swapped, closed socket must be with "FALSE"
// but in fact he swap the values:
// http://php.net/manual/en/function.socket-recv.php
//
if($bytes===false){ // no data available, socket not closed
    echo 'WS_READ_ERR1: '.socket_strerror(socket_last_error($socket)).PHP_EOL;
    // print when no data available:
    // WS_READ_ERR1: Resource temporarily unavailable
    continue;
}else if($bytes===0){ // socket closed
    echo 'WS_READ_ERR2: '.socket_strerror(socket_last_error($socket)).PHP_EOL;
    // print when socket closed:
    // WS_READ_ERR2: Success
    $process->close();
}


****/
