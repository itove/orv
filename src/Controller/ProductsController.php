<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Data;

class ProductsController extends AbstractController
{
    private $data;
    private $translator;
    
    public function __construct(TranslatorInterface $translator, Data $data)
    {
        $this->data = $data;
        $this->translator = $translator;
    }
    
    #[Route('/series/500', name: 'app_series_500')]
    public function index(Request $request): Response
    {
        $locale = $request->getLocale();
        $request = $this->data->findNodeByRegionAndLocale('request', $locale);
        $hero = $this->data->findNodeByRegion('products-hero', 1)[0];
        $sliders1 = $this->data->findNodeByTag('products-slider-1', 3);
        $sliders2 = $this->data->findNodeByTag('products-slider-2', 3);
        $sliders3 = $this->data->findNodeByTag('products-slider-3', 3);
        $sliders4 = $this->data->findNodeByTag('products-slider-4', 3);
        $sliders5 = $this->data->findNodeByTag('products-slider-5', 3);
        $sliders6 = $this->data->findNodeByCategory('gaizhuangche-1', 3);
        $spec1 = $this->data->findNodeByTag('products-spec-1', 1)[0];
        $improves = $this->data->findNodeByTag('products-improves', 5);
        $conf = $this->data->findConfByLocale($locale);
        $beian = $this->data->findNodeByRegion('beian', 1)[0];
        $wechat = $this->data->findNodeByRegion('footer-wechatqr', 1)[0];
        $miniprog = $this->data->findNodeByRegion('footer-miniprogqr', 1)[0];
        $data = [
          'class' => 'page-products position-absolute',
          'page_title' => $this->translator->trans('Products'),
          'request' => $request,
          'hero' => $hero,
          'improves' => $improves,
          'sliders1' => $sliders1,
          'sliders2' => $sliders2,
          'sliders3' => $sliders3,
          'sliders4' => $sliders4,
          'sliders5' => $sliders5,
          'sliders6' => $sliders6,
          'spec1' => $spec1,
          'conf' => $conf,
          'beian' => $beian,
          'wechat' => $wechat,
          'miniprog' => $miniprog,
        ];
        return $this->render('products/index.html.twig', $data);
    }
    
    #[Route('/series/600', name: 'app_series_600')]
    public function index2(Request $request): Response
    {
        $locale = $request->getLocale();
        $request = $this->data->findNodeByRegionAndLocale('request', $locale);
        $hero = $this->data->findNodeByRegion('products-2-hero', 1)[0];
        $sliders1 = $this->data->findNodeByTag('products-2-slider-1', 3);
        $sliders2 = $this->data->findNodeByTag('products-2-slider-2', 3);
        $sliders3 = $this->data->findNodeByTag('products-2-slider-3', 3);
        $sliders4 = $this->data->findNodeByTag('products-2-slider-4', 3);
        $sliders5 = $this->data->findNodeByTag('products-2-slider-5', 3);
        $sliders6 = $this->data->findNodeByCategory('gaizhuangche-1', 3);
        $spec1 = $this->data->findNodeByTag('products-2-spec-1', 1)[0];
        $improves = $this->data->findNodeByTag('products-2-improves', 5);
        $conf = $this->data->findConfByLocale($locale);
        $beian = $this->data->findNodeByRegion('beian', 1)[0];
        $wechat = $this->data->findNodeByRegion('footer-wechatqr', 1)[0];
        $miniprog = $this->data->findNodeByRegion('footer-miniprogqr', 1)[0];
        $data = [
          'class' => 'page-products position-absolute',
          'page_title' => $this->translator->trans('Products'),
          'request' => $request,
          'hero' => $hero,
          'improves' => $improves,
          'sliders1' => $sliders1,
          'sliders2' => $sliders2,
          'sliders3' => $sliders3,
          'sliders4' => $sliders4,
          'sliders5' => $sliders5,
          'sliders6' => $sliders6,
          'spec1' => $spec1,
          'conf' => $conf,
          'beian' => $beian,
          'wechat' => $wechat,
          'miniprog' => $miniprog,
        ];
        return $this->render('products/index.html.twig', $data);
    }
}
