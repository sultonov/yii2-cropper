Yii2 Cropper
===========
Yii-Framework extension for uploading and cropping images based on [CropperJs](https://fengyuanchen.github.io/cropperjs/).

Readme file in uzbek language: [README_UZ.MD](README_UZ.md)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require sultonov/yii2-cropper "dev-master"
```

or add

```
"sultonov/yii2-cropper": "dev-master"
```

to the require section of your `composer.json` file.

Usage
-----

Once the extension is installed, simply use it in your code by  :

```
use sultonov\cropper\CropperWidget;
```


```
<?php $form = ActiveForm::begin(['id' => 'form-profile']); ?>
    <?php echo $form->field($model, 'photo')->widget(CropperWidget::className(), [
        'uploadUrl' => Url::toRoute('/controller-name/uploadPhoto'),
        'width' => 100,
        'height' => 300,
    ]) ?>
    <div class="form-group">
        <?php echo Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>
```
Widget has following properties:

| Name     | Description    | Default |  Required   |
| --------|---------|-------|------|
| uploadParameter  | Upload parameter name | file    |No |
| width  | The final width of the image after cropping | 200    |No |
| height  | The final height of the image after cropping | 200    |No |
| label  | Hint in box for preview | It depends on application language. You can translate this message on your language and make pull-request.    |No |
| uploadUrl  | URL for uploading and cropping image |     |Yes |
| prefixUrl  | Prefix URL for uploaded image |     |No |
| noPhotoImage  | The picture, which is used when a photo is not loaded. | Extension default no-image file   |No |
| maxSize  | The maximum file size (kb).  | 2097152    |No |
| avatar  | Circle box for selection area | false    |No |
| preview  | Preview of selection area | false    |No |
| aspectRatio | Fix aspect ratio of cropping area | null |No |
| extensions  | Allowed file extensions (string). | jpeg, jpg, png, gif    |No |
| free  | Free box for selection area | false    |No |


In UserController:

```
use sultonov\cropper\actions\UploadAction;
```

```
public function actions()
{
    return [
        'upload-photo' => [
            'class' => UploadAction::className(),
            'url' => '',
            'path' => 'some-path',
        ]
    ];
}
```
Action has following parameters:

| Name     | Description    | Default |  Required   |
| --------|---------|-------|------|
| path  | Path for saving image after cropping |     |Yes |
| url  | URL to which the downloaded images will be available. |  |Yes |
| uploadParam  | Upload parameter name. It must match the value of a similar parameter of the widget. | file    |No |
| maxSize  | The maximum file size (kb). It must match the value of a similar parameter of the widget. | 2097152    |No |
| extensions  | Allowed file extensions (string). It must match the value of a similar parameter of the widget. | jpeg, jpg, png, gif    |No |
| width  | The final width of the image after cropping. It must match the value of a similar parameter of the widget. | 200    |No |
| height  | The final height of the image after cropping. It must match the value of a similar parameter of the widget. | 200    |No |
| jpegQuality  | Quality of cropped image (JPG) | 100    |No |
| pngCompressionLevel  | Quality of cropped image (PNG) | 1    |No |
| prefixPath  |Prefix URL for uploaded image |     |No |
