<?php

namespace EnjoyDevelop\ResetImageRoles\Model;

use Magento\Catalog\Setup\CategorySetup;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResourceConnection;
use Symfony\Component\Console\Helper\ProgressBar;

class ImageRoles
{
    private const BASE_ROLE_ATTRIBUTE = 'image';

    private string $eavAttribute;

    public function __construct(
        private ResourceConnection $resourceConnection
    ) {
        $this->eavAttribute = $this->resourceConnection->getTableName('eav_attribute');
    }

    public function reset($role, $output): void
    {
        $imagesToReset = $this->getCollectionToReset($role);

        $om = ObjectManager::getInstance();
        $progressBar = $om->create(
            ProgressBar::class,
            [
                'output' => $output,
                'max' => count($imagesToReset)
            ]
        );

        $progressBar->setFormat(
            "<info>Resetting the Role: $role</info> %current%/%max% [%bar%] %percent:3s%% %elapsed% %memory:6s%"
        );

        $progressBar->start();
        $progressBar->display();

        foreach ($imagesToReset as $image) {
            $this->updateAttributeValue($image);

            $progressBar->advance();
            $progressBar->display();
        }

        $progressBar->finish();
        $output->writeln('');
    }

//    private function getCollectionToReset($attributeId)
//    {
//        $select = "SELECT cpev.value_id, cpemg.value FROM catalog_product_entity_media_gallery_value_to_entity AS cpemgvte
//                   JOIN catalog_product_entity_media_gallery AS cpemg ON cpemgvte.value_id = cpemg.value_id
//                   LEFT JOIN catalog_product_entity_varchar AS cpev ON cpemgvte.entity_id = cpev.entity_id
//                   WHERE cpev.attribute_id IN {$attributeId} AND cpemg.value != cpev.value";
//
//        return $this->resourceConnection->getConnection()->fetchAssoc($select);
//    }

    /**
     * @param $role
     * @return array
     */
    private function getCollectionToReset($role): array
    {
        $imageToResetAttributeId = $this->getRoleAttributeId($role);
        $baseImageAttributeId = $this->getRoleAttributeId(self::BASE_ROLE_ATTRIBUTE);

        $select = "SELECT
                        e2.value_id AS value_id_to_update,
                        e1.value AS new_value
                    FROM
                        catalog_product_entity_varchar AS e1
                        JOIN catalog_product_entity_varchar AS e2
                            ON e1.entity_id = e2.entity_id
                    WHERE
                        e1.attribute_id = '{$baseImageAttributeId}'
                        AND e2.attribute_id = '{$imageToResetAttributeId}'
                        AND e1.value != e2.value;";

        return $this->resourceConnection->getConnection()->fetchAssoc($select);
    }

    /**
     * @param $data
     * @return void
     */
    private function updateAttributeValue($data): void
    {
        $update = "UPDATE catalog_product_entity_varchar SET value = '{$data['new_value']}'
                   WHERE value_id = '{$data['value_id_to_update']}'";
        $this->resourceConnection->getConnection()->query($update);
    }

    private function getRoleAttributeId($role)
    {
        $entityTypeId = CategorySetup::CATALOG_PRODUCT_ENTITY_TYPE_ID;

        if (is_numeric($role)) {
            $query = "SELECT attribute_id
                      FROM {$this->eavAttribute}
                      WHERE attribute_id='{$role}' AND frontend_input='media_image' AND entity_type_id={$entityTypeId}";
        } else {
            $query = "SELECT attribute_id
                      FROM {$this->eavAttribute}
                      WHERE attribute_code='{$role}' AND frontend_input='media_image' AND entity_type_id={$entityTypeId}";
        }

        return $this->resourceConnection->getConnection()->fetchOne($query);
    }

    /**
     * @param $role
     * @return bool
     */
    public function isRoleValid($role): bool
    {
        return (bool)$this->getRoleAttributeId($role);
    }
}
