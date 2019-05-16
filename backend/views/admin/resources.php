<?php

use backend\components\View;
use backend\models\AccessResources;
use backend\widgets\checkbo\CheckBoAsset;

/* @var $this View */
/* @var $model common\models\Admin */
?>
<div class="row">
    <div class="col col-lg-12 resource_panel ">
        <ul class="list-unstyled resource_list checkbo">
            <?php foreach (AccessResources::parseResources(true) as $group => $resources): ?>
                <li class="">
                    <h4><?= $group ?></h4>
                    <hr/>
                    <ul class="list-unstyled form-group ">
                        <?php foreach ($resources as $resource => $label): ?>
                            <?php $canAccess = $model->canAccessToResource($resource); ?>
                            <li class="">
                                <label class="cb-checkbox <?= $canAccess ? 'checked' : '' ?>">
                                    <input type="checkbox" name="Admin[resource][]"
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
