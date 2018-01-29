Yii2-safe-text
===================
Yii2 extension for hiding personal data(email, phone, etc.) from spam grabbing

Installation
---
Run
```code
composer require "demi/safe-text" "~1.0"
```

# Usage

### Hide phone number:
```php
<?= \demi\safeText\Widget::widget(['text' => '+555 478 24 75']) ?>
```
will be generated:
```javascript
document.write(["5","7"," ","4","2"," ","8","7","4"," ","5","5","5","+"].reverse().join(''));
```

### Hide email:
```php
<?= \demi\safeText\Widget::widget([
    'url' => 'mailto:example@gmail.com',
    'text' => 'example@gmail.com',
]) ?>
```
will be generated:

```javascript
(function() {
    var a = document.createElement('a');
    a.href = ["m","o","c",".","l","i","a","m","g","@","e","l","p","m","a","x","e",":","o","t","l","i","a","m"].reverse().join('');
    var text = ["m","o","c",".","l","i","a","m","g","@","e","l","p","m","a","x","e"].reverse().join('');
    var template = '{text}';
    a.innerHTML = template.replace('{text}', text);

    document.write(a.outerHTML);
}());
```

# Options
All widget options
```php
<?= \demi\safeText\Widget::widget([
    'url' => 'skype:girlfriend?chat',
    'text' => 'girlfriend',
    'linkTextTemplate' => '<i class="fa fa-skype"></i> {text}',
    'linkOptions' => [
        'class' => 'skype-link',
        'target' => '_blank',
    ],
]) ?>
```

will be generated:
```javascript
(function() {
    var a = document.createElement('a');
    a.href = ["t","a","h","c","?","d","n","e","i","r","f","l","r","i","g",":","e","p","y","k","s"].reverse().join('');
    a.setAttribute('class', 'skype-link');
    a.setAttribute('target', '_blank');
    var text = ["d","n","e","i","r","f","l","r","i","g"].reverse().join('');
    var template = '<i class="fa fa-skype"></i> {text}';
    a.innerHTML = template.replace('{text}', text);

    document.write(a.outerHTML);
}());
```
