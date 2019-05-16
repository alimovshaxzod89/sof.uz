<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/**
 * Created by PhpStorm.
 * Date: 12/18/17
 * Time: 1:48 AM
 */

namespace frontend\widgets;


use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use ymaker\social\share\assets\SocialIconsAsset;
use ymaker\social\share\widgets\SocialShare;

class SocialSharer extends SocialShare
{


    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if ($this->isDefaultIconsAssetEnabled()) {
            $this->getView()->registerAssetBundle(SocialIconsAsset::class);
        }

        if ($this->isSeoEnabled()) {
            echo '<!--noindex-->';
        }

        $containerTag = ArrayHelper::remove($this->containerOptions, 'tag', false);

        if ($containerTag) {
            echo Html::beginTag($containerTag, $this->containerOptions);
        }

        $wrapTag = ArrayHelper::remove($this->linkContainerOptions, 'tag', 'li');

        foreach ($this->getLinkList() as $k => $link) {
            echo $wrapTag ? Html::tag($wrapTag, $link, ['class' => $k]) : $link;
        }

        if ($containerTag) {
            echo Html::endTag($containerTag);
        }

        if ($this->isSeoEnabled()) {
            echo '<!--/noindex-->';
        }
    }

    /**
     * Returns array with share links in <a> HTML tag.
     * @return array
     */
    private function getLinkList()
    {
        $socialNetworks = $this->configurator->getSocialNetworks();
        $shareLinks     = [];

        foreach ($socialNetworks as $key => $socialNetwork) {
            if (isset($socialNetwork['class'])) {
                /* @var \ymaker\social\share\base\AbstractDriver $driver */
                $driver = $this->createDriver($socialNetwork);

                $linkOptions         = $this->combineOptions($socialNetworks);
                $linkOptions['href'] = $driver->getLink();
                Html::addCssClass($linkOptions, 'social social-' . $key);
                $shareLinks[$key] = Html::tag('a', $this->getLinkLabel($socialNetwork, Inflector::camel2words($key)), $linkOptions);
            }
        }

        return $shareLinks;
    }

    /**
     * Build label for driver.
     *
     * @param array $driverConfig
     * @param string $defaultLabel
     *
     * @return string
     */
    protected function getLinkLabel($driverConfig, $defaultLabel)
    {
        return Html::tag('i', '', ['class' => 'ui-'.$this->configurator->getIconSelector($driverConfig['class'])])
            . (isset($driverConfig['label']) ? $driverConfig['label'] : $defaultLabel);
    }

    /**
     * Combine global and custom HTML options.
     * @param array $driverConfig
     * @return array
     */
    private function combineOptions($driverConfig)
    {
        $options = isset($driverConfig['options']) ? $driverConfig['options'] : [];

        $globalOptions = $this->configurator->getOptions();
        if (empty($globalOptions)) {
            return $options;
        }

        if (isset($options['class'])) {
            Html::addCssClass($globalOptions, $options['class']);
            unset($options['class']);
        }

        return ArrayHelper::merge($globalOptions, $options);
    }

    /**
     * Creates driver object.
     * @param array $driverConfig
     * @return object
     * @throws \yii\base\InvalidConfigException
     */
    private function createDriver($driverConfig)
    {
        /* @var \ymaker\social\share\base\AbstractDriver $driver */
        $driver = Yii::createObject(ArrayHelper::merge([
                                                           'class'       => $driverConfig['class'],
                                                           'url'         => $this->url,
                                                           'title'       => $this->title,
                                                           'description' => $this->description,
                                                           'imageUrl'    => $this->imageUrl,
                                                       ], isset($driverConfig['config']) ? $driverConfig['config'] : []));

        if (key_exists($driverConfig['class'], $this->driverProperties)) {
            foreach ($this->driverProperties[$driverConfig['class']] as $property => $value) {
                if ($driver->hasProperty($property)) {
                    $driver->$property = $value;
                }
            }
        }

        return $driver;
    }

}