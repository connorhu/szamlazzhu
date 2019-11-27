<?php

namespace SzamlaAgent;

/**
 * A Számla Agent naplózását végző osztály
 *
 * @package SzamlaAgent
 */
class Log {

    /**
     * Alapértelmezett naplófájl elnevezés
     */
    const LOG_FILENAME = 'szamlaagent';

    /**
     * Naplózási szint: nincs naplózás
     */
    const LOG_LEVEL_OFF   = 0;

    /**
     * Naplózási szint: hibák
     */
    const LOG_LEVEL_ERROR = 1;

    /**
     * Naplózási szint: figyelmeztetések
     */
    const LOG_LEVEL_WARN  = 2;

    /**
     * Naplózási szint: fejlesztői (debug)
     */
    const LOG_LEVEL_DEBUG = 3;

    /**
     * Naplók útvonala
     */
    const LOG_PATH = './logs';

    /**
     * Elérhető naplózási szintek
     */
    private static $logLevels = array(
        self::LOG_LEVEL_OFF,
        self::LOG_LEVEL_ERROR,
        self::LOG_LEVEL_WARN,
        self::LOG_LEVEL_DEBUG
    );

    /**
     * Üzenetek naplózása logfájlba
     * Igény szerint e-mail küldése a megadott címre.
     *
     * @param string $pMessage
     * @param int    $pType
     * @param string $pEmail
     *
     * @throws SzamlaAgentException
     */
    public static function writeLog($pMessage, $pType = self::LOG_LEVEL_DEBUG, $pEmail = '') {

        $filename   = SzamlaAgentUtil::getAbsPath(self::LOG_PATH, self::getLogFileName());
        $remoteAddr = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '';
        $message    = '['.date('Y-m-d H:i:s').'] ['.$remoteAddr.'] ['.self::getLogTypeStr($pType).'] '.$pMessage.PHP_EOL;

        error_log($message, 3, $filename);

        if (!empty($pEmail) && $pType == self::LOG_LEVEL_ERROR) {
            $headers = "Content-Type: text/html; charset=UTF-8";
            error_log($message, 1, $pEmail, $headers);
        } elseif ($pType == self::LOG_LEVEL_ERROR) {
            throw new SzamlaAgentException($pMessage);
        }
    }

    /**
     * Visszaadja a naplózás típusának elnevezését
     *
     * @param $type
     *
     * @return string
     * @throws SzamlaAgentException
     */
    private static function getLogTypeStr($type) {
        switch ($type) {
            case self::LOG_LEVEL_ERROR: $name = 'error'; break;
            case self::LOG_LEVEL_WARN:  $name = 'warn';  break;
            case self::LOG_LEVEL_DEBUG: $name = 'debug'; break;
            default:
                throw new SzamlaAgentException("Nem létezik ilyen naplózási típus: {$type}.");
        }
        return $name;
    }

    /**
     * Visszaadja a naplózási fájl nevét
     *
     * @return string
     */
    private static function getLogFileName() {
        return self::LOG_FILENAME . '_' . date('Y-m-d') . '.log';
    }

    /**
     * @param $logLevel
     *
     * @return bool
     */
    public static function isValidLogLevel($logLevel) {
        return (in_array($logLevel, self::$logLevels));
    }

    /**
     * @param $logLevel
     *
     * @return bool
     */
    public static function isNotValidLogLevel($logLevel) {
        return !self::isValidLogLevel($logLevel);
    }
 }