<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Service\Data;

class AboutController extends AbstractController
{
    private $data;
    private $translator;
    
    public function __construct(TranslatorInterface $translator, Data $data)
    {
        $this->data = $data;
        $this->translator = $translator;
    }
    
    #[Route('/about', name: 'app_about')]
    public function index(Request $request): Response
    {
        $locale = $request->getLocale();
        $hero = $this->data->findNodeByRegion('about-hero', $locale, 1);
        $about = $this->data->findNodeByRegion('about_dongfeng_orv', $locale, 1);
        $service = $this->data->findNodeByRegion('online_service', $locale, 1);
        $spare = $this->data->findNodeByRegion('spare_guarantee', $locale, 1);
        $timeline_bg = $this->data->findNodeByRegion('timeline-bg', $locale, 1);
        $timeline = $this->data->findNodeByRegion('timeline', $locale);
        $honors = $this->data->findNodeByRegion('honor', $locale, 9);
        $news = $this->data->findNodeByRegion('news', $locale, 3);
        $info = $this->data->getInfo($locale);
        $data = [
          'page_title' => $this->translator->trans('About Us'),
          'class' => 'page-about',
          'hero' => empty($hero) ? [] : $hero[0],
          'about' => empty($about) ? [] : $about[0],
          'timeline' => $timeline,
          'timeline_bg' => empty($timeline_bg) ? '' : $timeline_bg[0],
          'service' => empty($service) ? [] : $service[0],
          'spare' => empty($spare) ? [] : $spare[0],
          'honors' => $honors,
          'news' => $news,
          'info' => $info,
        ];
        return $this->render('about/index.html.twig', $data);
    }
}
