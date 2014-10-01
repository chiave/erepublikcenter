<?php

namespace Chiave\NAZWABUNDLA\Document;

use Chiave\CoreBundle\Document\Base;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

// NAZWABUNDLA -> podaj nazwę bundla w którym jesteśmy
// NAZWAKOLEKCJI -> podaj nazwę tabeli w bazie danych
// NAZWAENCJI -> podaj nazwę encji
/**
 * NAZWAENCJI
 *
 * @MongoDB\Document(collection="NAZWAKOLEKCJI")
 * @MongoDB\HasLifecycleCallbacks
 */
class NAZWAENCJI extends Base {

    /**
     * @MongoDB\Field(type="string")
     */
    protected $name;

    public function __construct() {
        parent::__construct();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Get class name
     *
     * @return string $className
     */
    public function getClassName() {
        $className = get_class($this);
        return $className;
    }

}
