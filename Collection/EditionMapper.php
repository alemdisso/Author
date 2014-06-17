<?php

class Author_Collection_EditionMapper
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
        $query = $this->db->prepare('SELECT id FROM author_collection_editions WHERE 1=1;');
        $query->execute();
        $resultPDO = $query->fetchAll();

        $result = array();
        foreach ($resultPDO as $row) {
            $result[] = $row['id'];
        }
        return $result;

    }

    public function insert(Author_Collection_Edition $obj)
    {

        $query = $this->db->prepare("INSERT INTO author_collection_editions (work, title, prefix, uri, editor, country, serie, pages, cover, isbn, illustrator, cover_designer)
            VALUES (:work, :title, :prefix, :uri, :editor, :country, :serie, :pages, :cover, :isbn, :illustrator, :cover_designer)");

        $query->bindValue(':work', $obj->getWork(), PDO::PARAM_STR);
        $query->bindValue(':title', $obj->getTitle(true), PDO::PARAM_STR);
        $query->bindValue(':prefix', $obj->getPrefix(), PDO::PARAM_STR);
        $query->bindValue(':uri', $obj->getUri(), PDO::PARAM_STR);
        $query->bindValue(':editor', $obj->getEditor(), PDO::PARAM_STR);
        $query->bindValue(':country', $obj->getCountry(), PDO::PARAM_STR);
        $query->bindValue(':serie', $obj->getSerie(), PDO::PARAM_STR);
        $query->bindValue(':pages', $obj->getPages(), PDO::PARAM_STR);
        $query->bindValue(':cover', $obj->getCover(), PDO::PARAM_STR);
        $query->bindValue(':isbn', $obj->getIsbn(), PDO::PARAM_STR);
        $query->bindValue(':illustrator', $obj->getIllustrator(), PDO::PARAM_STR);
        $query->bindValue(':cover_designer', $obj->getCoverDesigner(), PDO::PARAM_STR);

        $query->execute();

        $obj->SetId((int)$this->db->lastInsertId());
        $this->identityMap[$obj] = $obj->getId();

    }

    public function update(Author_Collection_Edition $obj)
    {
        if (!isset($this->identityMap[$obj])) {
            throw new Author_Collection_EditionMapperException('Object has no ID, cannot update.');
        }

        $query = $this->db->prepare("UPDATE author_collection_editions SET work = :work, title = :title, prefix = :prefix
             , uri = :uri, editor = :editor, country = :country, serie = :serie, pages = :pages
             , cover = :cover, isbn = :isbn, illustrator = :illustrator, cover_designer = :cover_designer
            WHERE id = :id;");

        $query->bindValue(':work', $obj->getWork(), PDO::PARAM_STR);
        $query->bindValue(':title', $obj->getTitle(true), PDO::PARAM_STR);
        $query->bindValue(':prefix', $obj->getPrefix(), PDO::PARAM_STR);
        $query->bindValue(':uri', $obj->getUri(), PDO::PARAM_STR);
        $query->bindValue(':editor', $obj->getEditor(), PDO::PARAM_STR);
        $query->bindValue(':country', $obj->getCountry(), PDO::PARAM_STR);
        $query->bindValue(':serie', $obj->getSerie(), PDO::PARAM_STR);
        $query->bindValue(':pages', $obj->getPages(), PDO::PARAM_STR);
        $query->bindValue(':cover', $obj->getCover(), PDO::PARAM_STR);
        $query->bindValue(':isbn', $obj->getIsbn(), PDO::PARAM_STR);
        $query->bindValue(':illustrator', $obj->getIllustrator(), PDO::PARAM_STR);
        $query->bindValue(':cover_designer', $obj->getCoverDesigner(), PDO::PARAM_STR);
        $query->bindValue(':id', $obj->getId(), PDO::PARAM_STR);

        try {
            $query->execute();
        } catch (Exception $e) {
            throw new Author_Collection_EditionException("sql failed");
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

        $query = $this->db->prepare('SELECT work, title, prefix, uri, editor, country, serie, pages, cover, isbn, illustrator, cover_designer FROM author_collection_editions WHERE id = :id;');
        $query->bindValue(':id', $id, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch();
        if (empty($result)) {
            throw new Author_Collection_EditionMapperException(sprintf('There is no edition with id #%d.', $id));
        }
        $work = $result['work'];
        $obj = new Author_Collection_Edition($result['work'], $result['editor']);
        $this->setAttributeValue($obj, $id, 'id');
        $this->setAttributeValue($obj, $result['serie'], 'serie');
        $this->setAttributeValue($obj, $result['title'], 'title');
        $this->setAttributeValue($obj, $result['prefix'], 'prefix');
        $this->setAttributeValue($obj, $result['uri'], 'uri');
        $this->setAttributeValue($obj, $result['country'], 'country');
        $this->setAttributeValue($obj, $result['pages'], 'pages');
        $this->setAttributeValue($obj, $result['cover'], 'cover');
        $this->setAttributeValue($obj, $result['isbn'], 'isbn');
        $this->setAttributeValue($obj, $result['illustrator'], 'illustrator');
        $this->setAttributeValue($obj, $result['cover_designer'], 'coverDesigner');

        $this->identityMap[$obj] = $id;

        return $obj;

    }

    public function findByWork($work)
    {
        $query = $this->db->prepare('SELECT id FROM author_collection_editions WHERE work = :work LIMIT 1;');
        $query->bindValue(':work', $work, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch();

        if (empty($result)) {
            return null;
        }
        $id = $result['id'];

        if ($id > 0) {
            return $this->findById($id);
        } else {
            throw new Author_Collection_EditionMapperException(sprintf('The edition with id #%s has id=0?!?.', $work));
        }
    }

    public function findByTitle($title)
    {
        $query = $this->db->prepare('SELECT e.id FROM author_collection_editions e
                                     WHERE e.title=:title LIMIT 1;');
        $query->bindValue(':title', $title, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch();
        if (empty($result)) {
            throw new Author_Collection_WorkMapperException(sprintf('There is no edition with title #%s.', $title));
        }
        $id = $result['id'];

        if ($id > 0) {
            return $this->findById($id);
        } else {
            throw new Author_Collection_WorkMapperException(sprintf('The work with id #%s has id=0?!?.', $title));
        }

    }


    public function findByUri($uri)
    {
        $query = $this->db->prepare('SELECT e.id FROM author_collection_editions e
                                     WHERE e.uri=:uri LIMIT 1;');
        $query->bindValue(':uri', $uri, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch();

        if (empty($result)) {
            throw new Author_Collection_WorkMapperException(sprintf('There is no edition with uri #%s.', $uri));
        }
        $id = $result['id'];

        if ($id > 0) {
            return $this->findById($id);
        } else {
            throw new Author_Collection_WorkMapperException(sprintf('The work with id #%s has id=0?!?.', $uri));
        }

    }


    public function delete(Author_Collection_Edition $obj)
    {
        if (!isset($this->identityMap[$obj])) {
            throw new Author_Collection_EditionMapperException('Object has no ID, cannot delete.');
        }

        $query = $this->db->prepare('DELETE FROM author_collection_prizes WHERE id = :id;');
        $query->bindValue(':id', $this->identityMap[$obj], PDO::PARAM_STR);
        $query->execute();



        unset($this->identityMap[$obj]);
    }


   public function getAllEditionsAlphabeticallyOrdered()
    {
        $query = $this->db->prepare('SELECT id, editor FROM author_collection_editions WHERE 1 =1 ORDER BY editor;');
        $query->execute();
        $resultPDO = $query->fetchAll();

        $data = array();
        foreach ($resultPDO as $row) {
            //$data[$row['id']] = $row['editor'];
            if (!is_null($row['id'])) {
                $data[$row['id']] = $row['id'];
            }
        }
        return $data;

    }

    private function setAttributeValue(Author_Collection_Edition $a, $fieldValue, $attributeEditor)
    {
        $attribute = new ReflectionProperty($a, $attributeEditor);
        $attribute->setAccessible(TRUE);
        $attribute->setValue($a, $fieldValue);
    }



    public function getAllIdsOfType($type)
    {
        $query = $this->db->prepare('SELECT e.id FROM author_collection_works w
                                     LEFT JOIN author_collection_editions e ON w.id = e.work
                                     WHERE w.type=:type;');
        $query->bindValue(':type', $type, PDO::PARAM_STR);
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

    public function getAllEditionsOfWork($work)
    {
        $query = $this->db->prepare('SELECT e.id FROM  author_collection_editions e
                                     WHERE e.work=:work;');
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


    public function getAllEditionsOfSerieByUri($serie)
    {
        $query = $this->db->prepare('SELECT e.id FROM author_collection_series s
                                     LEFT JOIN author_collection_editions e ON s.id = e.serie
                                     WHERE s.uri = :serie ORDER BY e.title;');
        $query->bindValue(':serie', $serie, PDO::PARAM_STR);
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

    public function getAllEditionsOfSerieById($id)
    {
        $query = $this->db->prepare('SELECT e.id FROM author_collection_series s
                                     LEFT JOIN author_collection_editions e ON s.id = e.serie
                                     WHERE s.id = :id ORDER BY e.title;');
        $query->bindValue(':id', $id, PDO::PARAM_STR);
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

   public function getSomeEditionFrom($serie)
    {
        $query = $this->db->prepare('SELECT id FROM author_collection_editions WHERE serie = :serie LIMIT 1;');
        $query->bindValue(':serie', $serie, PDO::PARAM_STR);
        $query->execute();
        $resultPDO = $query->fetchAll();

        $data = array();
        foreach ($resultPDO as $row) {
            $data[] = $row['id'];
       }
        return $data;

    }

    public function getAllEditionsOfTypeAlphabeticallyOrdered($type)
    {
            $query = $this->db->prepare('SELECT e.id, e.title FROM author_collection_works w
                                        LEFT JOIN author_collection_editions e ON w.id = e.work
                                        WHERE w.type=:type ORDER BY e.title;');
        $query->bindValue(':type', $type, PDO::PARAM_STR);
        $query->execute();
        $resultPDO = $query->fetchAll();

        $data = array();
        foreach ($resultPDO as $row) {
            $data[] = $row['id'];
        }
        return $data;

    }


}