<?php
/**
 * Created by JetBrains PhpStorm.
 * User: leon
 * Date: 27.06.12
 * Time: 10:45
 * To change this template use File | Settings | File Templates.
 */
namespace Archfest\Bundle\Lib;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class BaseTranslation
{
    private static $supportedLanguages = array('en', 'ru');

    /**
     * @var integer $id
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $locale
     * @ORM\Column(type="string", length=8)
     */
    protected $locale;

    /**
     * @var string $field
     *
     * @ORM\Column(type="string", length=32)
     */
    protected $field;

    /**
     * @var string $content
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content;

    /**
     * @var BaseTranslatableEntity
     */
    protected $object;

    /**
     * @return array
     */
    public static function getSupportedLanguages()
    {
        return self::$supportedLanguages;
    }

    /**
     * @param string $locale
     * @param string $field
     * @param string $value
     */
    public final function __construct($locale, $field, $value)
    {
        $this->setLocale($locale);
        $this->setField($field);
        $this->setContent($value);
    }

    /**
     * Set object
     *
     * @param BaseTranslatableEntity $object
     */
    public function setObject(BaseTranslatableEntity $object)
    {
        $this->object = $object;
    }

    /**
     * Get object
     *
     * @return BaseTranslatableEntity
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return BaseTranslation
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set field
     *
     * @param string $field
     * @return BaseTranslation
     */
    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }

    /**
     * Get field
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return BaseTranslation
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

}