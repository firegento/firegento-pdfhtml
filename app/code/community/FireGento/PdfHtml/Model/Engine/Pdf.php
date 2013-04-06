<?php
/**
 * This file is part of the FIREGENTO project.
 *
 * FireGento_GermanSetup is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * PHP version 5
 *
 * @category  FireGento
 * @package   FireGento_Pdf
 * @author    FireGento Team <team@firegento.com>
 * @copyright 2013 FireGento Team (http://www.firegento.de). All rights served.
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version   $Id:$
 * @since     0.1.0
 */
/**
 * Html2Pdf invoice rendering engine.
 *
 * @category  FireGento
 * @package   FireGento_Pdf
 * @author    FireGento Team <team@firegento.com>
 * @copyright 2013 FireGento Team (http://www.firegento.de). All rights served.
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version   $Id:$
 * @since     0.1.0
 */
class FireGento_PdfHtml_Model_Engine_Pdf
{
    protected $_html;

    /**
     * Set html.
     *
     * @param string $html
     * @return FireGento_PdfHtml_Model_Engine_Pdf
     */
    public function setHtml($html)
    {
        $this->_html = $html;
        return $this;
    }

    /**
     * Render pdf output string from html.
     *
     * @return string
     */
    public function render()
    {
        require_once 'dompdf/dompdf_config.inc.php';

        $dompdf = new DOMPDF();
        $dompdf->load_html($this->_html);
        $dompdf->render();
        $output = $dompdf->output();

        return $output;
    }
}