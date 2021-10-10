<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use App\Security\ItemVoter;
use App\Service\ItemService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ItemController extends AbstractController
{
    /**
     * @Route("/items", name="item_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list(Request $request, ItemRepository $repository, ItemService $service): JsonResponse
    {
        $page = (int) $request->get('page');
        $items = $repository->findItemsByUserAndPage($this->getUser(), $page > 0 ? $page : 1);

        return $this->json($service->getDtos($items));
    }

    /**
     * @Route("/items", name="item_create", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function create(
        Request $request, 
        ItemService $itemService, 
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $data = $request->get('data');

        if (null === $data) {
            return $this->json(['error' => 'No data parameter'], Response::HTTP_BAD_REQUEST);
        }

        $item = $itemService->create($this->getUser(), $data);
        $entityManager->persist($item);
        $entityManager->flush();
        
        return $this->json($itemService->getDto($item));
    }

    /**
     * @Route("/items/{item}", name="items_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Item $item, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted(ItemVoter::ACCESS, $item);
        $entityManager->remove($item);
        $entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
    
    /**
     * @Route("/items/{item}", name="items_update", methods={"PUT"})
     * @IsGranted("ROLE_USER")
     */
    public function update(
        Request $request, 
        Item $item, 
        EntityManagerInterface $entityManager,
        ItemService $itemService
    ): JsonResponse 
    {
        $this->denyAccessUnlessGranted(ItemVoter::ACCESS, $item);
        $item->setData($request->get('data'));
        $entityManager->flush();
        
        return $this->json($itemService->getDto($item));
    }
}
