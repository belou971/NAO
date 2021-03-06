<?php

namespace TS\NaoBundle\Repository;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use TS\NaoBundle\Enum\StateEnum;

/**
 * ObservationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ObservationRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByName($specimen_name) {

        $subQueryBuilder = $this->_em->createQueryBuilder();
        $subQueryBuilder
            ->select('tax.cdNom')
            ->from('TSNaoBundle:TAXREF', 'tax')
            ->where('tax.lbNom = :name')
            ->orWhere('tax.nomVern = :name');

        $builder = $this->createQueryBuilder('obs');
        $builder
            ->select('obs','u.username')
            ->innerJoin('obs.user', 'u')
            ->where($builder->expr()->in('obs.taxref', $subQueryBuilder->getDQL()))
            ->setParameter('name', $specimen_name)
            ->andWhere('obs.state = :status')
            ->setParameter('status', StateEnum::VALIDATE);
        $observations = $builder->getQuery()->getArrayResult();

        return $observations;
    }

    public function findByCity($min_lat_lon, $max_lat_lon) {

        $builder = $this->createQueryBuilder('obs');
        $builder
            ->select('obs', 'u.username')
            ->innerJoin('obs.user', 'u')
            ->where('obs.longitude BETWEEN :minLon AND :maxLon')
            ->setParameter('minLon', $min_lat_lon[0])
            ->setParameter('maxLon', $max_lat_lon[0])
            ->andWhere('obs.latitude BETWEEN :minLat AND :maxLat')
            ->setParameter('minLat', $min_lat_lon[1])
            ->setParameter('maxLat', $max_lat_lon[1])
            ->andWhere('obs.state = :status')
            ->setParameter('status', StateEnum::VALIDATE);
        $observations = $builder->getQuery()->getArrayResult();

        return $observations;
    }

    public function findLastObservations($nbObservations) {
        $builder = $this->createQueryBuilder('obs');
        $builder->select('obs', 'i')
            ->leftJoin('obs.images','i')
            ->where('obs.state = :status')
            ->setParameter("status", StateEnum::VALIDATE)
            ->orderBy('obs.dtModification', 'DESC')
            ->setMaxResults($nbObservations);

        $query = $builder->getQuery();
        $statement = $query->getArrayResult();
        if (is_int($statement)) {
            return array();
        }

        return $statement;
    }

    public function getObservation($id)
    {
        $builder = $this->createQueryBuilder('obs');
        $builder
            ->select('obs', 'u.username', 'i', 'taxref.cdNom', 'taxref.url', 'taxref.lbNom', 'taxref.nomVern', 'r.label as rang', 'h.label as habitat', 's.label as status')
            ->leftJoin('obs.images', 'i')
            ->innerJoin('obs.user', 'u')
            ->innerJoin('obs.taxref', 'taxref')
            ->innerJoin('taxref.rang', 'r')
            ->innerJoin('taxref.habitat', 'h')
            ->innerJoin('taxref.fr', 's')
            ->where('obs.id = :id')
            ->setParameter('id', $id);

        $observation = $builder->getQuery()->getArrayResult();

        return $observation;
    }

    public function updateStatus($criteria) {
        $builder = $this->_em->createQueryBuilder();
        $builder->update('TSNaoBundle:Observation', 'obs')
                         ->set('obs.state', '?0')
                         ->set('obs.dtModification', '?1')
                         ->where('obs.id = ?2')
                         ->setParameter(0, $criteria['state'])
                         ->setParameter(1, new \DateTime())
                         ->setParameter(2, $criteria['id']);

        $toto = $builder->getQuery()->execute();
        return $toto;
    }
}
