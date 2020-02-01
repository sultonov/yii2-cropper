<?php

namespace sultonov\cropper\actions;

use sultonov\cropper\CropperWidget;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Yii;
use yii\base\Action;
use yii\base\DynamicModel;
use yii\base\InvalidConfigException;
use yii\imagine\Image;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class UploadAction extends Action
{
    public $path;
    public $url;
    public $uploadParam = 'file';
    public $maxSize = 2097152;
    public $extensions = 'jpeg, jpg, png, gif';
    public $jpegQuality = 100;
    public $pngCompressionLevel = 1;
    public $prefixPath = "";

    /**
     * @inheritdoc
     */
    public function init()
    {
        CropperWidget::registerTranslations();
        if ($this->url === null) {
            throw new InvalidConfigException(Yii::t('cropper', 'MISSING_ATTRIBUTE', ['attribute' => 'url']));
        } else {
            $this->url = rtrim($this->url, '/') . '/';
        }
        if ($this->path === null) {
            throw new InvalidConfigException(Yii::t('cropper', 'MISSING_ATTRIBUTE', ['attribute' => 'path']));
        } else {
            $this->path = rtrim(Yii::getAlias($this->path), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstanceByName($this->uploadParam);
            $model = new DynamicModel(compact($this->uploadParam));
            $model->addRule($this->uploadParam, 'image', [
                'skipOnEmpty' => false,
                'maxSize' => $this->maxSize,
                'tooBig' => Yii::t('cropper', 'TOO_BIG_ERROR', ['size' => $this->maxSize / (1024 * 1024)]),
                'extensions' => explode(', ', $this->extensions),
                'wrongExtension' => Yii::t('cropper', 'EXTENSION_ERROR', ['formats' => $this->extensions])
            ])->validate();

            if ($model->hasErrors()) {
                $result = [
                    'error' => $model->getFirstError($this->uploadParam)
                ];
            } else {
                $model->{$this->uploadParam}->name = uniqid() . '.' . $model->{$this->uploadParam}->extension;
                $request = Yii::$app->request;
                $width = intval($request->post('w'));
                $height = intval($request->post('h'));
                $x = intval($request->post('x'));
                $y = intval($request->post('y'));
                $image = Image::getImagine()->open($file->tempName . $request->post('filename'));
                if ($x + $width <= 0 || $x > $image->getSize()->getWidth()){
                    $image = Image::getImagine()->create(new Box($width, $height));
                }
                elseif ($x < 0 && $width + $x > $image->getSize()->getWidth()){
                    $image = Image::crop(
                        $file->tempName . $request->post('filename'),
                        $image->getSize()->getWidth(),
                        $height,
                        [0, $y]
                    );
                    $white = Image::getImagine()->create(new Box($width, $height));
                    $image = $white->paste($image, new Point(-$x, 0));
                }
                elseif ($x < 0){
                    $image = Image::crop(
                        $file->tempName . $request->post('filename'),
                        $width + $x,
                        $height,
                        [0, $y]
                    );
                    $white = Image::getImagine()->create(new Box($width, $height));
                    $image = $white->paste($image, new Point(-$x, 0));
                }
                elseif ($x + $width > $image->getSize()->getWidth()){
                    $image = Image::crop(
                        $file->tempName . $request->post('filename'),
                        $image->getSize()->getWidth() - $x,
                        $height,
                        [$x, $y]
                    );
                    $white = Image::getImagine()->create(new Box($width, $height));
                    $image = $white->paste($image, new Point(0, 0));
                }
                else {
                    $image = Image::crop(
                        $file->tempName . $request->post('filename'),
                        $width,
                        $height,
                        [$x, $y]
                    );
                }
                $image->resize(
                    new Box($request->post('width'), $request->post('height'))
                );

                if (!file_exists($this->path) || !is_dir($this->path)) {
                    $result = [
                        'error' => Yii::t('cropper', 'ERROR_NO_SAVE_DIR')];
                } else {
                    $saveOptions = ['jpeg_quality' => $this->jpegQuality, 'png_compression_level' => $this->pngCompressionLevel];
                    if ($image->save($this->path . $model->{$this->uploadParam}->name, $saveOptions)) {
                        $result = [
                            'filelink' => $this->url . $model->{$this->uploadParam}->name,
                            'prefixPath' => $this->prefixPath
                        ];
                    } else {
                        $result = [
                            'error' => Yii::t('cropper', 'ERROR_CAN_NOT_UPLOAD_FILE')
                        ];
                    }
                }
            }
            Yii::$app->response->format = Response::FORMAT_JSON;

            return $result;
        } else {
            throw new BadRequestHttpException(Yii::t('cropper', 'ONLY_POST_REQUEST'));
        }
    }
}
