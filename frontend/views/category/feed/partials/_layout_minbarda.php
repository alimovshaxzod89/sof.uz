<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */


/**
 * @var $model \frontend\models\CategoryProvider
 */
$model = isset($this->params['category']) ? $this->params['category'] : false;
?>
<div class="block-content mb-45">
    <div class="posts-dynamic posts-container grid count-0 grid-cols-2 no-margins ">
        {items}
    </div>
    <div class="main-pagination load-more">
        {pager}
    </div>
</div>
