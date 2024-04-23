## Magento 2 ResetImageRoles
##### Updates Image Roles. It copies the Base Image value to a specific Image Role (small_image, thumbnail, swatch_image).
##### What does this module solve? 
###### In case when your product has the image and Base role, but misses other roles. So you can set other roles using this script.


### Installation:
```shell
composer require enjoydevelop/magento2-module-reset-image-roles
```

```shell
bin/magento module:enable EnjoyDevelop_ResetImageRoles && bin/magento setup:upgrade && bin/magento setup:di:compile && bin/magento setup:static-content:deploy -f
```


### Usage
#### Set the small_image role to the same images as used for Base Image Role
```shell
bin/magento catalog:image-roles:reset small_image
```

#### Set the small_image, thumbnail, swatch_image roles to the same images as used for Base Image Role
```shell
bin/magento catalog:image-roles:reset small_image,thumbnail,swatch_image
```

#### Set the custom_role_attribute_code role to the same images as used for Base Image Role
```shell
bin/magento catalog:image-roles:reset custom_role_attribute_code
```

#### Set the custom_role_attribute_id role to the same images as used for Base Image Role
```shell
bin/magento catalog:image-roles:reset custom_role_attribute_id
```
