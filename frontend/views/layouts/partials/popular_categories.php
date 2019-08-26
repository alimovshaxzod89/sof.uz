<?php
/**
 * @var $this \frontend\components\View
 * @var $categories \frontend\models\CategoryProvider[]
 */
$categories = \frontend\models\CategoryProvider::getTrending();
?>
<?php if (is_array($categories) && count($categories)): ?>
    <div class="widget widget_magsy_category_widget">
        <h5 class="u-border-title"><?= __('Categories') ?></h5>
        <ul>
            <?php foreach ($categories as $category): ?>
                <li class="category-item">
                    <a href="<?= $category->getViewUrl() ?>" title="<?= $category->name ?>">
                        <span class="category-name">
                            <i class="dot" style="background-color: #6fd08d;"></i>
                            <?= $category->name ?>
                        </span>
                        <span class="category-count"><?= $category->count_posts ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>