<?php
/**
 * vim:ft=php et ts=4 sts=4
 * @author Al Zee <z@alz.ee>
 * @version
 * @todo
 */

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Conf;
use App\Entity\Node;
use App\Entity\Tag;
use App\Entity\Region;
use App\Entity\Language;
use App\Entity\Category;

class Data
{
    private $doctrine;
    
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    
    public function getSome($nid = null)
    {
        $conf = $this->doctrine->getRepository(Conf::class)->find(1);
        $nodeRepo = $this->doctrine->getRepository(Node::class);
        $regions = $this->doctrine->getRepository(Region::class)->findAll();
        $arr = [
            'description' => $conf->getDescription(),
            'keywords' => $conf->getKeywords(),
            'site_name' => $conf->getSitename(),
            'address' => $conf->getAddress(),
            'phone' => $conf->getPhone(),
            'email' => $conf->getEmail(),
        ];
        
        if (!is_null($nid)) {
            $arr['node'] = $this->doctrine->getRepository(Node::class)->find($nid);
        }
        
        foreach($regions as $r ) {
            $limit = $r->getCount();
            if ($limit > 0) {
                $order = 'DESC';
            } else {
                $order = 'ASC';
            }
            if ($limit !== 0) {
                $arr[$r->getLabel()] = $nodeRepo->findBy(['region' => $r], ['id' => $order], abs($limit));
            } else {
                $arr[$r->getLabel()] = $nodeRepo->findOneBy(['region' => $r]);
            }
        }
        
        return $arr;
    }
    
    public function findNodeByRegion(string $region, $locale, $limit = null, $offset = null)
    {
        $nodes = $this->doctrine->getRepository(Node::class)->findByRegion(['region' => $region], $locale, $limit, $offset);
        return $nodes;
    }
    
    public function findTagByLabel(string $label)
    {
        $tag = $this->doctrine->getRepository(Tag::class)->findOneBy(['label' => $label]);
        return $tag;
    }
   
    public function get($nid, $entity = Node::class)
    {
      return $this->doctrine->getRepository($entity)->find($nid);
    }
    
    public function find($nid, $entity = Node::class)
    {
      return $this->doctrine->getRepository($entity)->find($nid);
    }
    
    public function findNodeByTag(string $tag, $locale, $limit = null, $offset = null)
    {
        $nodes = $this->doctrine->getRepository(Node::class)->findByTag($tag, $locale, $limit, $offset);
        return $nodes;
    }
    
    public function findNodeByCategory(string $category, $locale, $limit = null, $offset = null)
    {
        $nodes = $this->doctrine->getRepository(Node::class)->findByCategory($category, $locale, $limit, $offset);
        return $nodes;
    }
    
    public function findNodeByRegionAndLocale($region_label, $locale)
    {
      $language = $this->findOneBy(['locale' => $locale], Language::class);
      $region = $this->findOneBy(['label' => $region_label], Region::class);
      $node = $this->findOneBy(['language' => $language, 'region' => $region]);
        
      return $node;
    }
    
    public function findConfByLocale($locale)
    {
      $language = $this->findOneBy(['locale' => $locale], Language::class);
      $conf = $this->findOneBy(['language' => $language], Conf::class);
        
      return $conf;
    }
    
    public function findBy($criteria, $entity = Node::class)
    {
      return $this->doctrine->getRepository($entity)->findBy($criteria);
    }
    
    public function findOneBy($criteria, $entity = Node::class)
    {
      return $this->doctrine->getRepository($entity)->findOneBy($criteria);
    }
    
    public function findAll($criteria, $entity = Node::class)
    {
      return $this->doctrine->getRepository($entity)->findAll($criteria);
    }
    
    public function getInfo(string $locale)
    {
      $conf = $this->findConfByLocale($locale);
      $beians = $this->findNodeByRegion('beian', $locale, 1);
      $wechats = $this->findNodeByRegion('footer-wechatqr', $locale, 1);
      $miniprogs = $this->findNodeByRegion('footer-miniprogqr', $locale, 1, null);
      $categories = $this->findAll([], Category::class);
      
      return [
        'conf' => $conf,
        'beian' => empty($beians) ? [] : $beians[0],
        'wechat' => empty($wechats) ? [] : $wechats[0],
        'miniprog' => empty($miniprogs) ? [] : $miniprogs[0],
        'categories' => $categories,
      ];
    }
    
    public function findNodeByCategoryAndRegion($cate_label, $region_label, $locale, int $limit = null, $offset = null)
    {
      return $this->doctrine->getRepository(Node::class)
                            ->findByCategoryAndRegion($cate_label, $region_label, $locale, $limit, $offset)
                        ;
    }
    
    public function findNodeByCategoryAndTag($cate_label, $tag_label, $locale, int $limit = null, $offset = null)
    {
      return $this->doctrine->getRepository(Node::class)
                            ->findByCategoryAndTag($cate_label, $tag_label, $locale, $limit, $offset)
                        ;
    }
}
