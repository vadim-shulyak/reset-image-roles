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


![image](https://github.com/vadim-shulyak/reset-image-roles/assets/9142847/21faf414-4019-4242-b5b4-aaea1358f0d8)


![image](https://github.com/vadim-shulyak/reset-image-roles/assets/9142847/68c123f1-4388-44fb-9c1e-227f729686cc)

![image](https://github.com/vadim-shulyak/reset-image-roles/assets/9142847/6607f2c3-80b1-4c5d-81e9-b389a0153223)

