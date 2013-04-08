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

            // Register invoice and order object. This is kindof dirty but we need this to use
            // given Magento core blocks that are referencing this objects through the registry.
            // TODO: Maybe there is a better way?
            Mage::unregister('current_invoice');
            Mage::register('current_invoice', $invoice);
            Mage::register('current_order', $order);

            // Temporarily change area.
            $area = Mage::getSingleton('core/design_package')->getArea();
            Mage::getSingleton('core/design_package')->setArea('frontend');

            $layout = Mage::app()->getLayout();
            $layout->setArea('frontend')->getUpdate()->load('firegento_pdfhtml_invoice');

            $layout->generateXml()->generateBlocks();
            $layout->addOutputBlock('root');

            // Debug here to see what html is returned for rendering
            // TODO: Remove this comment
            $html = $layout->getOutput();

            // Restore area.
            Mage::getSingleton('core/design_package')->setArea($area);

            // Unregister objects to store new one in next loop. See comment above why we need to use
            // the registry for that.
            Mage::unregister('current_invoice');
            Mage::unregister('current_order');
        }

        /** @var $pdf FireGento_PdfHtml_Model_Engine_Pdf */
        $pdf = Mage::getModel('firegento_pdfhtml/engine_pdf');
        $pdf->setHtml($html);
        return $pdf;
    }
}