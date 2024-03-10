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
use Picqer\Barcode\BarcodeGeneratorHTML;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ShipmentController extends AbstractController
{ 
    #[Route('/api/shipment/save/', name: 'app_save_shipment', methods: ['POST'])]
    public function save(Request $request, EntityManagerInterface $entityManager): Response
    {
        $authorizationHeader = $request->headers->get('Authorization');
        $token = str_replace('Bearer ', '', $authorizationHeader);
        if ($_ENV['API_KEY'] !== $token) {
            return new JsonResponse(['error' => 'Invalid API key'], Response::HTTP_UNAUTHORIZED);
        }
 
        $data = json_decode($request->getContent(), true);
        $this->createShipmentService($data, $entityManager);
        
        return new Response('', Response::HTTP_CREATED);
    }

    #[Route('/api/shipment/print/{id}', name: 'app_print_shipment', methods: ['GET'])]
    public function print(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $authorizationHeader = $request->headers->get('Authorization');
        $token = str_replace('Bearer ', '', $authorizationHeader);
        if ($_ENV['API_KEY'] !== $token) {
            return new JsonResponse(['error' => 'Invalid API key'], Response::HTTP_UNAUTHORIZED);
        }
        
        $shipment = $entityManager->getRepository(Shipment::class)->find($id);

        if(!$shipment){
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $shipmentInformation = $this->getShipmentInformation($shipment);

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

        foreach($shipmentInformation as $field => $value){
            if($field === 'barcode'){
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
    private function getShipmentInformation(Shipment $shipment): array
    {
        return [
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
    private function createShipmentService(array $data, EntityManagerInterface $entityManager): void
    {
        $shipment = new Shipment();
        $shipment->setCountry($data['country']);
        $shipment->setCompany($data['company']);
        $shipment->setFirstName($data['firstName']);
        $shipment->setLastName($data['lastName']);
        $shipment->setAddress1($data['address1']);
        $shipment->setAddress2($data['address2']);
        $shipment->setPostcode($data['postcode']);
        $shipment->setCity($data['city']);
        $shipment->setPhone($data['phone']);
        $shipment->setPhoneMobile($data['phoneMobile']);
        $shipment->setBarcode(decbin(time()));

        $entityManager->persist($shipment);
        $entityManager->flush();
    }
    
}
