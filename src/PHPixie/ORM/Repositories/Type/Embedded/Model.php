    <?php

namespace PHPixie\ORM\Model\Repository\Embedded;

abstract class Model extends \PHPixie\ORM\Model
{
    protected $owner;
    protected $ownerPropertyName;

    public function setOwnerProperty($owner, $ownerPropertyName)
    {
        $this->owner = $owner;
        $this->ownerPropertyName = $ownerPropertyName;
    }

    public function owner()
    {
        return $this->owner;
    }

    public function ownerPropertyName()
    {
        return $this->ownerPropertyName;
    }
}
