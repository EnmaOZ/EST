<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatServer implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Almacenar la nueva conexión
        $this->clients->attach($conn);
        echo "Nueva conexión ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Conexión %d enviando mensaje "%s" a %d otra(s) conexiones' . "\n",
            $from->resourceId, $msg, $numRecv);

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // Enviar el mensaje a todos los clientes excepto al remitente
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // Eliminar la conexión
        $this->clients->detach($conn);
        echo "Conexión {$conn->resourceId} cerrada\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}
?>

