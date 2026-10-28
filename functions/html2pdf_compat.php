<?php
/**
 * Wrapper de compatibilidad HTML2PDF
 *
 * El proyecto usaba la librería html2pdf antigua (con create_function, eliminada en PHP 8.0).
 * Este archivo redirige a spipu/html2pdf (instalada en vendor/), que es la versión
 * moderna y mantenida, compatible con PHP 8.2.
 *
 * Diferencias de API manejadas aquí:
 *   - Clase:   HTML2PDF  →  \Spipu\Html2Pdf\Html2Pdf  (mismo nombre de método writeHTML)
 *   - Output:  $obj->Output('file.pdf')  →  $obj->output('file.pdf')
 */

require_once __DIR__ . '/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf as SipuHtml2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;

if (!class_exists('HTML2PDF')) {
    class HTML2PDF extends SipuHtml2Pdf
    {
        /**
         * Alias en mayúsculas de output() para mantener compatibilidad
         * con el código que llama $html2pdf->Output(...)
         */
        // PHP: Output y output son el mismo método (case-insensitive).
        // Usamos parent::output() para delegar al padre sin recursión infinita.
        // Sin tipos declarados para ser compatible con la firma del padre (sin tipos).
        public function Output($name = 'document.pdf', $dest = 'I')
        {
            // La librería spipu requiere extensión .pdf en el nombre
            if (strtolower(substr($name, -4)) !== '.pdf') {
                $name .= '.pdf';
            }
            return parent::output($name, $dest);
        }
    }
}
