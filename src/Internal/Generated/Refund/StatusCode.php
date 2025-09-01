<?php

namespace Psa\EpsBankTransfer\Internal\Generated\Refund;

/**
 * Class representing StatusCode
 *
 * 000 - Keine Fehler Datentraeger übernommen
 *  004 - Authorisierungsdaten fehlerhaft
 *  007 - Fehler im XML Stream
 *  009 - Interner Fehler
 *  010 - IBAN ungültig
 *  020 - TransaktionsId nicht vorhanden
 *  022 - Refundierungsbetrag zu hoch
 */
class StatusCode
{

    /**
     * @var string $__value
     */
    private $__value = null;

    /**
     * Construct
     *
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value($value);
    }

    /**
     * Gets or sets the inner value
     *
     * @param string $value
     * @return string
     */
    public function value()
    {
        if ($args = func_get_args()) {
            $this->__value = $args[0];
        }
        return $this->__value;
    }

    /**
     * Gets a string value
     *
     * @return string
     */
    public function __toString()
    {
        return strval($this->__value);
    }


}

