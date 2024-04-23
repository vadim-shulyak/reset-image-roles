## Magento 2 ResetImageRoles
##### Updates Image Roles. It copies the Base Image value to a specific Image Role (small_image, thumbnail, swatch_image).




### Installation:
```shell
composer require enjoydevelop/magento2-module-reset-image-roles
```

```shell
bin/magento module:enable EnjoyDevelop_ResetImageRoles && bin/magento setup:upgrade && bin/magento setup:di:compile && bin/magento setup:static-content:deploy -f
```


### Usage
#### Set small_image, thumbnail, swatch_image roles to the same image as used for Base Image Role
```shell
bin/magento catalog:image-roles:reset small_image,thumbnail,swatch_image
```
