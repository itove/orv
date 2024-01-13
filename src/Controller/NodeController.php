<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Service\Data;

class NodeController extends AbstractController
{
    private $data;
    private $translator;
    
    public function __construct(TranslatorInterface $translator, Data $data)
    {
        $this->data = $data;
        $this->translator = $translator;
    }
    
    #[Route('/news', name: 'app_news_list')]
    public function index(Request $request): Response
    {
        $locale = $request->getLocale();
        $region = 'news';
        $page = $request->query->get('p');
        $limit = 9;
        if (is_null($page) || empty($page)) {
          $page = 1;
        }
        $offset = $limit * ($page - 1);
        
        $nodes = $this->data->findNodeByRegion($region, $locale, $limit, $offset);
        $nodes_all = $this->data->findNodeByRegion($region, $locale);
        $tag = $this->data->findTagByLabel($region);
        $hero = $this->data->findNodeByRegion('news-hero', $locale, 1);

        $arr = $this->data->getSome();
        $arr['node'] = $tag;
        $arr['nodes'] = $nodes;
        $arr['page'] = $page;
        $arr['page_count'] = ceil(count($nodes_all) / $limit);
        $info = $this->data->getInfo($locale);

        $data = [
          'nodes' => $nodes,
          'hero' => empty($hero) ? [] : $hero[0],
          'class' => 'page-news-list',
          'page_title' => $this->translator->trans('News'),
          'page' => $page,
          'page_count' => ceil(count($nodes_all) / $limit),
          'info' => $info,
        ];
        return $this->render('node/index.html.twig', $data);
    }
    
    #[Route('news/{nid}', requirements: ['nid' => '\d+'], name: 'app_node_show')]
    #[Route('product/{nid}', requirements: ['nid' => '\d+'], name: 'app_product_show')]
    public function show(int $nid, Request $request): Response
    {
        $route = $request->attributes->get('_route');
        $pageTitle = 'News';
        if ($route == 'app_product_show') $pageTitle = 'Product';
        $locale = $request->getLocale();
        $node = $this->data->get($nid);
        $hero = $this->data->findNodeByRegion('news-hero', $locale, 1);
        $info = $this->data->getInfo($locale);
        $data = [
          'page_title' => $this->translator->trans($pageTitle),
          'class' => 'page-news-show',
          'node' => $node,
          'hero' => empty($hero) ? [] : $hero[0],
          'info' => $info,
        ];
        return $this->render('node/detail.html.twig', $data);
    }
}
