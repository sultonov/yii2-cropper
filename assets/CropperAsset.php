<?php

namespace sultonov\cropper\assets;

use yii\web\AssetBundle;

/**
 * Widget asset bundle
 */
class CropperAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@sultonov/cropper/web/';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/cropper.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/cropper.js',
        'js/cropper-init.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
