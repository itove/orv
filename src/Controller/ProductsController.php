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
        $request = $this->data->findNodeByRegion('request', $locale, 1);
        
        $hero = $this->data->findNodeByCategoryAndRegion($category, $locale, 'products-hero', 1);
        $sliders1 = $this->data->findNodeByCategoryAndTag($category, $locale, 'products-slider-1', 3);
        $sliders2 = $this->data->findNodeByCategoryAndTag($category, $locale, 'products-slider-2', 3);
        $sliders3 = $this->data->findNodeByCategoryAndTag($category, $locale, 'products-slider-3', 3);
        $sliders4 = $this->data->findNodeByCategoryAndTag($category, $locale, 'products-slider-4', 3);
        $sliders5 = $this->data->findNodeByCategoryAndTag($category, $locale, 'products-slider-5', 3);
        $sliders6 = $this->data->findNodeByCategoryAndTag($category, $locale, 'products-slider-6');
        $spec1 = $this->data->findNodeByCategoryAndTag($category, $locale, 'products-spec-1', 1);
        $views = $this->data->findNodeByCategoryAndRegion($category, $locale, 'products-views', 3);
        $improves = $this->data->findNodeByCategoryAndTag($category, $locale, 'products-improves', 5);
        $products = $this->data->findNodeByShow($locale);
        
        $info = $this->data->getInfo($locale);
        
        $data = [
          'class' => 'page-products',
          'page_title' => $this->translator->trans('Products'),
          'request' => empty($request) ? [] : $request[0],
          'hero' => empty($hero) ? [] : $hero[0],
          'improves' => $improves,
          'sliders1' => $sliders1,
          'sliders2' => $sliders2,
          'sliders3' => $sliders3,
          'sliders4' => $sliders4,
          'sliders5' => $sliders5,
          'sliders6' => $sliders6,
          'spec1' => empty($spec1)? [] : $spec1[0],
          'views' => $views,
          'products' => $products,
          'info' => $info,
        ];
        return $this->render('products/index.html.twig', $data);
    }
}
