(:
    Copyright Université de Strasbourg (2015)

    Daniel Bessey <daniel.bessey@unistra.fr>

    This software is a computer program whose purpose is to display course information
    extracted from a Profetes database on a website.

    See LICENSE for more details.
:)
declare namespace cdm="http://cdm-fr.fr/2006/CDM-frSchema";

let $prefix := '{{{prefix}}}'
let $unistra := 'FR_RNE_0673021V_OR'
let $formations := collection('/db/CDM-2009')/cdm:CDM[cdm:orgUnit/@id = $unistra]/cdm:program

for $formation in $formations order by lower-case($formation/cdm:programID/text()) return concat($prefix, replace(lower-case($formation/cdm:programID/text()), '_', '-'))
