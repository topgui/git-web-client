<?php
// src/VersionControl/GitlabIssueBundle/Entity/User.php
namespace VersionControl\GitlabIssueBundle\Entity;

use VersionControl\GitControlBundle\Entity\Issues\IssueUserInterface;

/**
 */
class User implements IssueUserInterface
{
    
    /**
     *
     * @var integer
     */
    protected $id;
    
    /**
     *
     * @var string
     */
    protected $name;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }


}
