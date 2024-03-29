<?php

namespace SzamlaAgent\Waybill;

use SzamlaAgent\Request\Request;
use SzamlaAgent\SzamlaAgentException;
use SzamlaAgent\Util;

/**
 * Pick Pack Pont fuvarlevél
 *
 * @package SzamlaAgent\Waybill
 */
class PPPWaybill extends Waybill {

    /**
     * Vonalkód előtag
     * PPP-vel egyeztetett 3 karakteres rövidítés
     *
     * @var string
     */
    protected $barcodePrefix;

    /**
     * Számlánként egyedi vonalkód, maximum 7 karakteres azonosító
     *
     * @var string
     */
    protected $barcodePostfix;


    /**
     * PPP (Pick Pack Pont) fuvarlevél létrehozása
     *
     * @param string  $destination  Úti cél
     * @param string  $barcode      Vonalkód
     * @param string  $comment      fuvarlevél megjegyzés
     */
    function __construct($destination = '', $barcode = '', $comment = '') {
        parent::__construct($destination, self::WAYBILL_TYPE_PPP, $barcode, $comment);
    }

    /**
     * Ellenőrizzük a mező típusát
     *
     * @param $field
     * @param $value
     *
     * @return string
     * @throws SzamlaAgentException
     */
    protected function checkField($field, $value) {
        if (property_exists($this, $field)) {
            switch ($field) {
                case 'barcodePrefix':
                case 'barcodePostfix':
                    Util::checkStrField($field, $value, false, __CLASS__);
                    break;
            }
        }
        return $value;
    }

    /**
     *
     * @param Request $request
     *
     * @return array
     * @throws SzamlaAgentException
     */
    public function buildXmlData(Request $request) {
        $this->checkFields(get_class());
        $data = parent::buildXmlData($request);

        $data['ppp'] = [];
        if (Util::isNotBlank($this->getBarcodePrefix()))  $data['ppp']['vonalkodPrefix']  = $this->getBarcodePrefix();
        if (Util::isNotBlank($this->getBarcodePostfix())) $data['ppp']['vonalkodPostfix'] = $this->getBarcodePostfix();

        return $data;
    }

    /**
     * @return string
     */
    public function getBarcodePrefix() {
        return $this->barcodePrefix;
    }

    /**
     * @param string $barcodePrefix
     */
    public function setBarcodePrefix($barcodePrefix) {
        $this->barcodePrefix = $barcodePrefix;
    }

    /**
     * @return string
     */
    public function getBarcodePostfix() {
        return $this->barcodePostfix;
    }

    /**
     * @param string $barcodePostfix
     */
    public function setBarcodePostfix($barcodePostfix) {
        $this->barcodePostfix = $barcodePostfix;
    }
}