<?php
use backend\models\AccessResources;

/* @var $this yii\web\View */
/* @var $model common\models\User */
?>
<div class="row">
    <div class="col col-lg-12 resource_panel">
        <ul class="list-unstyled resource_list ">
            <?php foreach (AccessResources::parseResources(true) as $group => $resources): ?>
                <li class="res-group">
                    <h4 class=""><?= $group ?></h4>
                    <hr/>
                    <ul class="list-unstyled form-group checkbo">
                        <?php foreach ($resources as $resource => $label): ?>
                            <?php $canAccess = $model->canAccessToResource($resource); ?>
                            <li>
                                <label class="">
                                    <input type="checkbox" name="User[resource][]"
                                        <?= $canAccess ? "checked='checked'" : '' ?>
                                           value="<?= $resource ?>">
                                    <?= $label ?>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
