<?php

namespace at\externet\eps_bank_transfer;

class TransferMsgDetails
{

    /** @var string Jene URL, an die der eps SO den vitality-check und die eps Zahlungs-bestätigungsnachricht (= confirma-tion) sendet. */
    public $ConfirmationUrl;

    /** @var string Jene URL, um für den Käufer einen durchgängigen Ablauf zu garantieren und einen Rücksprungpunkt in den Webshop des Händlers anzubieten. */
    public $TransactionOkUrl;

    /** @var string Wurde die Transaktion nicht erfolg-reich durchgeführt, so erhält der Käufer, nach Rückmeldung des Sys-tems, einen Redirect auf diese URL */
    public $TransactionNokUrl;

    /** @var string Jenes Fenster, in welches der Redirect auf die Ok URL erfolgen soll. */
    public $TargetWindowOk;

    /** @var string Jenes Fenster, in welches der Redirect auf die Nok URL erfolgen soll. */
    public $TargetWindowNok;

    /**
     *
     * @param type $ConfirmationUrl
     * @param type $TransactionOkUrl
     * @param type $TransactionNokUrl
     */
    public function __construct($ConfirmationUrl, $TransactionOkUrl, $TransactionNokUrl)
    {
        $this->ConfirmationUrl = $ConfirmationUrl;
        $this->TransactionOkUrl = $TransactionOkUrl;
        $this->TransactionNokUrl = $TransactionNokUrl;
    }

}
?>
