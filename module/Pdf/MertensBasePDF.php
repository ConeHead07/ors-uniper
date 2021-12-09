<?php
/**
 * Created by PhpStorm.
 * User: f.barthold
 * Date: 28.10.2021
 * Time: 12:15
 */
namespace module\Pdf;

// Extend the TCPDF class to create custom Header and Footer
class MertensBasePDF extends \TCPDF {

    protected $aBriefkopfDaten = [];

    public function __construct(string $orientation = 'P', string $unit = 'mm', $format = 'A4', bool $unicode = true, string $encoding = 'UTF-8', bool $diskcache = false, $pdfa = false)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);

        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('merTens AG');
        $this->SetTitle('Lieferschein');
        $this->SetSubject('Lieferung NewHomeOffice');
        $this->SetKeywords('merTens, ORS, Uniper, NewHomeOffice');
        $this->SetHeaderData('', '', '', '', [255, 255, 255], [255, 255, 255]);

        // set header and footer fonts
        $this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        if (1) {
            // set margins
            $this->SetFooterMargin(PDF_MARGIN_FOOTER);
        }

// set auto page breaks
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $this->setLanguageArray($l);
        }

// ---------------------------------------------------------

// set default font subsetting mode
        $this->setFontSubsetting(true);
    }


    /**
     * [ [ 'label1', 'value1'], ['', ''] als Leerzeiche ]
     * @param array $aDaten
     * @return MertensBasePDF
     */
    public function setBriefReferenzDaten(array $aDaten): self {
        $this->aBriefkopfDaten = $aDaten;
        return $this;
    }

    /**
     * [ [ 'label1', 'value1'], ['', ''] als Leerzeiche ]
     * @param array $aDaten
     * @return MertensBasePDF
     */
    public function addBriefReferenzDaten(array $aDaten): self {
        $this->aBriefkopfDaten = array_merge($this->aBriefkopfDaten, $aDaten);
        return $this;
    }

    //Page header
    public function Header() {
        global $AppBaseDir;

        $x = 149;
        $y = 10;
        $w = 50;
        $h = 25;
        $fitbox = 'RT'; // Maybe false
        // Logo
        $image_file = $AppBaseDir . 'images/mertens_logo.jpg';
        if (0) {
            $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false,
                300, '', false, false, 0, false, false, false);
        } else {
            $this->Image($image_file, $x, $y, $w, $h, 'JPG', '', 'T', true,
                300, '', false, false, 0, $fitbox, false, false);
        }
        if (false) {
            // Set font
            $this->SetFont('helvetica', 'B', 20);
            // Title
            $this->setCellPaddings(['right' => 0]);
            $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        }
    }

    // Page footer
    public function Footer() {
        global $AppBaseDir;

        $image_file = $AppBaseDir . 'images/mertens_prime_cert.jpg';
        $img_x = 175;
        $img_y = 10;
        $img_w = 14;
        $img_h = 14;
        $fitbox = 'RT'; // Maybe false
        // Position at 15 mm from bottom
        $this->SetY(-50);
        // Set font
        $this->SetFont('helvetica', '', 8);

        $text = 'Experience- & InnovationCENTER Rhein-Ruhr: Stahlwerk Becker 8, D- 47877 Willich, Fon +49 2154 4705 0, Fax +49 2154 4705 40000, Email info@mertens.ag
Internet www.mertens.ag, Business- & InspirationOFFICE Rhein-Main: Mainzer Straße 97, D- 65189 Wiesbaden, Business- & InspirationOFFICE
im merTensHAUPTSTADTQUARTIER Berlin-Brandenburg: Stresemannstraße 65, D- 10963 Berlin, Bankverbindungen: SPK Neuss, IBAN
DE02 3055 0000 0080 1096 89, Swift-Bic WELA DE DNXXX, SPK Krefeld, IBAN DE25 3205 0000 0000 2540 29, Swift-Bic SPKR DE 33XXX
Amtsgericht: Krefeld HRB 14453, Ust.-Id.-Nr.: DE 120585703, St.-Nr.: 102/5832/1139, Vorstand: Mike Mertens, Matthias Engenhorst, Aufsichtsratsvorsitzender:
Thomas R. Brünger, Unsere AGB finden Sie unter: www.mertens.ag/agb';
        $curr = $this->getAliasNumPage();
        $tot = $this->getAliasNbPages();

        $pageCaption = ' Seite  ' . $curr . ' von '. $tot .' ';
        // Wegen unbeeinflussbaren rechten Aussenabtands von Total wird die Cell-Breite überzogen
        $this->Cell(189, '', $pageCaption, 0, '', 'R');
        $this->Ln(18);
        $this->SetTextColor(123);
        $this->SetFont('helvetica', '', 7);
        $this->Cell(165, 20, 'Zertifiziertes Managementsystem | DIN EN ISO 9001:2015', 0, '', 'R',
            '', '', '', '', 'B', 'B');
        $this->x;
        $this->Image($image_file, 177, $this->y - 14, $img_w, $img_h, 'JPG', '', 'T', true,
            300, '', false, false, 0, $fitbox, false, false);
        $this->Ln(11);
        $this->Cell(16, 6, 'merTens AG', 0, '', 'L',
            '', '', '', '', '', 'B');
        $useWriteHTMLCell = false;

        // Yellow Line Config
        $YL_w = 159.5;
        $YL_h = 6;
        $YL_x = 31;
        $YL_y = $this->y + 1;
        $YL_html = '<div style="border-bottom:2px solid #ffdd0e;color:#000000;"></div>';
        $YL_b = 0;
        $YL_ap = '';
        $YL_al = '';
        $YL_fill = '';
        $YL_reseth = '';
        $YL_autopadding = '';
        if ($useWriteHTMLCell) {
            $this->writeHTMLCell($YL_w, $YL_h, $YL_x, $YL_y,
                $YL_html, $YL_b, '', $YL_fill, '', $YL_al, $YL_ap);
        } else {
            $this->MultiCell($YL_w, $YL_h, $YL_html, $YL_b, $YL_al, $YL_fill, '', $YL_x, $YL_y,
                $YL_reseth, 0, true, $YL_autopadding, 6, 'B', false);
        }
        $this->Ln(6);
        $html = '<table width="99%">' . "\n";
        $html.= '<tr>';
        $html.= '<td colspan="3"><div style="text-align: justify">' . $text . '</div></td>';
        $html.= '</tr>';
        $html.= "</table>";
        $this->writeHTMLCell(178, '', '', '', $html);
        // $this->writeHTML($html);

    }

    protected function getDataUrlTypeInfos(string &$dataurl) {
        if (substr($dataurl, 0, 5) === 'data:') {
            $p = strpos($dataurl, ',');
            $dataurlStartInfo = substr($dataurl, 0, $p);
            list($mimeType, $encoding) = explode(';', substr($dataurlStartInfo, 5, -1));

            $binary = base64_decode(substr($dataurl, $p + 1));
            $imgInfo = getimagesizefromstring($binary);

            return [
                'mimeType' => $mimeType,
                'encoding' => $encoding,
                'infoLength' => $p,
                'dataStart' => $p + 1,
                'width' => $imgInfo ? $imgInfo[0] : '',
                'height' => $imgInfo ? $imgInfo[1] : '',
                'whfactor' => $imgInfo ? ($imgInfo[0] / $imgInfo[1]) : '',
            ];
        }
        return [];
    }

    public function getLaenderKuerzelByLand($land) {
        switch($land) {
            case 'Deutschland':
                return 'D';
            case 'Niederlande':
                return 'NL';
                break;
            case 'Ungarn':
                return 'HU';

            default:
                return $land;
        }
    }
}
