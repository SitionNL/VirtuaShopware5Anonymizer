<?php
/**
 * Created by PhpStorm.
 * User: virtua
 * Date: 2019-04-30
 * Time: 15:47
 */

namespace ShopwareAnonymizer\Anonymizer\Bridge;


use Doctrine\ORM\QueryBuilder;
use Shopware\Components\Model\ModelEntity;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Model\ModelRepository;
use ShopwareAnonymizer\IntegerNet\Anonymizer\AnonymizableValue;
use ShopwareAnonymizer\IntegerNet\Anonymizer\Implementor\AnonymizableEntity;

abstract class AbstractBridgeEntity implements AnonymizableEntity
{
    const ROWS_PER_QUERY = 50000;

    /** @var string Entity::class, needs to be set in descendants */
    protected $entityClass;

    /** @var string[], needs to be set in descendants */
    protected $formattersByAttribute = [];

    /** @var string[], needs to be set in descendants */
    protected $uniqueAttributes = [];

    /** @var string, name of entity as translatable string, needs to be set in descendants */
    protected $entityName;

    /** @var string to seed fake data generator */
    protected $identifier;

    /** @var ModelEntity */
    protected $entity;

    /** @var ModelManager */
    protected $modelManager;

    /** @var int currentPage of collection for chunking */
    protected $currentPage = 0;

    /**
     * AbstractBridgeEntity constructor.
     * @param $identifier string
     */
    public function __construct($identifier)
    {
        $this->identifier = $identifier;
        $this->modelManager = Shopware()->Models();
        $this->entity = new $this->entityClass();
    }

    function clearInstance()
    {
        // TODO: Implement clearInstance() method.
    }

    function setRawData($data)
    {
//        $this->entity->find();
        dump($this->entity);
        exit;
    }

    function updateValues()
    {
        // TODO: Implement updateValues() method.
    }

    /**
     * @return string
     */
    function getIdentifier()
    {
        return $this->identifier;
    }

    function getValues()
    {
        // TODO: Implement getValues() method.
    }

    /**
     * @return string
     */
    function getEntityName()
    {
        return $this->entityName;
    }


    public function getCollectionIterator()
    {
        $collection = $this->getDataChunk();
        $iterationOffset = $this->currentPage * self::ROWS_PER_QUERY;
        $size = $this->getEntitySize();
        if ($size <= $iterationOffset) {
            return null;
        }
        $this->currentPage++;

        $iterator = new Iterator($collection);
        $iterator->setSize($size);
        $iterator->setIterationOffset($iterationOffset);

//        dump($iterator);

        return $iterator;
    }
// todo tutaj robie, do sprawdzenia czy tak podziala fajnie
//    private function getWholeEntityChunk()
//    {
//        return $this->modelManager->getRepository($this->entityClass)
//            ->findBy([], [], self::ROWS_PER_QUERY, self::ROWS_PER_QUERY * $this->currentPage);
//    }

    /**
     * @return array
     */
    private function getDataChunk()
    {
        $alias = 'ent';
        $data = $this->modelManager->createQueryBuilder()->select($this->createSelectArray($alias))
            ->from($this->entityClass, $alias)
            ->setMaxResults(self::ROWS_PER_QUERY)
            ->setFirstResult(self::ROWS_PER_QUERY * $this->currentPage)
            ->getQuery()
            ->execute();

        return $data ? $data : [];
    }

    /**
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function getEntitySize()
    {
        $alias = 'ent';
        return (int) $this->modelManager->createQueryBuilder()
            ->select('count(' . $alias . '.id)')
            ->from($this->entityClass, $alias)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param $alias
     * @return string[]
     */
    private function createSelectArray($alias)
    {
        $attributes = array_keys($this->formattersByAttribute);
        array_walk($attributes, function (&$attribute) use ($alias) {
            $attribute = $alias . '.' . $attribute;
        });
        array_unshift($attributes, $alias . '.id');

        return $attributes;
    }
}