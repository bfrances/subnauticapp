<?php

namespace App\Controller;

use App\Entity\NauticBase;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class NauticBaseController extends AbstractController
{
    const PAGINATION_NB_BY_PAGE = 20;

    public function index()
    {
        return $this->redirectToRoute('list_nauticbases');
    }

    public function list(Request $request): Response
    {
        $page = 1;
        if (null !== $request->get('page')) {
            $page = $request->get('page');
        }

        try {
            $nauticBases = $this->getDoctrine()->
                                                getRepository(NauticBase::class)->
                                                findByPagination($page, NauticBaseController::PAGINATION_NB_BY_PAGE);
            $allNauticBases = [];
            foreach ($nauticBases as $nauticBase) {
                array_push($allNauticBases, $nauticBase->jsonSerializeMin());
            }

            $pagination = [
                'page' => $page,
                'nbPages' => ceil(count($nauticBases) / NauticBaseController::PAGINATION_NB_BY_PAGE),
            ];

            return $this->json([
                'nauticBases' => $allNauticBases,
                'pagination' => $pagination,
            ]);
        } catch (Exception $e) {
            throw new HttpException(404, $this->json(['message' => $e->getMessage()]));
        }
    }

    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent());
        if (!isset($data->name) || !isset($data->address) ||
                !isset($data->city) || !isset($data->postalCode)) {
            throw new HttpException(
                422,
                $this->json([
                    'message' => 'Missiong arguments for create the new nautic base',
                ])
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $nauticBase = new NauticBase();
        $nauticBase->setName($data->name);
        $nauticBase->setDescription(isset($data->description) ? $data->description : '');
        $nauticBase->setAddress($data->address);
        $nauticBase->setCity($data->city);
        $nauticBase->setPostalCode($data->postalCode);
        $entityManager->persist($nauticBase);
        $entityManager->flush();

        return $this->json($nauticBase->jsonSerialize());
    }

    public function read(int $id)
    {
        $nauticBase = $this->getDoctrine()->getRepository(NauticBase::class)->find($id);
        if (!$nauticBase) {
            throw new HttpException(404, $this->json(['message' => 'Nautic base not found !']));
        }

        return $this->json($nauticBase->jsonSerialize());
    }

    public function update(Request $request, int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $nauticBase = $entityManager->getRepository(NauticBase::class)->find($id);
        if (!$nauticBase) {
            throw new HttpException(404, $this->json(['message' => 'Nautic base not found !']));
        }
        $data = json_decode($request->getContent());
        if (isset($data->name)) {
            $nauticBase->setName($data->name);
        }
        if (isset($data->description)) {
            $nauticBase->setDescription($data->description);
        }
        if (isset($data->address)) {
            $nauticBase->setAddress($data->address);
        }
        if (isset($data->city)) {
            $nauticBase->setCity($data->city);
        }
        if (isset($data->postalCode)) {
            $nauticBase->setPostalCode($data->postalCode);
        }
        $entityManager->flush();

        return $this->json($nauticBase->jsonSerialize());
    }

    public function delete(int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $nauticBase = $this->getDoctrine()->getRepository(NauticBase::class)->find($id);
        $entityManager->remove($nauticBase);
        $entityManager->flush();

        return $this->json([
            'result' => 'success',
            'message' => 'nautic base is deleted',
        ]);
    }
}
