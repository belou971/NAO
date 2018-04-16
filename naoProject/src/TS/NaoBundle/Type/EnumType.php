<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 16/04/18
 * Time: 16:13
 */

namespace TS\NaoBundle\Type;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

abstract class EnumType extends Type
{
    protected $nameType;
    protected $values = array();

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array $fieldDeclaration The field declaration.
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $lovalues = array_map(function($val) { return "'".$val."'"; }, $this->getValues());

        return "enum(".implode(", ", $lovalues).")";
    }


    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return $this->nameType;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if(!in_array($value, $this->values, true)){
            throw new \UnexpectedValueException(sprintf('La valeur "%s" n\'est pas un type de %s reconnu. Les valeurs
             attendues sont [%s]',
                $value, $this->nameType, implode(", ", $this->values)));
        }

        return $value;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }

    public abstract function getValues();
}