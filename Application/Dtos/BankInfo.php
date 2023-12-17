<?php

namespace Application\Dtos;

class BankInfo
{
    private string $beneficiaryName;
    private string $Iban;
    private string $bankName;

    public function __construct(string $beneficiaryName, string $Iban, string $bankName)
    {
        $this->beneficiaryName = $beneficiaryName;
        $this->Iban = $Iban;
        $this->bankName = $bankName;
    }

    public function getBeneficiaryName(): string
    {
        return $this->beneficiaryName;
    }

    public function getIban(): string
    {
        return $this->Iban;
    }

    public function getBankName(): string
    {
        return $this->bankName;
    }
}

