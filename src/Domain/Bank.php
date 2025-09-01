<?php
declare(strict_types=1);

namespace Psa\EpsBankTransfer\Domain;

/**
 * Value object representing a bank entry from the EPS bank list.
 *
 * Contains BIC, display name, optional EPS URL and country code.
 */
class Bank
{
    /** @var string */
    private $bic;

    /** @var string */
    private $name;

    /** @var string|null */
    private $url;

    /** @var string */
    private $countryCode;

    /**
     * Create a bank value object.
     *
     * @param string $bic BIC identifier of the bank.
     * @param string $name Display name of the bank.
     * @param string|null $url Optional EPS URL for the bank.
     * @param string $countryCode ISO country code for the bank.
     */
    public function __construct(string $bic, string $name, ?string $url = null, string $countryCode = '')
    {
        $this->bic = $bic;
        $this->name = $name;
        $this->url = $url;
        $this->countryCode = $countryCode;
    }

    /**
     * @return string
     */
    public function getBic(): string
    {
        return $this->bic;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }
}