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
class FireGento_PdfHtml_Model_Engine_Invoice_Default
{
    /**
     * Return pdf content stream.
     *
     * @param array $invoices
     * @return FireGento_PdfHtml_Model_Engine_Pdf
     */
    public function getPdf($invoices = array())
    {
        $html = '';

        foreach ($invoices as $invoice) {
            if ($invoice->getStoreId()) {
                Mage::app()->getLocale()->emulate($invoice->getStoreId());
                Mage::app()->setCurrentStore($invoice->getStoreId());
            }

            $order = $invoice->getOrder();

            Mage::unregister('current_invoice');
            Mage::register('current_invoice', $invoice);
            Mage::register('current_order', $order);

            /** @var $block Mage_Core_Block_Template */
            $invoiceBlock = Mage::app()->getLayout()->createBlock('firegento_pdfhtml/invoice');
            $invoiceBlock->setData('area', 'frontend')
                ->setTemplate('firegento/pdfhtml/invoice.phtml');

            $totalsBlock = Mage::app()->getLayout()->createBlock('sales/order_invoice_totals', 'invoice_totals');
            $totalsBlock->setData('area', 'frontend');
            $totalsBlock->setTemplate('firegento/pdfhtml/invoice/totals.phtml');
            $invoiceBlock->append($totalsBlock);

            $taxBlock = Mage::app()->getLayout()->createBlock('tax/sales_order_tax', 'tax');
            $taxBlock->setData('area', 'frontend');
            $taxBlock->setTemplate('tax/order/tax.phtml');
            $totalsBlock->append($taxBlock);

            $html .= $invoiceBlock->renderView();

            Mage::unregister('current_invoice');
            Mage::unregister('current_order');
        }

        /** @var $pdf FireGento_PdfHtml_Model_Engine_Pdf */
        $pdf = Mage::getModel('firegento_pdfhtml/engine_pdf');
        $pdf->setHtml($html);
        return $pdf;
    }
}