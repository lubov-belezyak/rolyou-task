<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('users');

        $table->addColumn('full_name', 'string')
            ->addColumn('role', 'string')
            ->addColumn('efficiency', 'integer')
            ->addTimestamps()
            ->create();
    }
}
