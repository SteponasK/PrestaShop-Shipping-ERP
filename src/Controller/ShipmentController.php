<?php

namespace Invertus\Academy\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Invertus\Academy\Entity\Product;
use Invertus\Academy\Entity\Shipment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Dompdf\Dompdf;
use Invertus\Academy\ShipmentCreateService\ShipmentCreateService;
use Invertus\Academy\ShipmentPrintService\ShipmentPrintService;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ShipmentController extends AbstractController
{ 
    #[Route('/api/shipment/save/', name: 'app_save_shipment', methods: ['POST'])]
    public function save(Request $request, EntityManagerInterface $entityManager, ShipmentCreateService $service): Response
    {
        if($service->isApiKeyValid($request) === false){
            return new JsonResponse(['error' => 'Invalid API key'], Response::HTTP_UNAUTHORIZED);
        }
        $data = $service->getData($request);
        $service->createShipment($data, $entityManager);
        
        return new Response('', Response::HTTP_CREATED);
    }

    #[Route('/api/shipment/print/{id}', name: 'app_print_shipment', methods: ['GET'])]
    public function print(Request $request, EntityManagerInterface $entityManager, ShipmentPrintService $service, int $id): Response
    {
        if($service->isApiKeyValid($request) === false){
            return new JsonResponse(['error' => 'Invalid API key'], Response::HTTP_UNAUTHORIZED);
        }

        $shipment = $service->getShipment($entityManager, $id);
        if(!$shipment){
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $shipmentInformation = $service->getShipmentInformation($shipment);

        $pdf = $this->generatePdfFile($shipmentInformation);

       return new Response(
        $pdf->output(),
        Response::HTTP_OK,
        [
            'Content-Type' => 'application/pdf',
        ]
    );
    }  

    private function generatePdfFile(array $shipmentInformation): Dompdf
    {
        $pdf = new Dompdf();
        $this->addDataToPdf($pdf, $shipmentInformation);
        $pdf->render();
        return $pdf;
    }
    private function addDataToPdf(Dompdf $pdf, array $shipmentInformation): void
    {
        $html = '<table border="1" width="50%" style="margin: 0 auto; text-align: center;">
        <tr>
            <th>Field</th>
            <th>Value</th>
        </tr>';

        foreach ($shipmentInformation as $field => $value){
            if ($field === 'barcode'){
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

        $pdf->loadHtml($html);
    }
    private function getBarcodeHTML(string $value): string
    {
        $generator = new BarcodeGeneratorPNG();
        return '<div style="text-align: center;">
            <img src="data:image/png;base64,' . base64_encode($generator->getBarcode($value, $generator::TYPE_CODE_128, widthFactor:1)) . '" >
            </div>';
    }    
}
