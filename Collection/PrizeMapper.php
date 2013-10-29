<?php

class Author_Collection_PrizeMapper
{
    protected $db;
    protected $identityMap;

    function __construct()
    {
        $this->db = Zend_Registry::get('db');
        $this->identityMap = new SplObjectStorage;
    }

    public function getAllIds()
    {
        $query = $this->db->prepare('SELECT id FROM author_collection_prizes WHERE 1=1;');
        $query->execute();
        $resultPDO = $query->fetchAll();

        $result = array();
        foreach ($resultPDO as $row) {
            $result[] = $row['id'];
        }
        return $result;
    }

    public function insert(Author_Collection_Prize $obj)
    {
        $query = $this->db->prepare("INSERT INTO author_collection_prizes (work, prize_name, institution_name, category_name, year)
            VALUES (:work, :prize_name, :institution_name, :category_name, :year)");

        $query->bindValue(':work', $obj->getWork(), PDO::PARAM_STR);
        $query->bindValue(':prize_name', $obj->getPrizeName(), PDO::PARAM_STR);
        $query->bindValue(':institution_name', $obj->getInstitutionName(), PDO::PARAM_STR);
        $query->bindValue(':category_name', $obj->getCategoryName(), PDO::PARAM_STR);
        $query->bindValue(':year', $obj->getYear(), PDO::PARAM_STR);

        $query->execute();

        $obj->SetId((int)$this->db->lastInsertId());
        $this->identityMap[$obj] = $obj->getId();
    }

    public function update(Author_Collection_Prize $obj)
    {
        if (!isset($this->identityMap[$obj])) {
            throw new Author_Collection_PrizeMapperException('Object has no ID, cannot update.');
        }

        $query = $this->db->prepare("UPDATE author_collection_prizes SET work = :work, prize_name = :prize_name
            , institution_name = :institution_name, category_name = :category_name, year = :year
            WHERE id = :id;");

        $query->bindValue(':work', $obj->getWork(), PDO::PARAM_STR);
        $query->bindValue(':prize_name', $obj->getPrizeName(), PDO::PARAM_STR);
        $query->bindValue(':institution_name', $obj->getInstitutionName(), PDO::PARAM_STR);
        $query->bindValue(':category_name', $obj->getCategoryName(), PDO::PARAM_STR);
        $query->bindValue(':year', $obj->getYear(), PDO::PARAM_STR);
        $query->bindValue(':id', $obj->getId(), PDO::PARAM_STR);

        try {
            $query->execute();
        } catch (Exception $e) {
            throw new Author_Collection_PrizeException("sql failed");
        }
    }

    public function findById($id)
    {
        $this->identityMap->rewind();
        while ($this->identityMap->valid()) {
            if ($this->identityMap->getInfo() == $id) {
                return $this->identityMap->current();
            }
            $this->identityMap->next();
        }

        $query = $this->db->prepare('SELECT work, prize_name, institution_name, category_name, year FROM author_collection_prizes WHERE id = :id;');
        $query->bindValue(':id', $id, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch();
        if (empty($result)) {
            throw new Author_Collection_PrizeMapperException(sprintf('There is no prize with id #%d.', $id));
        }
        $work = $result['work'];
        $obj = new Author_Collection_Prize($result['work']);
        $this->setAttributeValue($obj, $id, 'id');
        $this->setAttributeValue($obj, $result['prize_name'], 'prizeName');
        $this->setAttributeValue($obj, $result['institution_name'], 'institutionName');
        $this->setAttributeValue($obj, $result['category_name'], 'categoryName');
        $this->setAttributeValue($obj, $result['year'], 'year');

        $this->identityMap[$obj] = $id;

        return $obj;
    }

    public function delete(Author_Collection_Prize $obj)
    {
        if (!isset($this->identityMap[$obj])) {
            throw new Author_Collection_PrizeMapperException('Object has no ID, cannot delete.');
        }
        $query = $this->db->prepare('DELETE FROM author_collection_prizes WHERE id = :id;');
        $query->bindValue(':id', $this->identityMap[$obj], PDO::PARAM_STR);
        $query->execute();
        unset($this->identityMap[$obj]);
    }


    private function setAttributeValue(Author_Collection_Prize $a, $fieldValue, $attributeEditor)
    {
        $attribute = new ReflectionProperty($a, $attributeEditor);
        $attribute->setAccessible(TRUE);
        $attribute->setValue($a, $fieldValue);
    }

    public function getAllPrizesOfWork($work)
    {
        $query = $this->db->prepare('SELECT id FROM author_collection_prizes WHERE work=:work ORDER BY year, prize_name;');
        $query->bindValue(':work', $work, PDO::PARAM_STR);
        $query->execute();
        $resultPDO = $query->fetchAll();

        $result = array();
        foreach ($resultPDO as $row) {
            if (!is_null($row['id'])) {
                $result[] = $row['id'];
            }
        }
        return $result;
    }

}