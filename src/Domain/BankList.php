<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Domain;

use Psa\EpsBankTransfer\Internal\Generated\BankList\EpsSOBankListProtocol;

/**
 * Collection of Bank value objects.
 *
 * Provides mapping helpers from generated XSD types into domain entities.
 */
class BankList
{
    /** @var Bank[] */
    private $banks;

    /**
     * Create a bank list.
     *
     * @param Bank[] $banks Array of banks.
     */
    public function __construct(array $banks)
    {
        $this->banks = $banks;
    }

    /**
     * Map generated v2.6 bank list protocol to domain model.
     *
     * @param EpsSOBankListProtocol $protocol Parsed XSD object.
     * @return self
     */
    public static function fromV26(EpsSOBankListProtocol $protocol): self
    {
        $banks = [];
        foreach ($protocol->getBank() as $bank) {
            $banks[] = new Bank(
                $bank->getBic(),
                $bank->getBezeichnung(),
                $bank->getEpsUrl(),
                $bank->getLand()
            );
        }
        return new self($banks);
    }

    /**
     * Get all banks.
     *
     * @return Bank[]
     */
    public function getBanks(): array
    {
        return $this->banks;
    }
}
