<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Service\Data;
use App\Entity\Conf;
use App\Entity\Region;
use App\Entity\Language;

class ContactController extends AbstractController
{
    private $data;
    private $translator;
    
    public function __construct(TranslatorInterface $translator, Data $data)
    {
        $this->data = $data;
        $this->translator = $translator;
    }
    
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $locale = $request->getLocale();
        $conf = $this->data->findConfByLocale($locale);
        $hero = $this->data->findNodeByRegionAndLocale('contact-hero', $locale);
        $contact = $this->data->findNodeByRegionAndLocale('contact_us', $locale);
        $request = $this->data->findNodeByRegionAndLocale('request', $locale);
        $info = $this->data->getInfo($locale);
        $data = [
          'class' => 'page-contact',
          'page_title' => $this->translator->trans('Contact Us'),
          'conf' => $conf,
          'hero' => $hero,
          'contact' => $contact,
          'request' => $request,
          'info' => $info,
        ];
        return $this->render('contact/index.html.twig', $data);
    }
}
