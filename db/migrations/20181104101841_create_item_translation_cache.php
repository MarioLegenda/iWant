<?php


use Phinx\Migration\AbstractMigration;

class CreateItemTranslationCache extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $sql = 'CREATE TABLE item_translation_cache (id INT AUTO_INCREMENT NOT NULL, unique_name VARCHAR(255) NOT NULL, item_id VARCHAR(255) NOT NULL, translations LONGTEXT NOT NULL, stored_at DATETIME NOT NULL, expires_at INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_AB876C7E98AB450A (unique_name), UNIQUE INDEX UNIQ_AB876C7E126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB';

        $this->execute($sql);
    }
}
