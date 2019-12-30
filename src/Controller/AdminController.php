<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\Section;
use App\Utils\Util;
use Doctrine\ORM\EntityManagerInterface;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 * @author Alcides Rodríguez <alcdesrh@gmail.com>
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin")
     */
    public function index()
    {
        $route = $this->get('router')->getContext()->getPathInfo();
        $route = str_getcsv($route, '/');
        unset($route[0]);
        if (empty($route[count($route)])) {
            unset($route[count($route)]);
        }
        $route = Util::array_string_values_nospace(array_merge($route));

        return $this->render('admin/index.html.twig', ['route_reload' => $route]);
    }

    /**
     * @Route(
     *     name="save_page",
     *     path="/save_page",
     *     methods={"POST"}
     * )
     */
    public function savePage(EntityManagerInterface $entityManager)
    {
        $data = Util::decodeBody();
        $page = new Page();
        $page->setName($data['name']);
        $page->setSlug($data['slug'] ?? null);
        $cont = 1;
        if (!empty($data['sections'])) {
            foreach ($data['sections'] as $value) {
                $section = new Section();
                $section->setContent($value['content']);
                $section->setTitle($value['title']);
                $section->setPage($page);
                $section->setSlug("section".$cont++);
                $entityManager->persist($section);
            }
        }
        $entityManager->persist($page);
        $entityManager->flush();

        return new JsonResponse(['saved']);
    }

    /**
     * @Route(
     *     name="edit_page",
     *     path="/edit_page",
     *     methods={"POST"}
     * )
     */
    public function editPage(EntityManagerInterface $entityManager)
    {
        $data = Util::decodeBody();
        $page = $entityManager->getRepository('App:Page')->find($data['id']);
        $page->setName($data['name']);
        $page->setSlug($data['slug'] ?? null);
        $sections = $page->getSections();
        if (!empty($data['sections'])) {
            foreach ($data['sections'] as $value) {
                if (isset($value['id'])) {
                    $section = $entityManager->getRepository('App:Section')->find($value['id']);
                    foreach ($sections as $item) {
                        if ($item->getId() == $value['id']) {
                            $sections->removeElement($item);
                            break;
                        }
                    }
                } else {
                    $section = new Section();
                }
                $section->setContent($value['content']);
                $section->setTitle($value['title']);
                $section->setPage($page);
                $entityManager->persist($section);
            }
        }
        foreach ($sections as $item) {
            $entityManager->remove($item);
        }
        $entityManager->persist($page);
        $entityManager->flush();

        $entityManager->clear();
        $page = $entityManager->getRepository('App:Page')->find($data['id']);
        $sections = $page->getSections()->toArray();
        $cont = 1;

        foreach ($sections as $section) {
            $section->setSlug("section".$cont++);
            $entityManager->persist($section);
        }
        $entityManager->flush();

        return new JsonResponse(['saved']);
    }

    /**
     * @Route(
     *     name="statistics",
     *     path="/statistics",
     *     methods={"GET"}
     * )
     */
    public function statistics(EntityManagerInterface $entityManager)
    {
        $return['reservations'] = $entityManager->createQuery(
            'SELECT COUNT(r.id) FROM App:Reservation r'
        )->getSingleScalarResult();
        $return['reservationPendent'] = $entityManager->createQuery(
            'SELECT COUNT(r.id) FROM App:Reservation r WHERE r.dateCarIn > :date'
        )->setParameter('date', new \DateTime())->getSingleScalarResult();
        $return['cash'] = $entityManager->createQuery(
            'SELECT COUNT(r.id) FROM App:Reservation r WHERE r.paymentType = 1'
        )->getSingleScalarResult();
        $return['visa'] = $entityManager->createQuery(
            'SELECT COUNT(r.id) FROM App:Reservation r WHERE r.paymentType = 2'
        )->getSingleScalarResult();
        $return['mastercard'] = $entityManager->createQuery(
            'SELECT COUNT(r.id) FROM App:Reservation r WHERE r.paymentType = 3'
        )->getSingleScalarResult();
        $return['postFinanceCard'] = $entityManager->createQuery(
            'SELECT COUNT(r.id) FROM App:Reservation r WHERE r.paymentType = 4'
        )->getSingleScalarResult();
        $return['postFinanceEfinance'] = $entityManager->createQuery(
            'SELECT COUNT(r.id) FROM App:Reservation r WHERE r.paymentType = 5'
        )->getSingleScalarResult();

        return new JsonResponse($return);

    }

    /**
     * @Route(
     *     name="reservation-statistics",
     *     path="/reservation-statistics",
     *     methods={"GET"}
     * )
     */
    public function reservationStatistics(EntityManagerInterface $entityManager, Request $request)
    {
        $date = $request->get('dates');
        $query = $request->get('in')?
            'SELECT r FROM App:Reservation r WHERE r.dateCarIn >= :start AND r.dateCarIn < :end ORDER BY r.dateCarIn ASC':
            'SELECT r FROM App:Reservation r WHERE r.dateCarOut >= :start AND r.dateCarOut < :end ORDER BY r.dateCarOut ASC';
        $reservations = $entityManager->createQuery($query)->setParameters(
                ['start' => new \DateTime($date['start']), 'end' => new \DateTime($date['end'])]
            )->getResult();

        return new JsonResponse($reservations ?? null);

    }

    /**
     * @Route(
     *     name="pdf-report",
     *     path="/pdf-report",
     * )
     */
    public function pdfReport(EntityManagerInterface $entityManager)
    {

        $date = $date = new \DateTime("2019-07-15");//new \DateTime(Util::decodeBody());
        $date2 = clone $date;
        $date->setTime(0, 0, 0);
        $date2->setTime(23, 59, 59);
        $reservations = $entityManager->getRepository('App:Reservation')->findByDay($date, $date2);

        $mpdf = new Mpdf(['tempDir' => 'pdf/temp/']);
// return $this->render('pdf-resumen.html.twig', ['reservations' => $reservations,  'date' => $date->format('d/m/Y')]);
        $mpdf->WriteHTML($this->renderView('pdf-resumen.html.twig', ['reservations' => $reservations,  'date' => $date->format('d/m/Y')]));
        // return  $mpdf->Output();
        $mpdf->Output('pdf/reservaciones.pdf', Destination::FILE);
        return new JsonResponse('pdf/reservaciones.pdf');
    }
}
