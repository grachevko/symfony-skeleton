<?php

namespace AppBundle\Doctrine;

use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
class BundleNamingStrategy extends UnderscoreNamingStrategy
{
    public function classToTableName($className)
    {
        $underscored = parent::classToTableName($className);

        if (0 === strpos($className, 'AppBundle')) {
            return $underscored;
        }

        if (strpos($className, 'Entity') && $pos = strpos($className, 'Bundle')) {
            $underscored = strtolower(substr($className, 0, $pos)).'_'.$underscored;
        }

        return $underscored;
    }
}
