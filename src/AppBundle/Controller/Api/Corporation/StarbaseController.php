<?php

namespace AppBundle\Controller\Api\Corporation;

use AppBundle\Controller\AbstractController;
use AppBundle\Controller\ApiControllerInterface;
use AppBundle\Entity\Corporation;
use AppBundle\Security\AccessTypes;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Starbase controller.
 */
class StarbaseController extends AbstractController implements ApiControllerInterface
{
    /**
     * @Route("/{id}/starbases", name="api.corporation.starbases", options={"expose"=true})
     * @ParamConverter(name="corp", class="AppBundle:Corporation")
     * @Secure(roles="ROLE_DIRECTOR")
     * @Method("GET")
     */
    public function indexAction(Request $request, Corporation $corp)
    {
        $this->denyAccessUnlessGranted(AccessTypes::VIEW, $corp, 'Unauthorized access!');

        $stations = $this->getDoctrine()->getRepository('AppBundle:Starbase')
            ->findBy(['corporation' => $corp]);

        $loctionRepo = $this->get('app.itemdetail.manager');

        $typeRepo = $this->get('evedata.registry')->get('EveBundle:ItemType');
        $attributeRepo = $this->get('evedata.registry')->get('EveBundle:ItemAttribute');
        $resourceRepo = $this->get('evedata.registry')->get('EveBundle:ControlTowerResource');

        foreach ($stations as $s) {
            $attributeData = $attributeRepo->getItemAttributes($s->getTypeId());

            $ids = array_map(function ($i) {
                return intval($i['attributeID']);
            }, $attributeData);

            $attrDetails = $attributeRepo->getAttributes($ids);
            $fuelDetails = $resourceRepo->getFuelConsumption($s->getTypeId());
            $mergedData = [];
            foreach ($attributeData as $k => $d) {
                foreach ($attrDetails as $m) {
                    if ($d['attributeID'] === $m['attributeID']) {
                        $mergedData[] = array_merge($attributeData[$k], $m);
                    }
                }
            }

            $descriptors = array_merge(
                ['attributes' => $mergedData],
                $loctionRepo->determineLocationDetails($s->getLocationId()),
                $typeRepo->getItemTypeData($s->getTypeId()),
                [
                    'fuel' => is_array($s->getFuel())
                        ? array_map(function ($d) use ($typeRepo, $attributeRepo) {
                            $data = $typeRepo->getItemTypeData($d['typeID']);

                            return [
                                'type' => $data,
                                'typeID' => $d['typeID'],
                                'quantity' => $d['quantity'],
                            ];
                        }, $s->getFuel())
                        : [],
                ]
            );

            $fuels = $this->get('app.price.manager')->updatePrices($descriptors['fuel']);

            $descriptors['fuel_consumption'] = $fuelDetails;
            $descriptors['fuel'] = $fuels;

            $s->setDescriptors($descriptors);
        }

        $json = $this->get('serializer')->serialize($stations, 'json');

        return $this->jsonResponse($json);
    }
}
