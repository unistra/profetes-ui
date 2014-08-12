<?php

namespace Unistra\Profetes;

class ProgramId
{
    private $id;

    public function __construct($id)
    {
        $pattern = '/^fr-rne-06\d{5}[a-z]-pr-\w+-\w+$/';
        if (! preg_match($pattern, $id)) {
            throw new \InvalidArgumentException(
                sprintf('%s is not a valid id for a program', $id)
            );
        }
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getResourcePath()
    {
        $id = $this->id;
        $id = strtoupper($id);
        $parts = explode('-', $id);

        #format: /db/CDM/WSDiplomeCDM-0673021V-FRAN-PS103-202.xml pour
        #fr-rne-0673021v-pr-ps103-202
        return sprintf(
            '%%collection%%/WSDiplomeCDM-%s-FRAN-%s-%s.xml',
            $parts[2],
            $parts[4],
            $parts[5]
        );
    }

    public static function fromBestGuess($id)
    {
        $id = strtolower($id);
        $id = str_replace(['_', '.', '/'], '-', $id);

        if (preg_match("/^[a-z0-9]{3,}-\d+$/", $id)) {
            $id = 'fr-rne-0673021v-pr-' . $id;
        }

        return new self($id);
    }
}
