<?php

namespace EnjoyDevelop\ResetImageRoles\Console;

use EnjoyDevelop\ResetImageRoles\Model\ImageRoles;
use Magento\Framework\Console\Cli;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResetImageRoles extends Command
{
    private const TYPE_ARGUMENT_NAME = 'roles';

    private bool $validationFailed = false;

    public function __construct(
        private ImageRoles $imageRoles,
        private LoggerInterface $logger,
        string $name = null
    ) {
        parent::__construct($name);
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('catalog:image-roles:reset');
        $this->setDescription(
            'Takes the Base image role\'s value and sets the same value to a particular image Role(s).'
        );
        $this->setDefinition(
            [
                new InputArgument(
                    self::TYPE_ARGUMENT_NAME,
                    InputArgument::REQUIRED,
                    'Roles to reset. Allowed options: small_image, thumbnail, swatch_image.
                    To pass a few/all roles use comma without spacing (e.g.: `catalog:image-roles:reset small_image,thumbnail,swatch_image`,
                    `catalog:image-roles:reset 87,88,89`). Custom roles also supported: you can pass the product
                    attribute id/code that has the frontend_input=`media_image`)'
                )
            ]
        );

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $roles = $input->getArgument(self::TYPE_ARGUMENT_NAME);
        $roles = explode(',', $roles);

        foreach ($roles as $role) {
            if (!$this->imageRoles->isRoleValid($role)) {
                $output->writeln("<error>$role is not a valid role (incorrect attribute_code/attribute_id).</error>");
                $this->validationFailed = true;
            }
        }

        if ($this->validationFailed) {
            return CLI::RETURN_FAILURE;
        }

        foreach ($roles as $role) {
            try {
                $this->imageRoles->reset($role, $output);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
            }
        }

        return Cli::RETURN_SUCCESS;
    }
}
