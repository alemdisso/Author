<?php

class Author_Collection_TaxonomyMapper extends Moxca_Taxonomy_TaxonomyMapper
{

    protected $db;
    protected $identityMap;

    function __construct()
    {
        $this->db = Zend_Registry::get('db');
        $this->identityMap = new SplObjectStorage;
    }

    public function insertTheme($termId)
    {

        $query = $this->db->prepare("INSERT INTO moxca_terms_taxonomy (term_id, taxonomy, count)
            VALUES (:termId, 'theme', 0)");

        $query->bindValue(':termId', $termId, PDO::PARAM_INT);

        $query->execute();

        return (int)$this->db->lastInsertId();


    }


    public function existsTheme($termId)
    {
        $query = $this->db->prepare("SELECT id FROM moxca_terms_taxonomy WHERE term_id = :termId AND taxonomy = 'theme';");

        $query->bindValue(':termId', $termId, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch();

        if (!empty($result)) {
            //$row = current($result);
            return $result['id'];
        } else {
            return false;
        }
    }

    public function updateWorkThemeRelationShip(Author_Collection_Work $obj)
    {

        $newThemeTermId = $obj->getTheme();
        $workId = $obj->getId();
        $formerThemeTermId = $this->workHasTheme($workId);
        if (!$formerThemeTermId) {
            if ($newThemeTermId > 0) {
                $this->insertRelationship($workId, $newThemeTermId);
            }
        } else {
            if ($newThemeTermId != $formerThemeTermId) {
                $formerTermTaxonomy = $this->existsTheme($formerThemeTermId);
                $newTermTaxonomy = $this->createThemeIfNeeded($newThemeTermId);

                $query = $this->db->prepare("UPDATE moxca_terms_relationships SET term_taxonomy = :newTheme"
                        . " WHERE object = :workId AND term_taxonomy = :formerTheme;");

                $query->bindValue(':workId', $workId, PDO::PARAM_STR);
                $query->bindValue(':newTheme', $newTermTaxonomy, PDO::PARAM_STR);
                $query->bindValue(':formerTheme', $formerTermTaxonomy, PDO::PARAM_STR);
                $query->execute();


                $query = $this->db->prepare("UPDATE moxca_terms_taxonomy SET count = count + 1
                    WHERE id = :termTaxonomy;");
                $query->bindValue(':termTaxonomy', $newTermTaxonomy, PDO::PARAM_STR);
                $query->execute();

                $query = $this->db->prepare("UPDATE moxca_terms_taxonomy SET count = count - 1
                    WHERE id = :termTaxonomy;");
                $query->bindValue(':termTaxonomy', $formerTermTaxonomy, PDO::PARAM_STR);

                try {
                    $query->execute();
                } catch (Exception $e) {
                    $query = $this->db->prepare("UPDATE moxca_terms_taxonomy SET count = 0
                        WHERE id = :termTaxonomy;");
                    $query->bindValue(':termTaxonomy', $formerTermTaxonomy, PDO::PARAM_STR);
                }
            }
        }

    }

    public function getAllThemesAlphabeticallyOrdered()
    {
        $query = $this->db->prepare('SELECT t.id, t.term
                FROM moxca_terms t
                LEFT JOIN moxca_terms_taxonomy tx ON t.id = tx.term_id
                WHERE tx.taxonomy =  \'theme\' ORDER BY t.term');
        $query->execute();
        $resultPDO = $query->fetchAll();
        $data = array();
        foreach ($resultPDO as $row) {
            $data[$row['id']] = $row['term'];
        }
        return $data;

    }



    public function workHasTheme($workId)
    {
        $query = $this->db->prepare('SELECT tx.term_id
                FROM moxca_terms_relationships tr
                LEFT JOIN moxca_terms_taxonomy tx ON tr.term_taxonomy = tx.id
                WHERE tr.object = :workId
                AND tx.taxonomy =  \'theme\'');

        $query->bindValue(':workId', $workId, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch();

        if (!empty($result)) {
            return $result['term_id'];
        } else {
            return false;
        }
    }


    private function createThemeIfNeeded($termId)
    {
        $existsThemeWithTerm = $this->existsTheme($termId);
        if (!$existsThemeWithTerm) {
            $existsThemeWithTerm = $this->insertTheme($termId);
        }

        return $existsThemeWithTerm;

    }

    public function findThemeByWorkId($id)
    {

        $query = $this->db->prepare('SELECT tx.term_id
                FROM moxca_terms_relationships tr
                LEFT JOIN moxca_terms_taxonomy tx ON tr.term_taxonomy = tx.id
                WHERE tr.object = :id
                AND tx.taxonomy =  \'theme\'');
        $query->bindValue(':id', $id, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch();

        if (empty($result)) {
            $termId = null;
        } else {
            $termId = $result['term_id'];
        }

        return $termId;


    }

    public function findTaxonomyByTheme($id)
    {

        $query = $this->db->prepare('SELECT id FROM moxca_terms_taxonomy tx
                WHERE tx.term_id = :id
                AND tx.taxonomy =  \'theme\'');
        $query->bindValue(':id', $id, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch();

        if (empty($result)) {
            $taxonomyId = null;
        } else {
            $taxonomyId = $result['id'];
        }

        return $taxonomyId;


    }





}