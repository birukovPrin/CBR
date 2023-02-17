<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\ExchangeService;
use App\Services\CacheCollectionService;
use App\Providers\CBRProvider;
use App\Form\RateRequestForm;
use App\Form\RateRequestFormType;
use RuntimeException;

class ExchangeController extends AbstractController
{
    private ExchangeService $service;

    public function __construct(CBRProvider $provider, CacheCollectionService $cache)
    {
        $this->service = new ExchangeService($provider, $cache);
    }

    /**
     * @Route("/rate", name="rate")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $form = new RateRequestForm();
        $form = $this->createForm(RateRequestFormType::class, $form);
        $form->handleRequest($request);

        $rates = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            try{
                $rates = $this->service->getDynamicRate(
                    $data->getCurrencyChar(),
                    $data->getBaseChar(),
                    $data->getPrevDate(), 
                    $data->getDate()
                );
            } catch(RuntimeException $e) {
                $message = $e->getMessage();
            };
        }

        return $this->render('exchange/test.html.twig', [
            'form' => $form->createView(),
            'rates' => array_slice($rates, -2, 2),
            'message' => $message ?? '',
        ]);
    }
}