<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Data;

#[Route('/products')]
class ProductsController extends AbstractController
{
    private $data;
    private $translator;
    
    public function __construct(TranslatorInterface $translator, Data $data)
    {
        $this->data = $data;
        $this->translator = $translator;
    }
    
    #[Route('/{category}', name: 'app_products')]
    public function index($category, Request $request): Response
    {
        $locale = $request->getLocale();
        $request = $this->data->findNodeByRegionAndLocale('request', $locale);
        
        $hero = $this->data->findNodeByCategoryAndRegion($category, 'products-hero', 1)[0];
        $sliders1 = $this->data->findNodeByCategoryAndTag($category, 'products-slider-1', 3);
        $sliders2 = $this->data->findNodeByCategoryAndTag($category, 'products-slider-2', 3);
        $sliders3 = $this->data->findNodeByCategoryAndTag($category, 'products-slider-3', 3);
        $sliders4 = $this->data->findNodeByCategoryAndTag($category, 'products-slider-4', 3);
        $sliders5 = $this->data->findNodeByCategoryAndTag($category, 'products-slider-5', 3);
        $sliders6 = $this->data->findNodeByCategory('gaizhuangche-1', 3);
        $spec1 = $this->data->findNodeByCategoryAndTag($category, 'products-spec-1', 1)[0];
        $improves = $this->data->findNodeByCategoryAndTag($category, 'products-improves', 5);
        
        $info = $this->data->getInfo($locale);
        
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
          'info' => $info,
        ];
        return $this->render('products/index.html.twig', $data);
    }
}
