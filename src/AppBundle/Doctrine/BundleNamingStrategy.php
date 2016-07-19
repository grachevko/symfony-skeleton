<?php

namespace AppBundle\Doctrine;

use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;

/**
 * @author Konstantin Grachev <ko@grachev.io>
 */
class BundleNamingStrategy extends UnderscoreNamingStrategy
{
    public function classToTableName($className)
    {
        $underscored = parent::classToTableName($className);

        if (strpos($className, 'Entity') && $pos = strpos($className, 'Bundle')) {
            $underscored = strtolower(substr($className, 0, $pos)).'_'.$underscored;
        }

        return $underscored;
    }
}
