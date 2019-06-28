<?php

namespace App\Controller\Traits;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

trait FormCacheTrait
{
    /**
     * Cache form
     *
     * @param $nameItem
     * @param $entity
     * @param $form
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function cacheForm($nameItem, $entity, $form)
    {
        $cache = new FilesystemAdapter('', 120);
        $item = $cache->getItem($nameItem);
        if (!$item->isHit()) {
            // Set form into item
            $item->set($this->render($entity . '/form.html.twig', ['form' => $form]));
            $cache->save($item);
        }
        if ($cache->hasItem($nameItem)) {
            // Remove header HTTP
            $view = explode('GMT', $item->get());
            // Render Form in Create template
            return $this->render($entity . '/create.html.twig', ['form' => trim($view[1])]);
        }
    }
}
