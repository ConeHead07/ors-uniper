<?php
/**
 * Created by PhpStorm.
 * User: f.barthold
 * Date: 28.10.2021
 * Time: 12:53
 */

namespace module\Pdf;


class MertensKommissionsscheinPDF extends MertensLieferscheinPDF
{
    public function __construct(string $orientation = 'P', string $unit = 'mm', string $format = 'A4', bool $unicode = true, string $encoding = 'UTF-8', bool $diskcache = false, bool $pdfa = false)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
        $this->SetTitle('Kommissionierschein');
    }


    public function getCaption() {
        $auftrag = $this->auftragsdaten;
        return "Kommissionierschein\nNr. U-" . str_pad($auftrag['aid'], 5, '0', STR_PAD_LEFT );
    }

    public function setLieferscheindaten(array $daten)
    {
        $this->lieferscheindaten = $daten;
    }

    public function getKundenabnahme()
    {
        return '';
    }

}
