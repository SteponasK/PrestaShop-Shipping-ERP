<?php

namespace Invertus\Academy\ShipmentPrintService;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;

use Invertus\Academy\Entity\Shipment;

use Picqer\Barcode\BarcodeGeneratorPNG;

use Dompdf\Dompdf;
use Dompdf\Options;

class ShipmentPrintService
{

    public function getShipment(EntityManagerInterface $entityManager, int $id)
    {
        return  $entityManager->getRepository(Shipment::class)->find($id);
    }

    public function getShipmentInformation(Shipment $shipment): array
    {
        return
        [
            'country'=> $shipment->getCountry(),
            'company' => $shipment->getCompany(),
            'firstName' => $shipment->getFirstName(),
            'lastName' => $shipment->getLastName(),
            'address1' => $shipment->getAddress1(),
            'address2'=> $shipment->getAddress2(),
            'postcode' => $shipment->getPostCode(),
            'city' => $shipment->getCity(),
            'phone' => $shipment->getPhone(),
            'phoneMobile' => $shipment->getPhoneMobile(),
            'barcode' => $shipment->getBarcode()
        ];
    }

    public function generatePdfFile(array $shipmentInformation): Dompdf
    {
        $pdf = new Dompdf();
        $this->addDataToPdf($pdf, $shipmentInformation);
        $pdf->render();
        return $pdf;
    }

    private function getBarcodeHtml(string $value): string
    {
        $generator = new BarcodeGeneratorPNG();
        return '<div style="text-align: center;">
            <img src="data:image/png;base64,' . base64_encode($generator->getBarcode($value, $generator::TYPE_CODE_128, widthFactor:1)) . '" >
            </div>';
    }

    private function addDataToPdf(Dompdf $pdf, array $shipmentInformation): void
    {
        
        $html = '
        
        <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                 <style>
                    body { font-family: DejaVu Sans; }
                </style>
            </head>';

        $html .= '<body>
        <table border="1" width="50%" style="margin: 0 auto; text-align: center;">
        <tr>
            <th>Field</th>
            <th>Value</th>
        </tr>';

        foreach ($shipmentInformation as $field => $value){
            if ($field === 'barcode')
            {
                continue;
            }
            $html .= '<tr>
                <td>' . ucfirst($field) . '</td>
                <td>' . $value . '</td>
            </tr>';
        }

        $html .='</table>';
        $html .= '<br>';
        $html .= $this->getBarcodeHTML($shipmentInformation['barcode']);
        $html .= '</body>';
        $pdf->loadHtml($html, 'UTF-8');
    }
}