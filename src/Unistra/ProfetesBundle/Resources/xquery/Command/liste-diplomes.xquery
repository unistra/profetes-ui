(:
    Copyright Université de Strasbourg (2015)

    Daniel Bessey <daniel.bessey@unistra.fr>

    This software is a computer program whose purpose is to display course information
    extracted from a Profetes database on a website.

    See LICENSE for more details.
:)
declare namespace cdm="http://cdm-fr.fr/2006/CDM-frSchema";

let $prefix := '{{{prefix}}}'
let $codeRne := '%code_rne%'
let $formations := collection('%collection%')/cdm:CDM[cdm:orgUnit/@id = $codeRne]/cdm:program

for $formation in $formations order by lower-case($formation/cdm:programID/text()) return concat($prefix, replace(lower-case($formation/cdm:programID/text()), '_', '-'))
