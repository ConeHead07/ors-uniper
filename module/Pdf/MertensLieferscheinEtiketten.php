<?php
/**
 * Created by PhpStorm.
 * User: f.barthold
 * Date: 28.10.2021
 * Time: 12:55
 */

namespace module\Pdf;

class MertensLieferscheinEtiketten extends \TCPDF {
    private static $iXPointsDefault = 141.732;
    private static $iYPointsDefault = 225.772;

    public function __construct(string $orientation = 'L', string $unit = 'mm', $format = 'ORS_ETIKETT', bool $unicode = true, string $encoding = 'UTF-8', bool $diskcache = false, $pdfa = false)
    {
        if (empty($format)) {
            $format = [self::$iXPointsDefault, self::$iYPointsDefault];
        }
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);

        // set document information
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('merTens AG');
        $this->SetTitle('Etiketten');
        $this->SetSubject('Lieferung NewHomeOffice');
        $this->SetKeywords('merTens, ORS, Uniper, NewHomeOffice');

        $this->setHeaderData('', '', '', '', '', '');
        $this->setFooterData('', '');

        // remove default header/footer
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);

// set font
        $this->SetFont('helvetica', '', 9);
    }

    public function addArtikel(string $lsNr, string $lsDatum, array $Artikel) {
        $lsArtikel = $Artikel['Artikel'];
        $link = $Artikel['Link'];

//         141.732;
//         225.772;
        $page_format = array(
            'MediaBox' => array ('llx' => 0, 'lly' => 0, 'urx' => 80, 'ury' =>50),
            'CropBox' => array ('llx' => 0, 'lly' => 0, 'urx' => 80, 'ury' => 50),
            'BleedBox' => array ('llx' => 0, 'lly' => 0, 'urx' => 80, 'ury' => 50),
            'TrimBox' => array ('llx' => 10, 'lly' => 0, 'urx' => 80, 'ury' => 50),
//            'ArtBox' => array ('llx' => 0, 'lly' => 0, 'urx' => 140, 'ury' => 225),
        );
        // $this->SetMargins(2, 2, 2, true);
        $this->SetTopMargin(2);
        $this->SetLeftMargin(2);
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);
        $this->getPageHeight();
        $this->setFooterMargin(0);
        $this->bMargin = 0;
        $this->pagedim[$this->page]['bm'] = 0;

        $this->AddPage('L', $page_format);
        // $this->SetMargins(2, 2, 2, true);
        $this->SetTopMargin(2);
        $this->SetLeftMargin(2);
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);
        $this->getPageHeight();
        $this->setFooterMargin(0);
        $this->bMargin = 0;
        $this->pagedim[$this->page]['bm'] = 0;


        // new style
        $style = array(
            'border' => false,
            'padding' => 0,
            'fgcolor' => array(0,0,0),
            'bgcolor' => false
        );

        $innerContentMaxW = 72; // x,y,w.h in mm als Fließkommazahl
        $x = 4;
        $y = 4;
        $w = 72;
        if ($link) {
            // QRCODE,H : QR-CODE Best error correction
            $bw = 22;
            $bh = 22;
            $this->write2DBarcode($link, 'QRCODE,L', $x, $y, $bw, $bh, $style, 'N');
            $x+= $bw + 3;
            $w-= ($bw + 3);
        }

        $html = "<p>$lsNr<br>$lsDatum<br>$lsArtikel</p>";

        // $this->writeHTML($html, '', true, '', '', '');
        $border = 0;
        $this->writeHTMLCell($w, '', $x, $y, $html, $border);

    }

    public function setArtikels(string $lsNr, string $lsDatum, array $aArtikels)
    {
        $iNumPages = count($aArtikels);
        for ($i = 0; $i < $iNumPages; $i++) {
            $this->addArtikel($lsNr, $lsDatum, $aArtikels[$i]);
        }
    }
}
