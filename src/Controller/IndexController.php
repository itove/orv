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
        $sliders1 = $this->data->findNodeByTag('home-slider-1', $locale, 3);
        $nodes = $this->data->findNodeByRegion('news', $locale, 3);
        $homeAbout = $this->data->findNodeByRegion('home-about', $locale, 1);
        $homeService = $this->data->findNodeByRegion('home-service', $locale, 1);
        $sliders2a = $this->data->findNodeByCategoryAndTag('series-500', $locale, 'home-slider-2', 20);
        $sliders2b = $this->data->findNodeByCategoryAndTag('series-600', $locale, 'home-slider-2', 20);
        $slider2_bg = $this->data->findNodeByRegion('home-slider2-bg', $locale, 1);
        $data = [
          'path' => '',
          'nodes' => $nodes,
          'sliders1' => $sliders1,
          'sliders2a' => $sliders2a,
          'sliders2b' => $sliders2b,
          'slider2_bg' => empty($slider2_bg) ? [] : $slider2_bg[0],
          'homeAbout' => empty($homeAbout) ? [] : $homeAbout[0],
          'homeService' => empty($homeService) ? [] : $homeService[0],
          'class' => 'page-home',
        ];
        return $this->render('index/index.html.twig', $data);
    }
}
