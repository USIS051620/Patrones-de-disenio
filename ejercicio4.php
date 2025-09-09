<?php
// Función auxiliar para imprimir correctamente según el entorno
function saltoLinea(): string {
    return php_sapi_name() === "cli" ? PHP_EOL : "<br><br>";
}

// Interfaz (Strategy)
interface MessageStrategy {
    public function mostrar(string $mensaje): void;
}

// Estrategias concretas
class ConsolaStrategy implements MessageStrategy {
    public function mostrar(string $mensaje): void {
        echo "Salida por Consola: " . $mensaje . saltoLinea();
    }
}

class JsonStrategy implements MessageStrategy {
    public function mostrar(string $mensaje): void {
        echo json_encode(["mensaje" => $mensaje], JSON_PRETTY_PRINT) . saltoLinea();
    }
}

class TxtStrategy implements MessageStrategy {
    public function mostrar(string $mensaje): void {
        $archivo = "salida.txt";
        file_put_contents($archivo, $mensaje . PHP_EOL, FILE_APPEND);
        echo "Mensaje guardado en {$archivo}" . saltoLinea();
    }
}

// Contexto
class MessageContext {
    private MessageStrategy $strategy;

    public function setStrategy(MessageStrategy $strategy): void {
        $this->strategy = $strategy;
    }

    public function mostrarMensaje(string $mensaje): void {
        if (isset($this->strategy)) {
            $this->strategy->mostrar($mensaje);
        } else {
            echo "No se ha definido estrategia de salida." . saltoLinea();
        }
    }
}

// Uso del patrón
$context = new MessageContext();

// Estrategia Consola
$context->setStrategy(new ConsolaStrategy());
$context->mostrarMensaje("Aplicando el patrón Strategy en PHP");

// Estrategia JSON
$context->setStrategy(new JsonStrategy());
$context->mostrarMensaje("Probando diferentes salidas con Strategy");

// Estrategia TXT
$context->setStrategy(new TxtStrategy());
$context->mostrarMensaje("Mensaje guardado usando Strategy");
