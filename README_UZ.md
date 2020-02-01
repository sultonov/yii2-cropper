Yii2 Cropper
===========
[CropperJs](https://fengyuanchen.github.io/cropperjs/) kutubxonasi asosida rasmlarni yuklash va qirqish uchun Yii2-freymforki ilovasi.

O'rnatish
------------

Ushbu ilovani o'rnatishning qulay usuli [composer](http://getcomposer.org/download/) orqali bajariladi.

Shunchaki terminalda buyruq bajarish orqali:

```
composer require sultonov/yii2-cropper "dev-master"
```

yoki quyidagi

```
"sultonov/yii2-cropper": "dev-master"
```

qatorni `composer.json` faylning require bo'limiga qo'shish orqali.

Qo'llanilishi
-----

Ilovani o'rnatgandan so'ng, uni kodingizda quyidagi qatorlarni qo'shish orqali oddiygina foydalanishingiz mumkin:

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
Vidjet quyidagi xususiyatlarni o'z ichiga oladi:

| Nomi     | Tavsifi    | Standart qiymati |  Majburiymi   |
| --------|---------|-------|------|
| uploadParameter  | Yuklash parametri nomi | file    |Yo'q |
| width  | Kesilgandan keyin rasmning natijaviy eni | 200    |Yo'q |
| height  | Kesilgandan keyin rasmning natijaviy bo'yi | 200    |Yo'q |
| label  | Oldindan ko'rish (preview) uchun tavsif | Bu dastur tiliga bog'liq. Siz ushbu xabarni o'zingizning tilingizga tarjima qilishingiz va pull-request jo'natishingiz mumkin.    |Yo'q |
| uploadUrl  | Rasmni yuklash va qirqish uchun URL |     |Ha |
| prefixUrl  | Yuklangan rasm uchun prefiks URL |     |Yo'q |
| noPhotoImage  | Rasm yuklanmagan paytda ishlatiladigan rasm. | Ilovaning rasm bo'lmagan holat uchun standart rasmi   |Yo'q |
| maxSize  | Maksimal fayl hajmi (kb).  | 2097152    |Yo'q |
| avatar  | Kesilish maydoni uchun doira shaklidagi ko'rinish (avatar uchun) | false    |Yo'q |
| preview  | Kesilish maydonini oldindan ko'rish (preview) | false    |Yo'q |
| aspectRatio | Qirqish maydoni proportsiyasi | null |Yo'q |
| extensions  | Ruxsat berilgan fayl kengaytmalari (satr). | jpeg, jpg, png, gif    |Yo'q |
| free  | Kesilish maydonidan cheklovlarni olib tashlash | false    |Yo'q |


Controller da:

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
Action quyidagi xususiyatlarni o'z ichiga oladi:

| Nomi     | Tavsifi    | Standart qiymati |  Majburiymi   |
| --------|---------|-------|------|
| path  | Kesilganidan keyin rasmni saqlash uchun yo'l |     |Ha |
| url  | Yuklab olingan rasmlar mavjud bo'ladigan URL manzili. |  |Ha |
| uploadParam  | Yuklash parametr nomi. Bu vidjetning o'xshash parametrining qiymatiga mos kelishi kerak. | file    |Yo'q |
| maxSize  | Maksimal fayl hajmi (kb). Bu vidjetning o'xshash parametrining qiymatiga mos kelishi kerak. | 2097152    |Yo'q |
| extensions  | Ruxsat berilgan fayl kengaytmalari (satr). Bu vidjetning o'xshash parametrining qiymatiga mos kelishi kerak. | jpeg, jpg, png, gif    |Yo'q |
| width  | Kesilganidan keyin rasmning natijaviy eni. Bu vidjetning o'xshash parametrining qiymatiga mos kelishi kerak. | 200    |Yo'q |
| height  | Kesilganidan keyin rasmning natijaviy bo'yi. Bu vidjetning o'xshash parametrining qiymatiga mos kelishi kerak. | 200    |Yo'q |
| jpegQuality  | Kesilgan tasvir sifati (JPG) | 100    |Yo'q |
| pngCompressionLevel  | Kesilgan tasvir sifati (PNG) | 1    |Yo'q |
| prefixPath  |Yuklangan rasm uchun prefiks URL |     |Yo'q |
