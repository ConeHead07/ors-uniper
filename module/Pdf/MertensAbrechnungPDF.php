<?php
/**
 * Created by PhpStorm.
 * User: f.barthold
 * Date: 28.10.2021
 * Time: 12:52
 */

namespace module\Pdf;


class MertensAbrechnungPDF extends MertensLieferscheinPDF
{
    private $abrechnungsZeitraum = '';

    public function __construct(string $orientation = 'P', string $unit = 'mm', string $format = 'A4', bool $unicode = true, string $encoding = 'UTF-8', bool $diskcache = false, bool $pdfa = false)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);

        $this->SetTitle('Leistungen');
    }

    public function create() {
        $fitbox = 'RT';
        $this->AddPage();

        $auftrag = $this->auftragsdaten;
        $lsdata = $this->lieferscheindaten;

        $lieferscheinCaption = $this->getCaption();

        $sender = 'merTens AG - Stahlwerk Becker 8 - D-47877 Willich';

        $recipient = trim($auftrag['vorname'] . ' ' . $auftrag['name']) . "\r\n";
        if (!empty($auftrag['strasse'])) {
            $recipient .= $auftrag['strasse'] . "\r\n";
        }
        if (!empty($auftrag['land']) || !empty($auftrag['stadt'])) {
            $recipient .= trim(
                    $this->getLaenderKuerzelByLand($auftrag['land'] ?? '')
                    . '-' . ($auftrag['plz'] ?? '')
                    . ' ' . ($auftrag['ort'] ?? '')
                , '- ') . "\r\n";
        }


        $iRecipientHeight = 6;
        if ($this->abrechnungsZeitraum) {
            $iRecipientHeight+= 6;
            $recipient.= "\r\n" . $this->abrechnungsZeitraum;
        }

        $aBriefkopfRefData = array_merge([
//            ['Referenznummer', '12345'],
//            ['Ihre KID', !empty($auftrag['kid']) ? $auftrag['kid'] : ''],
//            ['Ihre Durchwahl', $auftrag['fon']],
//            ['Ihr Bestellzeichen', 'UNIPER-ORS-' . $auftrag['aid']],
//            ['', ''],
            ['', 'Willich, ' . date('d.m.Y')],
        ], $this->aBriefkopfDaten);
        $auftragsliste = $this->getAuftragsliste();

        $this->Ln(10);
        $this->SetFont('helvetica', '', 8);
        $this->SetTextColor(123);
        $this->MultiCell(107, 10, $sender,
            0, 'L', 0, 0, '', '', true, '', '', '', 10, 'B', '');


        $this->SetFont('helvetica', 'B', 12);
        $this->SetTextColor(0);
        $this->MultiCell(55, 15, $lieferscheinCaption,
            0, 'L', 0, 0, '', '', true, '', '', '', 15, 'T', '');
        $this->Ln(20);

        $this->SetFont('helvetica', '', 10);
        $this->MultiCell(107, 60, $recipient,
            0, 'L', 0, 0, '', '', true);

        $iNumRefLines = count($aBriefkopfRefData);
        if ($iNumRefLines > 20) {
            die('#' . __LINE__ . ' ' . __FILE__ . ' ' . print_r(compact('iNumRefLines'), 1));
        }
        $iRefHeight = 0;
        for($ri = 0; $ri < $iNumRefLines; $ri++) {
            $_lbl = $aBriefkopfRefData[$ri][0];
            $_val = $aBriefkopfRefData[$ri][1] ?? '';

            if (1) $this->MultiCell(
                107, 5, '',
                0, 'L', 0, 0, PDF_MARGIN_LEFT, '', true, '', '', '', '', 'T', '');
            $this->MultiCell(
                35, 5, $_lbl,
                0, 'L', 0, 0, '', '', true, '', '', '', '', 'T', '');

            $this->MultiCell(
                35, 5, $_val,
                0, 'R', 0, 0, '', '', true, '', '', '', '', 'T', '');
            $this->Ln(5);
            $iRefHeight+= 5;
            if ($ri > 20) {
                break;
            }
        }
        if ($iRecipientHeight < $iRefHeight) {
            $this->Ln(4);
        } else {
            $this->Ln($iRecipientHeight);
        }

        $this->writeHTML($auftragsliste);

        $this->SetFont('helvetica', '', 9);
        $this->Ln(10);
    }

    public function setZeitraum(string $zeitraum): self {
        $this->abrechnungsZeitraum = $zeitraum;
        return $this;
    }

    public function getCaption() {
        $auftrag = $this->auftragsdaten;
        return 'Leistungen';
    }
}
