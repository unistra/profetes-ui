(:
    Copyright Université de Strasbourg (2015)

    Daniel Bessey <daniel.bessey@unistra.fr>

    This software is a computer program whose purpose is to display course information
    extracted from a Profetes database on a website.

    See LICENSE for more details.
:)
declare namespace cdm="http://cdm-fr.fr/2006/CDM-frSchema";

let $unistra := 'FR_RNE_0673021V_OR'

for $n in distinct-values(collection('%collection%')/cdm:CDM[cdm:orgUnit/@id = $unistra]/cdm:program/cdm:level/cdm:subBlock/cdm:extension/order/@n)
order by number($n)

return distinct-values(collection('%collection%')/cdm:CDM/cdm:program/cdm:level/cdm:subBlock[cdm:extension/order/@n = $n]/cdm:subBlock/text())