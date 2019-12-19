<?php

namespace sultonov\cropper;

use sultonov\cropper\assets\CropperAsset;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\widgets\InputWidget;
use Yii;

class CropperWidget extends InputWidget
{
    public $uploadParameter = 'file';
    public $width = 200;
    public $height = 200;
    public $label = '';
    public $uploadUrl;
    public $prefixUrl = '';
    public $noPhotoImage = '';
    public $maxSize = 2097152;
    public $avatar = false;
    public $preview = false;
    public $extensions = 'jpeg, jpg, png, gif';
    public $aspectRatio = null;
    public $free = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::registerTranslations();

        if ($this->uploadUrl === null) {
            throw new InvalidConfigException(Yii::t('cropper', 'MISSING_ATTRIBUTE', ['attribute' => 'uploadUrl']));
        } else {
            $this->uploadUrl = rtrim(Yii::getAlias($this->uploadUrl), '/') . '/';
        }

        if ($this->label == '') {
            $this->label = Yii::t('cropper', 'DEFAULT_LABEL');
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientAssets();

        return $this->render('widget', [
            'model' => $this->model,
            'widget' => $this
        ]);
    }

    /**
     * Register widget asset.
     */
    public function registerClientAssets()
    {
        if ($this->avatar)
            $this->width = $this->height;
        $view = $this->getView();
        $assets = CropperAsset::register($view);

        if ($this->noPhotoImage == '') {
            $this->noPhotoImage = $assets->baseUrl . '/img/nophoto.png';
        }

        $settings = [
            'url' => $this->uploadUrl,
            'name' => $this->uploadParameter,
            'maxSize' => $this->maxSize,
            'width' => $this->width,
            'height' => $this->height,
            'prefix_url' => $this->prefixUrl,
            'attribute' => $this->attribute,
            'free' => $this->free,
            'allowedExtensions' => explode(', ', $this->extensions),
            'upload_error' => Yii::t('cropper', 'ERROR_CAN_NOT_UPLOAD_FILE'),
            'upload_success' => Yii::t('cropper', 'UPLOAD_SUCCESS'),
            'size_error_text' => Yii::t('cropper', 'TOO_BIG_ERROR', ['size' => $this->maxSize / (1024 * 1024)]),
            'ext_error_text' => Yii::t('cropper', 'EXTENSION_ERROR', ['formats' => $this->extensions]),
            'accept' => 'image/*',
        ];

        if(is_numeric($this->aspectRatio)) {
                $settings['aspectRatio'] = $this->aspectRatio;
        }

        $view->registerJs(
            'initWidget(' . Json::encode($settings) . ')',
            $view::POS_READY
        );
    }

    /**
     * Register widget translations.
     */
    public static function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['cropper']) && !isset(Yii::$app->i18n->translations['cropper/*'])) {
            Yii::$app->i18n->translations['cropper'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@sultonov/cropper/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'cropper' => 'cropper.php'
                ]
            ];
        }
    }
}
