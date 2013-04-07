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
 * Html2Pdf invoice block.
 *
 * @category  FireGento
 * @package   FireGento_Pdf
 * @author    FireGento Team <team@firegento.com>
 * @copyright 2013 FireGento Team (http://www.firegento.de). All rights served.
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version   $Id:$
 * @since     0.1.0
 */
class FireGento_PdfHtml_Block_Invoice extends Mage_Sales_Block_Order_Print_Invoice
{
    /**
     * Return notes.
     *
     * @return string
     */
    public function getNote()
    {
        $notes = array();

        $result = new Varien_Object();
        $result->setNotes($notes);
        Mage::dispatchEvent('firegento_pdf_invoice_insert_note', array('order' => $this->getOrder(), 'invoice' => $this->getInvoice(), 'result' => $result));
        $notes = array_merge($notes, $result->getNotes());

        $notes[] = Mage::helper('firegento_pdf')->__('Invoice date is equal to delivery date.');

        // Get free text notes.
        $note = Mage::getStoreConfig('sales_pdf/invoice/note');
        if (!empty($note)) {
            $tmpNotes = explode("\n", $note);
            $notes = array_merge($notes, $tmpNotes);
        }

        return implode('<br />', $notes);
    }
}