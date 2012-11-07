# Yii Enumattributes

Yii ActiveRecord behavior used to work with ENUM attributes

## Attaching example

You need to add this behavior to behaviors:

```php
public function behaviors()
{
    return array(
        ...
        'statusEnum' => array(
            'class' => 'ext.behaviors.enumattributes.EnumAttributesBehavior',
            'attribute' => 'status',
        ),
    );
}
`

And also you should add behavior property to phpDoc comments:

```php
/**
 * ...
 * 
 * @property EnumAttributesBehavior $statusEnum
 */
class ...
```

## Using

Now you can retrieve list of possible values by calling behavior like that:

```php
$model->statusEnum->values
```

Or you can retrieve a map "value => label" by calling behavior like that:

```php
$model->statusEnum->valueLabels
```
