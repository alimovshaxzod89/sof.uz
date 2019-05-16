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
<div class="posts-dynamic posts-container ts-row grid count-0 grid-cols-2 mb-45">
    <div class="posts-wrap">
        {items}

        <div class="col-3"></div>
        <div class="col-6">
            <div class="main-pagination load-more">
                {pager}
            </div>
        </div>
    </div>

</div>
