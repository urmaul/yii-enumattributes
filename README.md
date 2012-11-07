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
```

### Options

* attribute (string) - enum attribute name
* labels (array) - custom labels array (value => label)

### Class phpDoc

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

### Values

Now you can retrieve list of possible values by calling behavior like that:

```php
$model->statusEnum->values
```

Values order is equal to values order in DB structure.

### Labels

Or you can retrieve a map "value => label" by calling behavior like that:

```php
$model->statusEnum->valueLabels
```

Values order is equal to values order in DB structure.

Labels are generated from values using [CModel::generateAttributeLabel](http://www.yiiframework.com/doc/api/1.1/CModel#generateAttributeLabel-detail) function. You can set custom labels using the **labels**  behavior option.
