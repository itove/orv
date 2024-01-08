<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Service\Data;

class IndexController extends AbstractController
{
    private $data;
    private $translator;
    
    public function __construct(TranslatorInterface $translator, Data $data)
    {
        $this->data = $data;
        $this->translator = $translator;
    }
    
    #[Route('/', name: 'app_index')]
    public function index(Request $request): Response
    {
        $locale = $request->getLocale();
        // $arr['slides'] = $this->data->getNodeByTag('carousel', 6);
        $sliders1 = $this->data->findNodeByTag('home-slider-1', 3);
        $nodes = $this->data->findNodeByRegion('news', 3);
        $homeAbout = $this->data->findNodeByRegion('home-about', 1);
        $homeService = $this->data->findNodeByRegion('home-service', 1);
        $sliders2a = $this->data->findNodeByCategory('series-500', 6);
        $sliders2b = $this->data->findNodeByCategory('series-600', 6);
        $data = [
          'path' => '',
          'nodes' => $nodes,
          'sliders1' => $sliders1,
          'sliders2a' => $sliders2a,
          'sliders2b' => $sliders2b,
          'homeAbout' => $homeAbout[0],
          'homeService' => $homeService[0],
          'class' => 'page-home position-absolute',
          'page_title' => $this->translator->trans('Home'),
        ];
        return $this->render('index/index.html.twig', $data);
    }
}
