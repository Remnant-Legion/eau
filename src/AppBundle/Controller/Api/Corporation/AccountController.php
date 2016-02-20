<?php

namespace AppBundle\Controller\Api\Corporation;

use AppBundle\Controller\AbstractController;
use AppBundle\Controller\ApiControllerInterface;
use AppBundle\Entity\Account;
use AppBundle\Entity\AccountBalance;
use AppBundle\Entity\Corporation;
use AppBundle\Security\AccessTypes;
use Carbon\Carbon;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Account controller.
 */
class AccountController extends AbstractController implements ApiControllerInterface
{
    /**
     * @Route("/{id}/account", name="api.corporation.account", options={"expose"=true})
     * @ParamConverter(name="corp", class="AppBundle:Corporation")
     * @Secure(roles="ROLE_DIRECTOR")
     * @Method("GET")
     */
    public function indexAction(Request $request, Corporation $corp)
    {
        $this->denyAccessUnlessGranted(AccessTypes::VIEW, $corp, 'Unauthorized access!');

        $date = $request->query->get('date', false);
        $accounts = $this->getDoctrine()->getRepository('AppBundle:Account')
            ->findBy(['corporation' => $corp]);

        $date = $date ? new \DateTime($date) : new \DateTime();

        $this->get('app.account.manager')->updateLatestBalances($accounts, $date);

        $json = $this->get('serializer')->serialize($accounts, 'json');

        return $this->jsonResponse($json);
    }

    /**
     * @Route("/{id}/account/data", name="api.corporation.account_data", options={"expose"=true})
     * @ParamConverter(name="corp", class="AppBundle:Corporation")
     * @Secure(roles="ROLE_DIRECTOR")
     * @Method("GET")
     */
    public function dataAllAction(Request $request, Corporation $corp)
    {
        $this->denyAccessUnlessGranted(AccessTypes::VIEW, $corp, 'Unauthorized access!');

        $date = $request->get('date', null);
        $accounts = $this->getDoctrine()->getRepository('AppBundle:Account')
            ->findBy(['corporation' => $corp]);

        $balanceRepo = $this->getDoctrine()->getRepository('AppBundle:AccountBalance');

        $accountData = [];

        // roll up our data for the graph
        foreach ($accounts as $acc) {
            if (null === $date) {
                $balances = $balanceRepo->getOrderedBalances($acc);
            } else {
                $dateTime = Carbon::createFromTimestamp($date);

                $balances = $balanceRepo->getOrderedBalancesByDate($acc, $dateTime);
            }

            foreach ($balances as $b) {
                $accountData[] = [
                    'name' => $acc->getName(),
                    'date' => $b->getCreatedAt()->setTimezone(new \DateTimeZone('UTC'))
                        ->format("Y-m-d\Th:i:s\Z"),
                    'balance' => floatval($b->getBalance()),
                ];
            }
        }

        $json = $this->get('serializer')->serialize($accountData, 'json');

        return $this->jsonResponse($json);
    }
}
