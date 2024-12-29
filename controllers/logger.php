<?php

class Logger {
    protected static $logFile = __DIR__ . '/../logs/actions.log'; //Ruta del archivo de logs

    //Función para escribir en el archivo de logs
    public static function log($message) { 
        $logDir = dirname(self::$logFile); //Obtiene el directorio del archivo de logs
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true); //Crea el directorio si no existe
        }

        $timestamp = date('Y-m-d H:i:s'); //Obtiene la fecha y hora actual
        $logMessage = "[$timestamp] $message" . PHP_EOL; //Mensaje a escribir en el archivo de logs
        file_put_contents(self::$logFile, $logMessage, FILE_APPEND); //Escribe el mensaje en el archivo de logs
    }

    //Función para obtener los logs
    public static function getLogs() {
        if (file_exists(self::$logFile)) {
            return file(self::$logFile, FILE_IGNORE_NEW_LINES);
        }
        return []; //Devuelve un array vacío si no existe el archivo de logs
    }

    //Función para limpiar los logs
    public static function clearLogs() {
        if (file_exists(self::$logFile)) { //Verifica si existe el archivo de logs
            file_put_contents(self::$logFile, ''); //Limpia el archivo de logs
        }
    }
}
?>
