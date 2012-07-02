yii-enumattributes
==================

Yii ActiveRecord behavior used to work with ENUM attributes

Attaching example

You need to add this behavior to behaviors:

```php
public function behaviors()
{
    return array(
        ...
        array(
            'class' => 'ext.behaviors.enumattributes.EnumAttributesBehavior',
            'attribute' => 'status',
        ),
    );
}
```

And also you should add behavior property to phpDoc comments&

```php
/**
 * ...
 * 
 * @property EnumAttributesBehavior $statusEnum
 */
class ...
```
