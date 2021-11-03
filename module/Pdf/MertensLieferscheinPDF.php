<?php
/**
 * Created by PhpStorm.
 * User: f.barthold
 * Date: 28.10.2021
 * Time: 12:52
 */

namespace module\Pdf;


class MertensLieferscheinPDF extends MertensBasePDF
{
    protected $auftragsdaten = [];
    protected $lieferscheindaten = [];
    protected $leistungen = [];
    protected $leistungenLabels = [];
    protected $aEtikettenCheck = [];
    protected $sigMtDataUrlSrc = '';
    protected $sigKdDataUrlSrc = '';
    protected $sigMtImg = '';
    protected $sigKdImg = '';
    protected $sigDatum = '';
    protected $sigAnkunft = '';
    protected $sigAbfahrt = '';
    protected $sigSchreibtischGeprueft = false;
    protected $sigEtikettierungen = '';
    protected $sigKdName = '';

    public function __construct(string $orientation = 'P', string $unit = 'mm', string $format = 'A4', bool $unicode = true, string $encoding = 'UTF-8', bool $diskcache = false, bool $pdfa = false)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);

        $this->SetTitle('Lieferschein');
    }

    public function setAuftragsdaten(array $daten) {
        $this->auftragsdaten = $daten;
        return $this;
    }

    public function setLieferscheindaten(array $daten) {
        $this->lieferscheindaten = $daten;
        foreach($daten as $k => $val) {
            if (empty($val)) {
                continue;
            }
            switch($k) {
                case 'sig_mt_dataurl':
                    $this->setAbnahmeSigMtDataUrl($val);
                    break;

                case 'sig_kd_dataurl':
                    $this->setAbnahmeSigKdDataUrl($val);
                    break;

                case 'ankunft':
                    $this->setAnkunft($val);
                    break;

                case 'abfahrt':
                    $this->setAbfahrt($val);
                    break;

                case 'lieferdatum':
                    $this->setAbnahmeDatum($val);
                    break;

                case 'sig_kd_unterzeichner':
                    $this->setAbnahmeSigKdName($val);
                    break;

                case 'etikettierung_erfolgt':
                    if (is_string($val)) {
                        $val = json_decode($val, JSON_OBJECT_AS_ARRAY);
                    } elseif (is_object($val)) {
                        $val = (array)$val;
                    }
                    if (is_array($val)) {
                        $this->setEtikettierungen( (array)$val );
                    }
                    break;

                case 'funktionspruefung_erfolgt':
                    if (is_array($val)) {
                        $this->sigSchreibtischGeprueft = in_array( 'Schreibtisch', $val);
                    }
                    elseif (is_string($val)) {
                        $this->sigSchreibtischGeprueft = false !== strpos($val, 'Schreibtisch');
                    }
            }
        }
        return $this;
    }

    public function setLeistungen(array $daten) {
        $this->leistungen = $daten;
        $this->leistungenLabels = array_map(function($item) { return $item['Kategorie']; }, $daten);
        $this->aEtikettenCheck = [];
        foreach($this->leistungenLabels as $k) {
            $this->aEtikettenCheck[$k] = '&nbsp;';
        }
        return $this;
    }

    public function setAbnahmeSigMtDataUrl(string $dataurl) {
        $dataInfo = $this->getDataUrlTypeInfos($dataurl);
        if (!empty($dataInfo) && !empty($dataInfo['width'])) {
            $f = $dataInfo['whfactor'];
            $dataStart = $dataInfo['dataStart'];
            $this->sigMtDataUrlSrc = '@' . substr($dataurl, $dataStart);

            $this->sigMtImg = '<img height="12" src="' . $this->sigMtDataUrlSrc . '">';
        }
        return $this;
    }

    public function setAbnahmeSigKdDataUrl(string $dataurl) {
        $dataInfo = $this->getDataUrlTypeInfos($dataurl);
        if (!empty($dataInfo)) {
            $dataStart = $dataInfo['dataStart'];
            $this->sigKdDataUrlSrc = '@' . substr($dataurl, $dataStart);
            $this->sigKdImg = '<img height="12" src="' . $this->sigKdDataUrlSrc . '">';
        }
        return $this;
    }
    // $this->sigKdText

    public function setAbnahmeSigKdName(string $kdUnterzeichnerName) {
        $this->sigKdName = $kdUnterzeichnerName;
        return $this;
    }

    public function setAbnahmeDatum(string $datum) {
        $this->sigDatum = date('d.m.Y', strtotime($datum));
        return $this;
    }

    public function setAnkunft(string $zeit) {
        $this->sigAnkunft = substr($zeit, 0, 5);
        return $this;
    }

    public function setAbfahrt(string $zeit) {
        $this->sigAbfahrt = substr($zeit, 0, 5);
        return $this;
    }

    public function setEtikettierungen(array $aEtikettierungen) {
        // $aEtikettierungen = $lieferschein['etikettierung_erfolgt']
        $this->sigEtikettierungen = $aEtikettierungen;

        if (count($aEtikettierungen)) {
            $aEtikettierteItem = array_values($aEtikettierungen);
            foreach($aEtikettierteItem as $k) {
                if (isset($this->aEtikettenCheck[$k])) {
                    $this->aEtikettenCheck[$k] = 'x';
                }
            }
        }
        return $this;
    }

    public function setSchreibtischGeprueft(bool $geprueft) {
        $this->sigSchreibtischGeprueft = $geprueft;

        return $this;
    }

    public function create() {
        $fitbox = 'RT';
        $this->AddPage();

        $auftrag = $this->auftragsdaten;
        $lsdata = $this->lieferscheindaten;

        $lieferscheinCaption = $this->getCaption();

        $sender = 'merTens AG - Stahlwerk Becker 8 - D-47877 Willich';

        $recipient = $auftrag['vorname'] . ' ' . $auftrag['name'] . "\r\n";
        $recipient.= $auftrag['strasse'] . "\r\n";
        $recipient.= $this->getLaenderKuerzelByLand($auftrag['land']) . "-{$auftrag['plz']} {$auftrag['ort']}\r\n";

        $aBriefkopfRefData = [
//            ['Referenznummer', '12345'],
            ['Ihre KID', !empty($auftrag['kid']) ? $auftrag['kid'] : ''],
            ['Ihre Durchwahl', $auftrag['fon']],
            ['Ihr Bestellzeichen', 'UNIPER-ORS-' . $auftrag['aid']],
            ['', ''],
            ['', 'Willich, ' . date('d.m.Y')],
        ];
        $auftragsliste = $this->getAuftragsliste();

        $kundenAbnahme = $this->getKundenabnahme();

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
            if ($ri > 20) {
                break;
            }
        }
        $this->Ln(4);

        $this->writeHTML($auftragsliste);

        $this->SetFont('helvetica', '', 9);
        $this->Ln(10);
        $this->writeHTML($kundenAbnahme);
    }

    public function getCaption() {
        $auftrag = $this->auftragsdaten;
        return "Lieferschein\nNr. U-" . str_pad($auftrag['aid'], 5, '0', STR_PAD_LEFT );
    }

    public function getAuftragsliste() {
        $table = <<<EOT
<table style="border:1px solid gray" width="99%" cellspacing="0" cellpadding="1" border="0">
    <tr>
        <td style="font-weight:bold;padding-bottom:10px;" width="8%" align="left">Anzahl</td>
        <td style="padding-bottom:10px;" width="8%">&nbsp;</td>
        <td style="font-weight:bold;padding-bottom:10px;" width="63%">Artikel</td>
        <td style="font-weight:bold;padding-bottom:10px;" width="10%" align="right">B-Menge</td>
        <td style="font-weight:bold;padding-bottom:10px;" width="10%" align="right">R-Menge</td>
    </tr>
    <tr>
        <td colspan="5">
        <hr style="height:2px;margin-top:10px"/>
        </td>
    </tr>
EOT;

        $leistungen = $this->leistungen;

        $iNumLstg = count($leistungen);
        for($i = 0; $i < $iNumLstg; $i++) {

            $_item = $leistungen[$i];
            $unit = $_item['leistungseinheit'];
            $kategorie = $_item['Kategorie'];
            $anzahl = $_item['menge_mertens'];
            $artikel = $_item['Bezeichnung'];
            $BMenge = $anzahl;
            $RMenge = 0;
            $table.= <<<EOT
    <tr>
        <td style="font-weight:bold;" align="left"><font weight="bold">$anzahl</font></td>
        <td style="font-weight:bold;" align="right">$unit</td>
        <td align="left">
<div style="font-weight:bold;">$kategorie</div>
$artikel</td>
        <td style="font-weight:bold;" align="right">$BMenge</td>
        <td style="font-weight:bold;" align="right">$RMenge</td>
    </tr>
EOT;
        }
        $table.= '
</table
';
        return $table;
    }

    protected function getKundenabnahme() {
        $sig_mt = $this->sigMtImg ?: '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;';
        $sig_kd = $this->sigKdImg ?: '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;';
        $sig_kd_txt = $this->sigKdName ?: '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;';
        $sig_datum = $this->sigDatum ?: '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;';
        $sig_pruefung = $this->sigSchreibtischGeprueft ? 'x' : '&nbsp;';
        $sig_ankunft = $this->sigAnkunft ?: '&nbsp; &nbsp; :&nbsp; &nbsp;';
        $sig_abfahrt = $this->sigAbfahrt ?:  '&nbsp; &nbsp; :&nbsp; &nbsp;';

        $aEtikettenCheck2 = [];
        foreach($this->aEtikettenCheck as $k => $check) {
            $aEtikettenCheck2[] = "[ $check ] $k";
        }
        $sig_etiketen_check = implode($aEtikettenCheck2, ' &nbsp; &nbsp; &nbsp; &nbsp;');

        $kundenAbnahme = <<<EOT
<div>Die Ware wurde ordnungsgemäß geliefert und in einwandfreiem Zustand montiert. Ebenfalls bestätigen Sie hiermit, dass
durch uns keine Schäden an Ihrem Gebäude und Ihren Räumlichkeiten entstanden sind. Sollten Schäden entstanden
sein, notieren Sie diese bitte auf dem beiliegendem Reklamationsformular.</div>
<div></div>
<table width="99%" cellpadding="0" cellspacing="0">
    <tr>
        <td width="35%">Ihr Montageteam der merTens AG</td>
        <td>$sig_mt<hr width="250px"></td>    
    </tr>
    <tr>
        <td colspan="2">
            <div style="height:5px;overflow:hidden;"></div>
            <table>
                <tr>
                <td width="50">Ankunft</td>
                <td width="35">$sig_ankunft<hr width="35px"></td>
                <td width="100"> Uhr &nbsp; &nbsp; &nbsp; &nbsp; Abfahrt </td>
                <td width="35">$sig_abfahrt<hr></td>
                <td width="30"> Uhr</td>
                </tr>
             </table>
        </td>    
    </tr>
    <tr>
        <td colspan="2"><div style="height:5px;overflow:hidden;"></div>
            Etikettierung erfolgt:<br>
            $sig_etiketen_check                             
        </td>
    </tr>
EOT;
        if (strpos($sig_etiketen_check, 'Schreibtisch')) {
            $kundenAbnahme .= <<<EOT
    <tr>
        <td colspan="2"><div style="height:5px;overflow:hidden;"></div>
            Funktionsprüfung erfolgt:<br>
            [ $sig_pruefung ] Schreibtisch                              
        </td>
    </tr>
EOT;
        }
        $kundenAbnahme.= <<<EOT
    <tr>
        <td colspan="3">
            <table>
                <tr>
                    <td width="100">
                        <div style="height:5px;overflow:hidden;"></div>
                        $sig_datum<hr width="55px">
                        <br>(Datum)
                    </td>
                    <td width="200">
                        <div style="height:5px;overflow:hidden;"></div>
                        $sig_kd_txt<hr width="170px">
                        <br>(Name Kunde in Blockbuchstaben)
                    </td>  
                    <td width="180">
                        <div style="height:5px;overflow:hidden;"></div>
                        $sig_kd<hr width="180px">
                        <br>(Kunde Unterschrift)
                    </td>  
                </tr>
            </table>
        </td> 
    </tr>

</table>
EOT;
        return $kundenAbnahme;
}
}
