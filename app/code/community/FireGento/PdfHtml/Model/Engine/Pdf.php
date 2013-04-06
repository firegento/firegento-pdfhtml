<?php

class FireGento_PdfHtml_Model_Engine_Pdf
{
    protected $_html;

    public function setHtml($html)
    {
        $this->_html = $html;
    }

    /**
     * @return string
     */
    public function render()
    {
        require_once 'dompdf/dompdf_config.inc.php';
        $dompdf = new DOMPDF();
        $dompdf->load_html($this->_html);
        $dompdf->render();
        return $dompdf->output();
    }
}