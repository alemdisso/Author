<?php

class Author_Collection_WorkMapper
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
        $query = $this->db->prepare('SELECT id FROM author_collection_works WHERE 1=1;');
        $query->execute();
        $resultPDO = $query->fetchAll();

        $result = array();
        foreach ($resultPDO as $row) {
            $result[] = $row['id'];
        }
        return $result;

    }

    public function insert(Author_Collection_Work $obj)
    {

        $query = $this->db->prepare("INSERT INTO author_collection_works (uri, title, prefix, description, summary, type)
            VALUES (:uri, :title, :prefix, :description, :summary, :type)");

        $query->bindValue(':uri', $obj->getUri(), PDO::PARAM_STR);
        $query->bindValue(':title', $obj->getTitle(true), PDO::PARAM_STR);
        $query->bindValue(':prefix', $obj->getPrefix(), PDO::PARAM_STR);
        $query->bindValue(':description', $obj->getDescription(), PDO::PARAM_STR);
        $query->bindValue(':summary', $obj->getSummary(), PDO::PARAM_STR);
        $query->bindValue(':type', $obj->getType(), PDO::PARAM_STR);

        $query->execute();

        $obj->setId((int)$this->db->lastInsertId());
        $this->identityMap[$obj] = $obj->getId();

    }

    public function update(Author_Collection_Work $obj)
    {
        if (!isset($this->identityMap[$obj])) {
            throw new Author_Collection_WorkMapperException('Object has no ID, cannot update.');
        }

        $query = $this->db->prepare("UPDATE author_collection_works SET uri = :uri, title = :title, prefix = :prefix, description = :description, summary = :summary, type = :type WHERE id = :id;");

        $query->bindValue(':uri', $obj->getUri(), PDO::PARAM_STR);
        $query->bindValue(':title', $obj->getTitle(true), PDO::PARAM_STR);
        $query->bindValue(':prefix', $obj->getPrefix(), PDO::PARAM_STR);
        $query->bindValue(':description', $obj->getDescription(), PDO::PARAM_STR);
        $query->bindValue(':summary', $obj->getSummary(), PDO::PARAM_STR);
        $query->bindValue(':type', $obj->getType(), PDO::PARAM_STR);
        $query->bindValue(':id', $this->identityMap[$obj], PDO::PARAM_STR);

        try {
            $query->execute();
        } catch (Exception $e) {
            throw new Author_Collection_WorkException("sql failed");
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

        $query = $this->db->prepare('SELECT uri, title, prefix, description, summary, type FROM author_collection_works WHERE id = :id;');
        $query->bindValue(':id', $id, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch();

        if (empty($result)) {
            throw new Author_Collection_WorkMapperException(sprintf('There is no work with id #%d.', $id));
        }
        $uri = $result['uri'];

        $obj = new Author_Collection_Work();
        $this->setAttributeValue($obj, $id, 'id');
        $this->setAttributeValue($obj, $result['title'], 'title');
        $this->setAttributeValue($obj, $result['prefix'], 'prefix');
        $this->setAttributeValue($obj, $result['uri'], 'uri');
        $this->setAttributeValue($obj, $result['description'], 'description');
        $this->setAttributeValue($obj, $result['summary'], 'summary');
        $this->setAttributeValue($obj, $result['type'], 'type');


        $this->identityMap[$obj] = $id;

        return $obj;

    }

    public function findByUri($uri)
    {
        $query = $this->db->prepare('SELECT id FROM author_collection_works WHERE uri = :uri LIMIT 1;');
        $query->bindValue(':uri', $uri, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch();

        if (empty($result)) {
            throw new Author_Collection_WorkMapperException(sprintf('There is no work with uri #%s.', $uri));
        }
        $id = $result['id'];

        if ($id > 0) {
            return $this->findById($id);
        } else {
            throw new Author_Collection_WorkMapperException(sprintf('The work with id #%s has id=0?!?.', $uri));
        }

    }


    public function delete(Author_Collection_Work $obj)
    {
        if (!isset($this->identityMap[$obj])) {
            throw new Author_Collection_WorkMapperException('Object has no ID, cannot delete.');
        }
        $query = $this->db->prepare('DELETE FROM author_collection_works WHERE id = :id;');
        $query->bindValue(':id', $this->identityMap[$obj], PDO::PARAM_STR);
        $query->execute();

        $query = $this->db->prepare('DELETE FROM author_collection_prizes WHERE work = :work;');
        $query->bindValue(':work', $this->identityMap[$obj], PDO::PARAM_STR);
        $query->execute();

        $query = $this->db->prepare('DELETE FROM author_collection_editions WHERE work = :work;');
        $query->bindValue(':work', $this->identityMap[$obj], PDO::PARAM_STR);
        $query->execute();

        unset($this->identityMap[$obj]);
    }


    private function setAttributeValue(Author_Collection_Work $a, $fieldValue, $attributeName)
    {
        $attribute = new ReflectionProperty($a, $attributeName);
        $attribute->setAccessible(TRUE);
        $attribute->setValue($a, $fieldValue);
    }


    public function getAllIdsOfType($type)
    {
        $query = $this->db->prepare('SELECT id FROM author_collection_works WHERE type=:type;');
        $query->bindValue(':type', $type, PDO::PARAM_STR);
        $query->execute();
        $resultPDO = $query->fetchAll();

        $result = array();
        foreach ($resultPDO as $row) {
            $result[] = $row['id'];
        }
        return $result;

    }




}