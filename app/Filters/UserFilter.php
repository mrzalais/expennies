<?php

declare(strict_types=1);

namespace App\Filters;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class UserFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        return $targetTableAlias . '.user_id = ' . $this->getParameter('user_id');
    }
}
