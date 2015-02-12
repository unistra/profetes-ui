<?php

/*
 * Copyright Université de Strasbourg (2015)
 *
 * Daniel Bessey <daniel.bessey@unistra.fr>
 *
 * This software is a computer program whose purpose is to diplay course information
 * extracted from a Profetes database on a website.
 *
 * See LICENSE for more details
 */

namespace Unistra\Profetes;

class ProgramId
{
    private $id;

    public function __construct($id)
    {
        $pattern = '/^fr-rne-06\d{5}[a-z]-pr-[a-z0-9]+-[a-z0-9]+$/';
        if (! preg_match($pattern, $id)) {
            throw new \InvalidArgumentException(
                sprintf('%s is not a valid id for a program', $id),
                404
            );
        }
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * fr-rne-0673021v-pr-ps103-202
     * => /db/CDM/WSDiplomeCDM-0673021V-FRAN-PS103-202.xml
     *
     * @return string
     */
    public function getResourcePath()
    {
        $parts = explode('-', strtoupper($this->id));

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
            $id = 'fr-rne-0673021v-pr-'.$id;
        }

        return new self($id);
    }
}
