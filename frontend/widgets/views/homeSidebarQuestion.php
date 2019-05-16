<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use common\models\Post;
use frontend\models\PostProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * @var $questions PostProvider[]
 */

?>
    <h2 class="sidebar__questions-title"><?= __('Let\'s answer') ?></h2>
<?php if (count($questions)): ?>
    <p><a href="#">Хориждаги ваколатхоналар кимларга паспорт беради?</a></p>

    <p><a href="#">“Ички ишлар органлари тўғрисида”ги қонун қабул қилинди: нима ўзгарди?</a></p>

    <p><a href="#">Фитрат: “Дунёнинг энг бой, энг бахтсиз бир тилини биласизми?”</a></p>

    <p class="continue"><a href="#"><?= __('All questions') ?></a></p>
<?php else: ?>
    <code><?= $this->context->emptyText ?></code>
<?php endif; ?>