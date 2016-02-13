<?php

namespace AppBundle\Controller\Api\Corporation;

use AppBundle\Controller\AbstractController;
use AppBundle\Controller\ApiControllerInterface;
use AppBundle\Entity\Corporation;
use AppBundle\Entity\MarketOrderGroup;
use AppBundle\Security\AccessTypes;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * MarketOrder controller.
 *
 */
class MarketOrderController extends AbstractController implements ApiControllerInterface
{

    /**
     * @Route("/{id}/marketorders", name="api.corporation.marketorders", options={"expose"=true})
     * @ParamConverter(name="corp", class="AppBundle:Corporation")
     * @Method("GET")
     * @Secure(roles="ROLE_DIRECTOR")
     */
    public function indexAction(Corporation $corp)
    {
        $this->denyAccessUnlessGranted(AccessTypes::VIEW, $corp, 'Unauthorized access!');

        $repo = $this->getDoctrine()->getRepository('AppBundle:MarketOrder');
        $newestGroup = $this->getDoctrine()->getRepository('AppBundle:MarketOrderGroup')
            ->getLatestMarketOrderGroup($corp);

        if (!$newestGroup instanceof MarketOrderGroup){
            return $this->jsonResponse(json_encode(['error' => 'not found']), 400);
        }

        $orders = $repo->getBuyOrders($newestGroup);
        $sellorders = $repo->getSellOrders($newestGroup);

        $total_onMarket = array_reduce($sellorders, function($carry, $data){
            if ($carry === null){
                return ($data->getVolumeRemaining() * $data->getPrice());
            }

            return $carry + ($data->getVolumeRemaining() * $data->getPrice());
        });

        $total_escrow = array_reduce($orders, function($carry, $data){
            if($carry === null){
                return $data->getEscrow();
            }

            return $carry + $data->getEscrow();
        });

        $merged_orders = array_values(array_merge($orders, $sellorders));

        $updated_orders = $this->get('app.itemdetail.manager')
            ->updateDetails($merged_orders);

        $merged_orders = $updated_orders;

        $items = [
            'items' => $merged_orders,
            'total_escrow' => $total_escrow,
            'total_on_market' => $total_onMarket
        ];

        $json = $this->get('jms_serializer')->serialize($items,'json');

        return $this->jsonResponse($json);

    }
}
