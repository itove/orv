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
        
        $hero = $this->data->findNodeByCategoryAndRegion($category, 'products-hero', 1, null, $locale);
        $sliders1 = $this->data->findNodeByCategoryAndTag($category, 'products-slider-1', 3, null, $locale);
        $sliders2 = $this->data->findNodeByCategoryAndTag($category, 'products-slider-2', 3, null, $locale);
        $sliders3 = $this->data->findNodeByCategoryAndTag($category, 'products-slider-3', 3, null, $locale);
        $sliders4 = $this->data->findNodeByCategoryAndTag($category, 'products-slider-4', 3, null, $locale);
        $sliders5 = $this->data->findNodeByCategoryAndTag($category, 'products-slider-5', 3, null, $locale);
        $sliders6 = $this->data->findNodeByCategoryAndTag($category, 'products-slider-6', 3, null, $locale);
        $spec1 = $this->data->findNodeByCategoryAndTag($category, 'products-spec-1', 1, null, $locale);
        $improves = $this->data->findNodeByCategoryAndTag($category, 'products-improves', 5, null, $locale);
        
        $info = $this->data->getInfo($locale);
        
        $data = [
          'class' => 'page-products position-absolute',
          'page_title' => $this->translator->trans('Products'),
          'request' => $request,
          'hero' => empty($hero)? [] : $hero[0],
          'improves' => $improves,
          'sliders1' => $sliders1,
          'sliders2' => $sliders2,
          'sliders3' => $sliders3,
          'sliders4' => $sliders4,
          'sliders5' => $sliders5,
          'sliders6' => $sliders6,
          'spec1' => empty($spec1)? [] : $spec1[0],
          'info' => $info,
        ];
        return $this->render('products/index.html.twig', $data);
    }
}
