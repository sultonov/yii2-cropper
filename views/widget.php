<?php
/**
 * @var ActiveRecord $model
 * @var CropperWidget $widget
 *
 */

use sultonov\cropper\CropperWidget;
use yii\db\ActiveRecord;
use yii\helpers\Html;

?>

<div class="cropper-widget">
    <?= Html::activeHiddenInput($model, $widget->attribute, ['class' => 'photo-field', 'id' => 'cropper-input-'.$widget->attribute]); ?>
    <label style="cursor: pointer;" class="label" data-toggle="tooltip" title="<?=Yii::t('cropper', 'DEFAULT_LABEL')?>">
        <?= Html::img(
            $model->{$widget->attribute} != ''
                ? $widget->prefixUrl.$model->{$widget->attribute}
                : $widget->noPhotoImage,
            [
                'class' => 'rounded',
                'style' => "max-width: 100%; width: $widget->width px; height: $widget->height px;",
                'id' => 'image-result-'.$widget->attribute,
            ]
        ); ?>
        <?= Html::fileInput('image-input-'.$widget->attribute, null, ['class' => 'sr-only', 'accept' => 'image/*', 'id' => 'image-input-'.$widget->attribute]); ?>
    </label>
    <div class="progress" id="progress-<?=$widget->attribute?>" style="display: none; margin-bottom: 1rem;">
        <div id="progress-bar-<?=$widget->attribute?>" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
    </div>
    <div id="alert-<?=$widget->attribute?>" class="alert" role="alert" style="display: none"></div>
    <?php if ($widget->avatar): ?>
    <style>
        .cropper-view-box,
        .cropper-face {
            border-radius: 50%;
        }
    </style>
    <?php endif; ?>
    <div class="modal fade" id="modal-cropper-<?=$widget->attribute?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" <?=$widget->preview?'style="width: 90%"':''?> role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" style="display: inline-block" id="modalLabel"><?=Yii::t('cropper', 'DESCRIPTION')?></h3>
                    <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if($widget->preview): ?>
                    <div class="row">
                        <div class="col-md-8">
                            <?php endif; ?>
                            <div class="img-container">
                                <img id="image-selected-<?= $widget->attribute ?>" style="max-width: 100%;" alt="<?=Yii::t('cropper', 'DESCRIPTION')?>"/>
                            </div>
                            <?php if($widget->preview): ?>
                        </div>
                        <div class="col-md-4">
                            <h6><?=Yii::t('cropper', 'PREVIEW')?></h6>
                            <div class="preview" style="max-width: 100%"></div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=Yii::t('cropper', 'CANCEL')?></button>
                    <button type="button" class="btn btn-primary" id="crop-button-<?=$widget->attribute?>"><?=Yii::t('cropper', 'CROP_PHOTO')?></button>
                </div>
            </div>
        </div>
    </div>
</div>