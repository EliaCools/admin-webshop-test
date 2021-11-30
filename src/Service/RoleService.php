<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\Security\Core\Security;

class RoleService
{
    /*
     *I'm not happy about injecting the containerInterface directly,
     *but in this case I don't know a better way to get all top level roles
     */
    private array $roles;
    private Security $security;

    public function __construct(ContainerInterface $container, Security $security)
    {
        $this->roles = $container->getParameter('security.role_hierarchy.roles');
        $this->security = $security;
    }

    public function getAllTopLevelRoles(): array
    {
    $mutatedArray = [];
    foreach (array_keys($this->roles) as $key => $value){
       if(false === strpos($value, "SONATA")) {
           $mutatedArray[strtolower(str_replace("_", " ", $value)) ] = $value;
       }
    }
    return $mutatedArray;
    }
}
