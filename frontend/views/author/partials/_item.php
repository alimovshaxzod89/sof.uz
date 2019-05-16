<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use common\models\Blogger;
use frontend\components\View;
use Imagine\Image\ManipulatorInterface;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * Date: 12/13/17
 * Time: 12:48 AM
 */

/**
 * @var $this  View
 * @var $model Blogger
 */

$this->title = __('Mualliflar');
?>
<a href="mailto:<?= $model->email ?>" class="mail-author"><i class="icon mail-author-icon"></i></a>

<div class="media">
    <img src="<?= Blogger::getCropImage($model->image, 90, 90, ManipulatorInterface::THUMBNAIL_OUTBOUND, false, 95) ?>"
         width="90" height="90" alt="<?= $model->getFullname() ?>">
</div><!-- End of media-->

<p class="title"><strong><?= $model->getFullname() ?></strong><?= $model->job ?></p>

<p><?= $model->intro ?></p>

<p class="continue"><a data-pjax="0"
                       href="<?= Url::to(['author/view', 'login' => $model->login]) ?>"><?= __('Барча мақолалари') ?></a>
</p>
